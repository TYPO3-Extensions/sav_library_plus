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
 * Default List Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_Viewers_ListViewer extends Tx_SavLibraryPlus_Viewers_AbstractViewer {

  /**
   * Item viewer directory
   *
   * @var string
   */
  protected $itemViewerDirectory = 'Default';
  
  /**
   * Edit mode flag
   *
   * @var boolean
   */
  protected $inEditMode = false;
  
  /**
   * The template file
   *
   * @var string
   */
  protected $templateFile = 'List.html';
  
  /**
   * The previous folder fields configuration
   *
   * @var array
   */  
  protected $previousFolderFieldsConfiguration = array();
   
  /**
   * Renders the view
   *
   * @param none
   *
   * @return string The rendered view
   */
  public function render() {
  
    // Sets the library view configuration
    $this->setLibraryViewConfiguration('ListView');

    // Sets the active folder Key
    $this->setActiveFolderKey();

    // Creates the template configuration manager
    $templateConfigurationManager = t3lib_div::makeInstance('Tx_SavLibraryPlus_Managers_TemplateConfigurationManager');
    $templateConfigurationManager->injectTemplateConfiguration($this->getLibraryConfigurationManager()->getListViewTemplateConfiguration());

    // Creates the field configuration manager
    $this->createFieldConfigurationManager();
        
    // Gets the item template
    $itemTemplate = $templateConfigurationManager->getItemTemplate();

    // Processes the rows
    $rows = $this->getController()->getQuerier()->getRows();

    $fields = array();
    foreach ($rows as $rowKey => $row) {

      $this->getController()->getQuerier()->setCurrentRowId($rowKey);
      
    	// Gets the fields configuration for the folder
    	$this->folderFieldsConfiguration = $this->getFieldConfigurationManager()->getFolderFieldsConfiguration($this->getActiveFolder(), true, true);
  	
      $listItemConfiguration = array_merge( $this->parseItemTemplate($itemTemplate),
        array(
          'uid' => $row['uid'],
        )
      );
      // Additional list item configuration
      $listItemConfiguration = array_merge($listItemConfiguration, $this->additionalListItemConfiguration());
      $fields[] = $listItemConfiguration;
      
      $this->previousFolderFieldsConfiguration = $this->folderFieldsConfiguration;
    }
   
    // Adds the fields configuration
    $this->addToViewConfiguration('fields', $fields);
    
    // Page information for the page browser
    $page = Tx_SavLibraryPlus_Managers_UriManager::getPage();
    $lastPage = floor(($this->getController()->getQuerier()->getTotalRowsCount() - 1) / $this->getController()->getExtensionConfigurationManager()->getMaxItems());
    $maxPages = $this->getController()->getExtensionConfigurationManager()->getMaxPages();
    $pages = array();
    for($i = min($page, max(0, $lastPage - $maxPages)); $i <= min($lastPage, $page + $maxPages - 1); $i++) {
      $pages[$i] = $i + 1;
    }

    // Adds information to the view configuration
    $this->addToViewConfiguration('general',
      array(
        'extensionKey' => $this->getController()->getExtensionConfigurationManager()->getExtensionKey(),
        'userIsAllowedToInputData' => $this->getController()->getUserManager()->userIsAllowedToInputData(),
        'userIsAllowedToExportData' => $this->getController()->getUserManager()->userIsAllowedToExportData(),
        'helpPage' => $this->getController()->getExtensionConfigurationManager()->getHelpPageForListView(),
        'addPrintIcon' => $this->getActiveFolderField('addPrintIcon'),
        'page' => $page,
        'lastPage' => $lastPage,
        'pages' => $pages,
        'title' => $this->processTitle($this->parseTitle($this->getActiveFolderTitle())),
      )
    );

    // Additional view configuration if no rows are returned by the querier
    $this->additionalViewConfigurationIfNoRows();
    
    // Additional view configuration
    $this->additionalViewConfiguration();

    return $this->renderView();
  }

  /**
   * Adds elements to the item list configuration
   *
   * @param none
   *
   * @return none
   */
  protected function additionalListItemConfiguration() {
    return array();
  }

  /**
   * Adds elements to the view configuration
   *
   * @param none
   *
   * @return none
   */
  protected function additionalViewConfiguration() {
  }

  /**
   * Parses the item template
   *
   * @param $itemTemplate string The item template
   *
   * @return string The item configuration
   */
  protected function parseItemTemplate($itemTemplate) {

    $itemConfiguration = array();
    $fields = array();
    	
  	// Gets the querier
  	$querier = $this->getController()->getQuerier();

    // Gets the tags
    preg_match_all('/###(?P<render>render\[)?(?P<fullFieldName>(?<TableNameOrAlias>[^\.#\]]+)\.?(?<fieldName>[^#\]]*))\]?###/', $itemTemplate, $matches);
   
    // Sets the default class item
    $classItem = 'item';
    foreach($matches[0] as $matchKey => $match) {
    	
      // Gets the crypted full field name
      $fullFieldName =  $this->getController()->getQuerier()->buildFullFieldName($matches['fullFieldName'][$matchKey]);  
      $cryptedFullFieldName = Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($fullFieldName);

			// Checks if the configuration exists for the field name
			if (is_null($this->folderFieldsConfiguration[$cryptedFullFieldName])) {
				Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.unknownFieldName', array($fullFieldName));
			}
      
      // Checks if the value must be cut 
      if ($this->folderFieldsConfiguration[$cryptedFullFieldName]['cutDivItemInner']) {
      	$value = '';
      } else {
      	// It's a full field name, i.e. tableName.fieldName, without render
      	if ($matches['fieldName'][$matchKey] && empty($matches['render'][$matchKey])) {
      		$value = $this->folderFieldsConfiguration[$cryptedFullFieldName]['value'];
      	} else {
      		$value = $this->renderItem($cryptedFullFieldName);
      	}
      }     

      // Sets the class item
      if ($this->folderFieldsConfiguration[$cryptedFullFieldName]['classItem'] != 'item') {
        $classItem = $this->folderFieldsConfiguration[$cryptedFullFieldName]['classItem'];  
      } 
      
      // Processes the cutIfSameAsPrevious attribute if any
      if ($this->folderFieldsConfiguration[$cryptedFullFieldName]['cutifsameasprevious']) { 
        if ($this->folderFieldsConfiguration[$cryptedFullFieldName]['value'] == $this->previousFolderFieldsConfiguration[$cryptedFullFieldName]['value']) {
          $value = '';
          $classItem = 'item';
        }
      }      
           
      // Renders the item
      $itemTemplate = str_replace($matches[0][$matchKey], $value, $itemTemplate);
      
      // Sets the field configuration for the fluid processings
      if ($matches['fieldName'][$matchKey]) {
      	$fields[$matches['TableNameOrAlias'][$matchKey]] = array($matches['fieldName'][$matchKey] => $this->folderFieldsConfiguration[$cryptedFullFieldName]);      		
      } else {
      	$fields[$matches['fullFieldName'][$matchKey]] = $this->folderFieldsConfiguration[$cryptedFullFieldName];
      }      
    }

    // Creates a view for more fluid processings of the template
    $view = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');
    $view->setTemplateSource($itemTemplate);

    // Assigns the field configuration and renders the view
    $view->assign('field', $fields);
    $itemTemplate = $view->render();    
     
    // Sets the class item
    $itemConfiguration = array(
      'classItem' => $classItem,
      'template' => $itemTemplate,
    );
 
    return $itemConfiguration;
  }

  /**
   * Parses the item template
   *
   * @param $itemTemplate string The item template
   *
   * @return string The parsed item template
   */
  protected function parseTitle($title) { 	
  	
    // Replaces the tags in the title by $$$label[tag]$$$
    preg_match_all('/###(linkDefault|link)?(?:\[)?(([^\.#\]]+)[\.]?([^#\]]*))(?:\])?###/', $title, $matches);
 
    // Processes the matched information
    foreach($matches[0] as $matchKey => $match) {
    
      // Gets the field configuration 
      if  ($matches[1][$matchKey]) {
      	// It is tag for a simple link with no ordering
      	
      	// Gets the extension key
    		$extensionKey = $this->getController()->getExtensionConfigurationManager()->getExtensionKey();
      	
    		// Gets the label
				$label = Tx_Extbase_Utility_Localization::translate($matches[2][$matchKey], $extensionKey);
      	if (empty($label)) {
      		$label = $matches[2][$matchKey];
      	}  

      	// Sets the field configuration
      	$fieldConfiguration = array (
      		'orderlinkintitle' => 1,
      	  'linkwithnoordering' => 1,
      		'orderlinkintitlesetup' => ':' . $matches[1][$matchKey] . ':',
      		'label' => $label,
      		'fieldName' => $matches[2][$matchKey],
      	); 
      } elseif ($matches[3][$matchKey]) {
      	// It is a tag with a full field name
        $fullFieldName = $matches[2][$matchKey];
      	$cryptedFullFieldName = Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($fullFieldName);
      	$fieldConfiguration = $this->folderFieldsConfiguration[$cryptedFullFieldName];        
      } else {
      	// It is a tag with a short field name, the main table is assumed      	
        $mainTable = $queryConfigurationManager->getMainTable();
        $fullFieldName = $mainTable . '.' . $matches[2][$matchKey];
      	$cryptedFullFieldName = Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($fullFieldName);
      	$fieldConfiguration = $this->folderFieldsConfiguration[$cryptedFullFieldName];  
      }

      // Checks if an order link in title is set
      if ($fieldConfiguration['orderlinkintitle']) {
      	$replacementString = $this->processLink($fieldConfiguration);
      }	else {
        $replacementString = '$$$label[' . $matches[2][$matchKey] . ']$$$';
      }
    
      $title = str_replace($matches[0][$matchKey], $replacementString , $title);
    }

    return $title;
  }

  /**
   * Processes the link
   *
   * @param array $fieldConfiguration The field configuration
   *
   * @return string
   */  
	protected function processLink($fieldConfiguration) {

		$replacementString = '';

    // Gets the query configuration manager
    $queryConfigurationManager = $this->getController()->getQuerier()->getQueryConfigurationManager();
		
		//Builds the field name and full field name
    $fieldName = $fieldConfiguration['fieldName'];
    $fullFieldName = ($fieldConfiguration['tableName'] ? $fieldConfiguration['tableName'] . '.' . $fieldName : $fieldName);
   		
    // Gets the ascending whereTag Key
		$order = ($fieldConfiguration['linkwithnoordering'] ? '' : '+');
		$whereTagAscendingOrderKey = Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($fullFieldName . $order);
		if ($queryConfigurationManager->getWhereTag($whereTagAscendingOrderKey) == NULL) {
			$whereTagAscendingOrderKey = Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($fieldName . $order);
		}
		if ($queryConfigurationManager->getWhereTag($whereTagAscendingOrderKey) == NULL) {
			Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.noWhereTag', array($fullFieldName . $order, $fieldName . $order));
		}
		// Gets the descending whereTag Key
		$order = ($fieldConfiguration['linkwithnoordering'] ? '' : '-');        
		$whereTagDescendingOrderKey = Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($fullFieldName . $order);
		if ($queryConfigurationManager->getWhereTag($whereTagDescendingOrderKey) == NULL) {
			$whereTagDescendingOrderKey = Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($fieldName . $order);
		}
		if ($queryConfigurationManager->getWhereTag($whereTagDescendingOrderKey) == NULL) {
			Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.noWhereTag', array($fullFieldName . $order, $fieldName . $order));
		}
        
		// Sets the default pattern for the display
		if(!isset($fieldConfiguration['orderlinkintitlesetup'])) {
			$fieldConfiguration['orderlinkintitlesetup'] = ':link:';
		}
		$orderLinksInTitle = explode(':', $fieldConfiguration['orderlinkintitlesetup']);

		foreach($orderLinksInTitle as $orderLinkInTitle) {
			if($orderLinkInTitle) {

				// Creates the view
				$view = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');
				$view->setTemplatePathAndFilename($this->getPartialRootPath() . '/TitleBars/OrderLinks/'. ucfirst($orderLinkInTitle) . '.html');

				// Assigns the view configuration
				$view->assign('field', array(
					'value' => $fieldConfiguration['label'],
					'whereTagAscendingOrderKey' => $whereTagAscendingOrderKey,
					'whereTagDescendingOrderKey' => $whereTagDescendingOrderKey,
					'whereTagKey' => Tx_SavLibraryPlus_Managers_UriManager::getWhereTagKey(),
					'inEditMode' => ($this->inEditMode ? 'InEditMode' : ''),
					)
				);
				
    		//Gets the link configuration
    		$linkConfiguration = $this->getLinkConfiguration();				
				$view->assign('configuration',array(
					'general' => array(
	      		'additionalParams' => Tx_SavLibraryPlus_Controller_AbstractController::convertLinkAdditionalParametersToArray($linkConfiguration['additionalParams']),
	      		)	
	      	)
    		);
    		
				$replacementString .= $view->render();	
			}  
    }
    return $replacementString;
	} 
  
  /**
   * Additional view configuration if no rows are returned by the querier
   *
   * @param none
   *
   * @return none
   */
  protected function additionalViewConfigurationIfNoRows() {

  	// Gets the rows count
  	$rowsCount = $this->getController()->getQuerier()->getRowsCount();

  	// Builds the message when the rows count is equal to zero
  	if ($rowsCount == 0) {
  		switch($this->getController()->getExtensionConfigurationManager()->getShowNoAvailableInformation()) {
  			case Tx_SavLibraryPlus_Managers_ExtensionConfigurationManager::SHOW_MESSAGE:
  				$this->addToViewConfiguration('general', array(
  					'message' => Tx_Extbase_Utility_Localization::translate('general.noAvailableInformation', Tx_SavLibraryPlus_Controller_AbstractController::LIBRARY_NAME),
  					)
  				);
  				break;
  			case Tx_SavLibraryPlus_Managers_ExtensionConfigurationManager::DO_NOT_SHOW_EXTENSION:
  				$this->addToViewConfiguration('general', array(
  					'hideExtension' => true,
  					)
  				); 
  				break; 					
  		}
  	}
  }
  
}
?>
