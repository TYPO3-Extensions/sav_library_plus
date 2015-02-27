<?php
namespace SAV\SavLibraryPlus\ItemViewers\Edit;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
*  Copyright notice
*
*  (c) 2011 Laurent Foulloy (yolf.typo3@orange.fr)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Edit RelationManyToManyAsSubform item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class RelationManyToManyAsSubformItemViewer extends AbstractItemViewer {

  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {

  	$htmlArray = array();

    // Builds the crypted field Name
    $fullFieldName = $this->getItemConfiguration('tableName') . '.' . $this->getItemConfiguration('fieldName');
    $cryptedFullFieldName = \SAV\SavLibraryPlus\Controller\AbstractController::cryptTag($fullFieldName);

    // Creates the controller
    $controller =  GeneralUtility::makeInstance('SAV\\SavLibraryPlus\\Controller\\Controller');
    $extensionConfigurationManager = $controller->getExtensionConfigurationManager();
	  $extensionConfigurationManager->injectExtension($this->getController()->getExtensionConfigurationManager()->getExtension());
	  $extensionConfigurationManager->injectTypoScriptConfiguration(\SAV\SavLibraryPlus\Managers\ExtensionConfigurationManager::getTypoScriptConfiguration());
    $controller->initialize();
    
    // Gets the maximum item number in the subform (must be called before the querier to process deprecated maxsubitems attribute)
    $maxSubformItems = $this->getMaximumItemsInSubform();

    // Builds the querier
    $querierClassName = 'SAV\\SavLibraryPlus\\Queriers\\ForeignTableSelectQuerier';
    $querier = GeneralUtility::makeInstance($querierClassName);
    $controller->injectQuerier($querier);
    $querier->injectController($controller);
    $querier->injectUpdateQuerier($this->getController()->getQuerier()->getUpdateQuerier());
    $querier->injectParentQuerier($this->getController()->getQuerier());
    $this->itemConfiguration['uidLocal'] = $this->itemConfiguration['uid'];
    $pageInSubform = \SAV\SavLibraryPlus\Managers\SessionManager::getSubformFieldFromSession($cryptedFullFieldName, 'pageInSubform');
    $pageInSubform = ($pageInSubform ? $pageInSubform : 0);
    $this->itemConfiguration['pageInSubform'] = $pageInSubform;
		// Builds the query
		if ($this->getItemConfiguration('norelation')) {
    	$querier->buildQueryConfigurationForSubformWithNoRelation($this->itemConfiguration);			
		} else {
    	$querier->buildQueryConfigurationForTrueManyToManyRelation($this->itemConfiguration);
		}
    $querier->injectQueryConfiguration();  
    $querier->processTotalRowsCountQuery();
    
    // Gets the rows count
    $totalRowsCount = $querier->getTotalRowsCount();
   
    // Checks if the maximum number of relations is reached
    if ($totalRowsCount < $this->getItemConfiguration('maxitems')) {
    	$newButtonIsAllowed = TRUE;
    } else {
    	$newButtonIsAllowed = FALSE;
    }

    // Processes the query
    if (\SAV\SavLibraryPlus\Managers\UriManager::getFormAction() == 'newInSubform' && \SAV\SavLibraryPlus\Managers\UriManager::getSubformFieldKey() == $cryptedFullFieldName) {
			if (\SAV\SavLibraryPlus\Managers\UriManager::getSubformUidLocal() == $this->itemConfiguration['uidLocal']) {
	      $isNewInSubform = TRUE;
	      $querier->addEmptyRow();
      } else {     
      	return '';
      }      
    } else {
      $isNewInSubform = FALSE;
      $querier->processQuery();
    }

    // Calls the viewer
    $viewerClassName = 'SAV\\SavLibraryPlus\\Viewers\\SubformEditViewer';
    $viewer = GeneralUtility::makeInstance($viewerClassName);
    $viewer->setIsNewView($isNewInSubform);
    $controller->injectViewer($viewer);
    $viewer->injectController($controller);
    $subformConfiguration = $this->getItemConfiguration('subform');
    
    if ($subformConfiguration === NULL) {
      \SAV\SavLibraryPlus\Controller\FlashMessages::addError('error.noFieldSelectedInSubForm');
    }
    $viewer->injectLibraryViewConfiguration($subformConfiguration);
    
    // Adds the hidden element
    $htmlArray[] = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlInputHiddenElement(
      array (
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('value', $this->getItemConfiguration('value')),
      )
    );

    // Gets the subform title
    $subformTitle = $this->getItemConfiguration('subformtitle');
    if (empty($subformTitle)) {
    	// Gets the label cutter
    	$cutLabel = $this->getItemConfiguration('cutlabel');
    	if (empty($cutLabel)) {
    		$subformTitle = $this->getItemConfiguration('label');
    	}
    }    
    
    // Sets the view configuration
    $deleteButtonIsAllowed = $this->getItemConfiguration('adddelete') || $this->getItemConfiguration('adddeletebutton');
    $upDownButtonIsAllowed = $this->getItemConfiguration('addupdown') || $this->getItemConfiguration('addupdownbutton');
    $saveButtonIsAllowed = $this->getItemConfiguration('addsave') || $this->getItemConfiguration('addsavebutton');
    $viewer->addToViewConfiguration('general',
      array (
      	'newButtonIsAllowed' => $newButtonIsAllowed,
        'deleteButtonIsAllowed' => ($isNewInSubform === FALSE) && $deleteButtonIsAllowed && !$viewer->errorsInNewRecord(),
        'upDownButtonIsAllowed' => ($isNewInSubform === FALSE) && $upDownButtonIsAllowed,
        'saveButtonIsAllowed' => ($isNewInSubform === FALSE) && $saveButtonIsAllowed,
        'subformFieldKey' => $cryptedFullFieldName,
        'subformUidLocal' => $this->getItemConfiguration('uid'),
        'pageInSubform' => $pageInSubform,
        'maximumItemsInSubform' => $maxSubformItems,
      	'showFirstLastButtons' => ($this->getItemConfiguration('nofirstlast') ? 0 : 1),
        'title' => $controller->getViewer()->processTitle($subformTitle),
      	'saveAndNew' => array_key_exists($this->getItemConfiguration('foreign_table'), $this->getController()->getLibraryConfigurationManager()->getGeneralConfigurationField('saveAndNew')),
      )
    );
    
    $htmlArray[] = $viewer->render();

    return $this->arrayToHTML($htmlArray);
  }

  /**
   * Gets the maximum number of items in a subform.
   *
   * @param none
   *
   * @return integer
   */
  protected function getMaximumItemsInSubform() {  
  	// Checks if the deprecated "maxsubitems" attribute is used
  	$maxSubItems = $this->getItemConfiguration('maxsubitems');
  	if (empty($maxSubItems) === FALSE) {
  		// Replaces it by the "maxsubformitems" attribute
  		$this->itemConfiguration['maxsubformitems'] = $maxSubItems;
  		unset($this->itemConfiguration['maxsubitems']);
  	}
  	$maxSubformItems = $this->getItemConfiguration('maxsubformitems');
 		if (empty($maxSubformItems) === FALSE) {
 			return $maxSubformItems;
 		} else {
	 		return 0;
 		}
  }
  
}
?>
