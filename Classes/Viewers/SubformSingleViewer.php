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
 * Default Subform Single Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_Viewers_SubformSingleViewer extends Tx_SavLibraryPlus_Viewers_SingleViewer {

  /**
   * The template file
   *
   * @var string
   */
  protected $templateFile = 'EXT:sav_library_plus/Resources/Private/Templates/Default/SubformSingle.html';
  
  /**
   * Renders the view
   *
   * @param none
   *
   * @return string The rendered view
   */
  public function render() {
    
    // Sets the active folder Key
    $this->setActiveFolderKey();

    // Creates the field configuration manager
    $fieldConfigurationManager = t3lib_div::makeInstance('Tx_SavLibraryPlus_Managers_FieldConfigurationManager');
    $fieldConfigurationManager->injectController($this->getController());

    // Processes the rows
    $configurationRows = array();
    $rowsCount = $this->getController()->getQuerier()->getRowsCount();

    for ($rowKey=0; $rowKey < $rowsCount; $rowKey++) {
      $this->getController()->getQuerier()->setCurrentRowId($rowKey);

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

      $configurationRows[] = $this->folderFieldsConfiguration;
    }

    // Adds the fields configuration
    $this->addToViewConfiguration('rows', $configurationRows);

    // Page information for the page browser
    $pageInSubform = $this->getFieldFromGeneralViewConfiguration('pageInSubform');
    $maximumItemsInSubform = $this->getFieldFromGeneralViewConfiguration('maximumItemsInSubform');
    $lastPageInSubform = floor(($this->getController()->getQuerier()->getTotalRowsCount() - 1) / $maximumItemsInSubform);
    $maxPagesInSubform = $this->getController()->getExtensionConfigurationManager()->getMaxPages();
    $pagesInSubform = array();
    for($i = min($pageInSubform, max(0, $lastPageInSubform - $maxPagesInSubform)); $i <= min($lastPageInSubform, $pageInSubform + $maxPagesInSubform - 1); $i++) {
      $pagesInSubform[$i] = $i + 1;
    }

    // Adds information to the view configuration
    $this->addToViewConfiguration('general',
      array(
        'lastPageInSubform' => $lastPageInSubform,
        'pagesInSubform' => $pagesInSubform,
      )
    );

    // Renders the view
    return $this->renderView();
  }

}
?>
