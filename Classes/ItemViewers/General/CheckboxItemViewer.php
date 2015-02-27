<?php
namespace SAV\SavLibraryPlus\ItemViewers\General;

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
 * General Checkbox item Viewer.
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
  
    if ($this->itemConfigurationNotSet('displayasimage') || $this->getItemConfiguration('displayasimage')) {
      $renderIfChecked = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlDivElement(
        array(
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('class', 'checkbox'),
        ),
        $this->renderCheckedAsImage()
      );
      $renderIfNotChecked = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlDivElement(
        array(
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('class', 'checkbox'),
        ),
        $this->renderNotCheckedAsImage()
      );
    } else {
      $renderIfChecked = \SAV\SavLibraryPlus\Controller\FlashMessages::translate('itemviewer.yes');
      $renderIfNotChecked = (
        $this->getItemConfiguration('donotdisplayifnotchecked') ?
        '' :
        \SAV\SavLibraryPlus\Controller\FlashMessages::translate('itemviewer.no')
      );
    }

    // Gets the value
    $value = $this->getItemConfiguration('value');
    
    if (empty($value)) {
      return $renderIfNotChecked;
    } else {
      return $renderIfChecked;
    }
  }
  
  /**
   * Renders a checked checkbox as an image.
   *
   * @param none
   *
   * @return string
   */
  protected function renderCheckedAsImage() {
  	// Gets the image file name
  	$imageFileName = $this->getItemConfiguration('checkboxselectedimage');
  	if (empty($imageFileName)) {
  		$imageFileName = 'checkboxSelected';
  	}

    // Renders the content
    $content = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlImgElement(
      array(
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('class', 'checkboxSelected'),
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('src', \SAV\SavLibraryPlus\Managers\LibraryConfigurationManager::getIconPath($imageFileName)),
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('title', \SAV\SavLibraryPlus\Controller\FlashMessages::translate('itemviewer.checkboxSelected')),
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('alt', \SAV\SavLibraryPlus\Controller\FlashMessages::translate('itemviewer.checkboxSelected')),
      )
    );

    return $content;
  }

  /**
   * Renders a unchecked checkbox as an image.
   *
   * @param none
   *
   * @return string
   */
  protected function renderNotCheckedAsImage() {
  	// Gets the image file name
  	if ($this->getItemConfiguration('donotdisplayifnotchecked')) {
  		$imageFileName = 'clear';
  	} else {
  		$imageFileName = $this->getItemConfiguration('checkboxnotselectedimage');
  		if (empty($imageFileName)) {
  			$imageFileName = 'checkboxNotSelected';
  		}
  	}
  	
    // Renders the content
    $content = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlImgElement(
      array(
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('class', 'checkboxNotSelected'),
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('src', \SAV\SavLibraryPlus\Managers\LibraryConfigurationManager::getIconPath($imageFileName)),
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('title', \SAV\SavLibraryPlus\Controller\FlashMessages::translate('itemviewer.checkboxNotSelected')),
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('alt', \SAV\SavLibraryPlus\Controller\FlashMessages::translate('itemviewer.checkboxNotSelected')),
      )
    );

    return $content;
  }
}
?>
