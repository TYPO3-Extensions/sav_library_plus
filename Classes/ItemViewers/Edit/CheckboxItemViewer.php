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
 * Default Checkbox item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_ItemViewers_Edit_CheckboxItemViewer extends Tx_SavLibraryPlus_ItemViewers_Edit_AbstractItemViewer {

  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {

    // Checks if it is associated with a mail
    if ($this->getItemConfiguration('mail')) {
      $content = $this->renderSingleMailCheckbox();
    } else {
      $content = $this->renderSingleCheckbox();
    }
    
    // Adds a DIV element
		$content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlDivElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'checkbox'),
        ),
        $content
      );
      
    return $content;
  }
  
  /**
   * Gets the checked attribute.
   *
   * @param none
   *
   * @return string
   */
  protected function getCheckedAttribute() {
    if ($this->getItemConfiguration('value') == 1) {
      $checked = 'checked';
    } else {
      if ($this->getItemConfiguration('uid')) {
        $checked = '';
      } else {
        $checked = ($this->getItemConfiguration('default') ? 'checked' : '');
      }
    }
    
    return $checked;
  }

  /**
   * Renders a single checkbox.
   *
   * @param none
   *
   * @return string
   */
  protected function renderSingleCheckbox() {

    $content = '';
    
    // Adds the hidden input element
    $content .= Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputHiddenElement(
      array(
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', '0'),
      )
    );

    // Adds the checkbox input element
    $content .= Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputCheckBoxElement(
      array(
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', '1'),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttributeIfNotNull('checked', $this->getCheckedAttribute()),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('onchange', 'document.changed=1;'),
      )
    );

    return $content;
  }

  /**
   * Renders a single mail checkbox.
   *
   * @param none
   *
   * @return string
   */
  protected function renderSingleMailCheckbox() {

  	// Gets the value to check for mail
  	$fieldForCheckMail = $this->getItemConfiguration('fieldforcheckmail');
  	if (empty($fieldForCheckMail) === true) {
  		Tx_SavLibraryPlus_Controller_FlashMessages::addError(error.noAttributeInField, array('fieldforcheckmail', $this->getItemConfiguration('fieldName')));
  		return '';
  	}
  	// Gets the icon root path
  	$iconRootPath = Tx_SavLibraryPlus_Managers_LibraryConfigurationManager::getIconRootPath('checkboxSelected.gif');
  	
  	// Gets the value associated with the field
  	$querier = $this->getController()->getQuerier();
  	$valueForChecking = $querier->getFieldValueFromCurrentRow($querier->buildFullFieldName($fieldForCheckMail));
 	
  	// Adds the image
  	if (empty($valueForChecking) === false) {
  		if ($this->getItemConfiguration('value')) {
  			// Adds an image element
		    $content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlImgElement(
		      array(
		        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'mailButton'),
		        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('src', $iconRootPath . 'newMailOff.gif'),
		        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('title', Tx_Extbase_Utility_Localization::translate('button.mail', 'sav_library_plus')),
		        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('alt', Tx_Extbase_Utility_Localization::translate('button.mail', 'sav_library_plus')),
		      )
		    );  			
  		} else {
  			// Adds an input image element
		    $content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputImageElement(
		      array(
		        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'mailButton'),
		        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('src', $iconRootPath . 'newMail.gif'),
	        	Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', Tx_SavLibraryPlus_Controller_AbstractController::getFormName() . '[formAction][saveAndSendMail][' . $this->getCryptedFullFieldName() . ']'),	        
		        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('title', Tx_Extbase_Utility_Localization::translate('button.mail', 'sav_library_plus')),
		        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('alt', Tx_Extbase_Utility_Localization::translate('button.mail', 'sav_library_plus')),
		        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('onclick', 'return update();'),
		      )
		    );
  		}    		
  	} else {
	    $content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlImgElement(
	      array(
	        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'mailButton'),
	        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('src', $iconRootPath . 'newMailOff.gif'),
	        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('title', Tx_Extbase_Utility_Localization::translate('button.mail', 'sav_library_plus')),
	        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('alt', Tx_Extbase_Utility_Localization::translate('button.mail', 'sav_library_plus')),
	      )
	    );            
  	}
  	
  	// Adds the checkbox  	
    $content .= $this->renderSingleCheckbox();

    return $content;
  }
}
?>
