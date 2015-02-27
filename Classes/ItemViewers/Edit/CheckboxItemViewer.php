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
 * Edit Checkbox item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class CheckboxItemViewer extends AbstractItemViewer {

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
		$content = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlDivElement(
        array(
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('class', 'checkbox'),
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
    $content .= \SAV\SavLibraryPlus\Utility\HtmlElements::htmlInputHiddenElement(
      array(
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('value', '0'),
      )
    );

    // Adds the checkbox input element
    $content .= \SAV\SavLibraryPlus\Utility\HtmlElements::htmlInputCheckBoxElement(
      array(
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('value', '1'),
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttributeIfNotNull('checked', $this->getCheckedAttribute()),
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('onchange', 'document.changed=1;'),
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
  	if (empty($fieldForCheckMail) === TRUE) {
  		\SAV\SavLibraryPlus\Controller\FlashMessages::addError('error.noAttributeInField', array('fieldForCheckMail', $this->getItemConfiguration('fieldName')));
  		return '';
  	}
  	
  	// Gets the value associated with the field
  	$querier = $this->getController()->getQuerier();
  	$valueForChecking = $querier->getFieldValue($querier->buildFullFieldName($fieldForCheckMail));

  	// Adds the image
  	if (empty($valueForChecking) === FALSE) {	
  		if ($this->getItemConfiguration('value')) {
  			// Adds an image element
		    $content = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlImgElement(
		      array(
		        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('class', 'mailButton'),
		        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('src', \SAV\SavLibraryPlus\Managers\LibraryConfigurationManager::getIconPath('newMailOff')),
		        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('title', \SAV\SavLibraryPlus\Controller\FlashMessages::translate('button.mail')),
		        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('alt', \SAV\SavLibraryPlus\Controller\FlashMessages::translate('button.mail')),
		      )
		    );  			
  		} else {
  			// Adds an input image element
		    $content = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlInputImageElement(
		      array(
		        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('class', 'mailButton'),
		        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('src', \SAV\SavLibraryPlus\Managers\LibraryConfigurationManager::getIconPath('newMail')),
	        	\SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('name', \SAV\SavLibraryPlus\Controller\AbstractController::getFormName() . '[formAction][saveAndSendMail][' . $this->getCryptedFullFieldName() . ']'),	        
		        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('title', \SAV\SavLibraryPlus\Controller\FlashMessages::translate('button.mail')),
		        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('alt', \SAV\SavLibraryPlus\Controller\FlashMessages::translate('button.mail')),
		        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('onclick', 'return update(\'' . \SAV\SavLibraryPlus\Controller\AbstractController::getFormName() . '\');'),
		      )
		    );
  		}    		
  	} else {
	    $content = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlImgElement(
	      array(
	        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('class', 'mailButton'),
	        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('src', \SAV\SavLibraryPlus\Managers\LibraryConfigurationManager::getIconPath('newMailOff')),
	        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('title', \SAV\SavLibraryPlus\Controller\FlashMessages::translate('button.mail')),
	        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('alt', \SAV\SavLibraryPlus\Controller\FlashMessages::translate('button.mail')),
	      )
	    );            
  	}
  	
  	// Adds the checkbox  	
    $content .= $this->renderSingleCheckbox();

    return $content;
  }
}
?>
