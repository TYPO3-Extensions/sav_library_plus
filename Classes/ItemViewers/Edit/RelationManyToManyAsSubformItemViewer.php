<?php
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
 
class Tx_SavLibraryPlus_ItemViewers_Edit_RelationManyToManyAsSubformItemViewer extends Tx_SavLibraryPlus_ItemViewers_Edit_AbstractItemViewer {

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
    $cryptedFullFieldName = Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($fullFieldName);

    // Creates the controller
    $controller =  t3lib_div::makeInstance('Tx_SavLibraryPlus_Controller_Controller');
    $extensionConfigurationManager = $controller->getExtensionConfigurationManager();
	  $extensionConfigurationManager->injectExtension($this->getController()->getExtensionConfigurationManager()->getExtension());
	  $extensionConfigurationManager->injectTypoScriptConfiguration(array());
    $controller->initialize();

    // Builds the querier
    $querierClassName = 'Tx_SavLibraryPlus_Queriers_ForeignTableSelectQuerier';
    $querier = t3lib_div::makeInstance($querierClassName);
    $controller->injectQuerier($querier);
    $querier->injectController($controller);
    $this->itemConfiguration['uidLocal'] = $this->itemConfiguration['uid'];
    $pageInSubform = Tx_SavLibraryPlus_Managers_SessionManager::getSubformFieldFromSession($cryptedFullFieldName, 'pageInSubform');
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

    if (Tx_SavLibraryPlus_Managers_UriManager::getFormAction() == 'newInSubform' && Tx_SavLibraryPlus_Managers_UriManager::getSubformFieldKey() == $cryptedFullFieldName) {
      $isNewInSubform = true;
      $querier->addEmptyRow();
    } else {
      $isNewInSubform = false;
      $querier->processQuery();
    }

    // Calls the viewer
    $viewerClassName = 'Tx_SavLibraryPlus_Viewers_SubformEditViewer';
    $viewer = t3lib_div::makeInstance($viewerClassName);
    $controller->injectViewer($viewer);
    $viewer->injectController($controller);
    $subformConfiguration = $this->getItemConfiguration('subform');
    if ($subformConfiguration === NULL) {
      Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.noFieldSelectedInSubForm');
    }
    $viewer->injectLibraryViewConfiguration($subformConfiguration);
    
    // Adds the hidden element
    $htmlArray[] = Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputHiddenElement(
      array (
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', $this->getItemConfiguration('value')),
      )
    );

    // Gets the subform title
    $subformTitle = $this->getItemConfiguration('subformtitle');
    if (empty($subformTitle)) {
    	$subformTitle = $this->getItemConfiguration('label');
    }    
    
    // Sets the view configuration
    $deleteButtonIsAllowed = $this->getItemConfiguration('adddelete') || $this->getItemConfiguration('adddeletebutton');
    $upDownButtonIsAllowed = $this->getItemConfiguration('addupdown') || $this->getItemConfiguration('addupdownbutton');
    $saveButtonIsAllowed = $this->getItemConfiguration('addsave') || $this->getItemConfiguration('addsavebutton');
    $viewer->addToViewConfiguration('general',
      array (
        'deleteButtonIsAllowed' => ($isNewInSubform === false) && $deleteButtonIsAllowed,
        'upDownButtonIsAllowed' => ($isNewInSubform === false) && $upDownButtonIsAllowed,
        'saveButtonIsAllowed' => ($isNewInSubform === false) && $saveButtonIsAllowed,
        'subformFieldKey' => $cryptedFullFieldName,
        'subformUidLocal' => $this->getItemConfiguration('uid'),
        'pageInSubform' => $pageInSubform,
        'maximumItemsInSubform' => $this->getItemConfiguration('maxsubformitems'),
        'title' => $controller->getViewer()->processTitle($subformTitle),
      	'saveAndNew' => array_key_exists($this->getItemConfiguration('foreign_table'), $this->getController()->getLibraryConfigurationManager()->getGeneralConfigurationField('saveAndNew')),
      )
    );
    
    $htmlArray[] = $viewer->render();

    return $this->arrayToHTML($htmlArray);
  }
  
}
?>
