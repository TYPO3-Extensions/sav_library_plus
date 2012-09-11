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
 * Edit File item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_ItemViewers_Edit_FilesItemViewer extends Tx_SavLibraryPlus_ItemViewers_Edit_AbstractItemViewer {

  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {

    $htmlArray = array();

    if ($this->getItemConfiguration('size') < 10) {
      $size = '';
    }

    // Gets the stored file names
    $fileNames = explode(',', $this->getItemConfiguration('value'));
    
    // Adds the items
    for ($counter = 0; $counter < $this->getItemConfiguration('maxitems'); $counter++) {
    
      // Sets the file name
      $fileName = ($fileNames[$counter] ? $fileNames[$counter] : '');
      
      // Adds the text element
      $content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputTextElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName') . '[' . $counter . ']'),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'fileText'),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', $fileName),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('size', $size),
        )
      );

      // Adds the file element
      $content .= Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputFileElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName') . '[' . $counter . ']'),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'fileInput'),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', ''),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('size', $size),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('onchange', 'document.changed=1;'),
        )
      );
      
      // Adds the hyperlink if required
      if ($this->getItemConfiguration('addlinkineditmode') && empty($fileName) === false) {
		    // Gets the upload folder
		    $uploadFolder = $this->getUploadFolder();   
		       	
    		// Builds the typoScript configuration
				$typoScriptConfiguration = array(
      		'parameter'  =>  $uploadFolder . '/' . $fileName, 
      		'fileTarget'  => $this->getItemConfiguration('target') ? $this->getItemConfiguration('target') : '_blank',						
    		);    

    		// Gets the content object
    		$contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();
   
   			// Builds the content  
   			$message = Tx_SavLibraryPlus_Controller_FlashMessages::translate('general.clickHereToOpenInNewWindow');
      	$content .= Tx_SavLibraryPlus_Utility_HtmlElements::htmlSpanElement(
      		array(
      			Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'fileLink'),
      		),
      		$contentObject->typolink($message, $typoScriptConfiguration)
      	);
      }
      
      // Adds the DIV elements
      $htmlArray[] = Tx_SavLibraryPlus_Utility_HtmlElements::htmlDivElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'file item' . $counter),
        ),
        $content
      );
    }

    return $this->arrayToHTML($htmlArray);
  }

 	/**
	 * Gets the upload folder
	 *
	 * @param none
	 *
	 * @return string
	 */
  protected function getUploadFolder() {
    $uploadFolder = $this->getItemConfiguration('uploadfolder');
    $uploadFolder .= ($this->getItemConfiguration('addToUploadFolder') ? '/' . $this->getItemConfiguration('addToUploadFolder') : '');

    return $uploadFolder;
  }
}
?>
