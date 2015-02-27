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
 * Edit Link item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class LinkItemViewer extends AbstractItemViewer {

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
    	// Initializes the content
    	$content = '';
 		   
  		// Adds an input image element
  		$generateRtfButton = FALSE;
  		$generateRtfButtonCondition = $this->getItemConfiguration('generatertfbuttonif');
    	if (!empty($generateRtfButtonCondition)) {
    		$fieldConfigurationManager = GeneralUtility::makeInstance('SAV\\SavLibraryPlus\\Managers\\FieldConfigurationManager');
    		$fieldConfigurationManager->injectController($this->getController());	
    		$fieldConfigurationManager->injectQuerier($this->getController()->getQuerier());	
    		$generateRtfButton = $fieldConfigurationManager->processFieldCondition($generateRtfButtonCondition);
	    }	  	
	    	
  		if (empty($generateRtfButtonCondition) || (!empty($generateRtfButtonCondition) && $generateRtfButton)) {
			  $content = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlInputImageElement(
			    array(
						\SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('class', 'generateRtfButton'),
			      \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('src', \SAV\SavLibraryPlus\Managers\LibraryConfigurationManager::getIconPath('generateRtf')),
		        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('name', \SAV\SavLibraryPlus\Controller\AbstractController::getFormName() . '[formAction][saveAndGenerateRtf][' . $this->getCryptedFullFieldName() . ']'),	        
			      \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('title', \SAV\SavLibraryPlus\Controller\FlashMessages::translate('button.generateRtf')),
			      \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('alt', \SAV\SavLibraryPlus\Controller\FlashMessages::translate('button.generateRtf')),
			      \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('onclick', 'return update(\'' . \SAV\SavLibraryPlus\Controller\AbstractController::getFormName() . '\');'),
			     )
			  );    	
  		}
  		
    	// Adds the hidden input element
    	$content .= \SAV\SavLibraryPlus\Utility\HtmlElements::htmlInputHiddenElement(
      	array(
        	\SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
        	\SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('value', $value),
      	)
    	);		  
		  
		  if (empty($value) === FALSE) {  	
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
			$content = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlDivElement(
        array(
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('class', 'generateRtf'),
        ),
        $content
      );    	
    	
    } else {
    	
	    // Gets the size
	    $size = ($this->getItemConfiguration('size') < 20 ? 40 : $this->getItemConfiguration('size'));
    	
      // Adds the Input text element
      $content = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlInputTextElement(
        array(
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('value', stripslashes($value)),
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('size', $size),
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('onchange', 'document.changed=1;'),
        )
      );
    }

    return $content;
  }
}
?>
