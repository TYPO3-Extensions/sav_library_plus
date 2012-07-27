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
 * Edit Link item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_ItemViewers_Edit_LinkItemViewer extends Tx_SavLibraryPlus_ItemViewers_Edit_AbstractItemViewer {

  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {
  
    // Gets the value
    $value = $this->getItemConfiguration('value');
    $value = ($value == NULL ? '' : $value);

    if ($this->getItemConfiguration('generatertf')) {
  		// Gets the icon root path
  		$iconRootPath = Tx_SavLibraryPlus_Managers_LibraryConfigurationManager::getIconRootPath('generateRtf.gif');   
  		   
  		// Adds an input image element
		  $content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputImageElement(
		    array(
					Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'generateRtfButton'),
		      Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('src', $iconRootPath . 'generateRtf.gif'),
	        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', Tx_SavLibraryPlus_Controller_AbstractController::getFormName() . '[formAction][saveAndGenerateRtf][' . $this->getCryptedFullFieldName() . ']'),	        
		      Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('title', Tx_Extbase_Utility_Localization::translate('button.generateRtf', 'sav_library_plus')),
		      Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('alt', Tx_Extbase_Utility_Localization::translate('button.generateRtf', 'sav_library_plus')),
		      Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('onclick', 'return update();'),
		     )
		  );    	

    	// Adds the hidden input element
    	$content .= Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputHiddenElement(
      	array(
        	Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
        	Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', $value),
      	)
    	);		  
		  
		  if (empty($value) === false) {  	
        $path_parts = pathinfo($this->getItemConfiguration('savefilertf')); 
        $folder = $path_parts['dirname'];    
        $this->setItemConfiguration('folder', $folder);	
        $fileName = $folder . '/' . $value; 
 	
				// Checks if the file exists
				if (file_exists($fileName)) {
		  		$content .= $this->makeLink($value);
				} else {
					$content .= $value;
				}
		  } 
		        	
    	// Adds a DIV element
			$content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlDivElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'generateRtf'),
        ),
        $content
      );    	
    	
    } else {
    	
	    // Gets the size
	    $size = ($this->getItemConfiguration('size') < 20 ? 40 : $this->getItemConfiguration('size'));
    	
      // Adds the Input text element
      $content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputTextElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', stripslashes($value)),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('size', $size),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('onchange', 'document.changed=1;'),
        )
      );
    }

    return $content;
  }
}
?>
