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
 * Default Single Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_Viewers_SingleViewer extends Tx_SavLibraryPlus_Viewers_AbstractViewer {

  /**
   * Item viewer directory
   *
   * @var string
   */
  protected $itemViewerDirectory = 'Default';

  /**
   * The template file
   *
   * @var string
   */
  protected $templateFile = 'Single.html';

  /**
   * Renders the view
   *
   * @param none
   *
   * @return string The rendered view
   */
  public function render() {

    // Sets the library view configuration
    $this->setLibraryViewConfiguration('SingleView');
 
    // Sets the active folder Key
    $this->setActiveFolderKey();

    // Creates the field configuration manager
    $this->createFieldConfigurationManager();
    
    // Gets the fields configuration for the folder
    $this->folderFieldsConfiguration = $this->getFieldConfigurationManager()->getFolderFieldsConfiguration($this->getActiveFolder());

    // Processes the fields
    foreach ($this->folderFieldsConfiguration as $fieldConfigurationKey => $fieldConfiguration) {
      // Calls the item viewer
      $this->folderFieldsConfiguration[$fieldConfigurationKey]['value'] = $this->renderItem($fieldConfigurationKey);
    }

    // Adds the folders configuration
    $this->addToViewConfiguration('folders', $this->getFoldersConfiguration());
    
    // Adds the fields configuration
    $this->addToViewConfiguration('fields', $this->folderFieldsConfiguration);

    // Adds general information to the view configuration
    $this->addToViewConfiguration('general',
      array(
        'extensionKey' => $this->getController()->getExtensionConfigurationManager()->getExtensionKey(),
        'hideExtension' => 0,
        'helpPage' => $this->getController()->getExtensionConfigurationManager()->getHelpPageForSingleView(),
        'addPrintIcon' => $this->getActiveFolderField('addPrintIcon'),
        'activeFolderKey' => $this->getActiveFolderKey(),
        'userIsAllowedToInputData' => $this->getController()->getUserManager()->userIsAllowedToInputData(),
        'title' => $this->processTitle($this->getActiveFolderTitle()),
      )
    );
   
    // Renders the view
    return $this->renderView();
  }

}
?>
