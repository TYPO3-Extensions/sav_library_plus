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
 * Default Edit Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_Viewers_EditViewer extends Tx_SavLibraryPlus_Viewers_AbstractViewer {

  /**
   * Item viewer directory
   *
   * @var string
   */
  protected $itemViewerDirectory = 'Edit';

  /**
   * The template file
   *
   * @var string
   */
  protected $templateFile = 'Edit.html';
 
  /**
   * Renders the view
   *
   * @param none
   *
   * @return string The rendered view
   */
  public function render() {
  	
  	// Adds the javascript for the popup to save data when clicking on a folder and data were changed and not saved.  
  	$this->addJavaScript();
   
    // Sets the library view configuration
    $this->setLibraryViewConfiguration('EditView');

    // Sets the active folder Key
    $this->setActiveFolderKey();

    // Creates the field configuration manager
    $fieldConfigurationManager = t3lib_div::makeInstance('Tx_SavLibraryPlus_Managers_FieldConfigurationManager');
    $fieldConfigurationManager->injectController($this->getController());

    // Gets the fields configuration for the folder
    $this->folderFieldsConfiguration = $fieldConfigurationManager->getFolderFieldsConfiguration($this->getActiveFolder());

    // Processes the fields
    foreach ($this->folderFieldsConfiguration as $fieldConfigurationKey => $fieldConfiguration) {
      // Adds the item name
      $uid = $this->getController()->getQuerier()->getFieldValueFromCurrentRow('uid');
      $itemName = Tx_SavLibraryPlus_Controller_AbstractController::getFormName() . '[' . $fieldConfigurationKey . '][' . intval($uid) . ']';
      $this->folderFieldsConfiguration[$fieldConfigurationKey]['itemName'] = $itemName;

      // Calls the item viewer
      $this->folderFieldsConfiguration[$fieldConfigurationKey]['value'] = $this->renderItem($fieldConfigurationKey);
    }

    // Adds the folders configuration
    $this->addToViewConfiguration('folders', $this->getFoldersConfiguration());
    
    // Adds the fields configuration
    $this->addToViewConfiguration('fields', $this->folderFieldsConfiguration);

    // Adds information to the view configuration
    $this->addToViewConfiguration('general',
      array(
        'extensionKey' => $this->getController()->getExtensionConfigurationManager()->getExtensionKey(),
        'hideExtension' => 0,
        'helpPage' => $this->getController()->getExtensionConfigurationManager()->getHelpPageForEditView(),
        'activeFolderKey' => $this->getActiveFolderKey(),
        'formName' => Tx_SavLibraryPlus_Controller_AbstractController::getFormName(),
        'title' => $this->processTitle($this->getActiveFolderTitle()),
      	'saveAndNew' => array_key_exists($this->getController()->getQuerier()->getQueryConfigurationManager()->getMainTable(), $this->getController()->getLibraryConfigurationManager()->getGeneralConfigurationField('saveAndNew')),
				'isNewView' => $this->isNewView,
      	'viewIdentifier' => $this->viewIdentifier,
      )
    );

    // Renders the view
   return $this->renderView();
  }

  /**
   * Adds javaScript for the popup
   *
   * @param none
   *
   * @return none
   */  
	protected function addJavaScript() {  
  	if ($this->getController()->getQuerier()->errorDuringUpdate() === true) {
  		$javaScript = 'document.changed = true;';
  	} else {
  		$javaScript = '';
  	}
  	Tx_SavLibraryPlus_Managers_AdditionalHeaderManager::addJavaScript('documentChanged', $javaScript);
	}
  
}
?>
