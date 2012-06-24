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
 
class Tx_SavLibraryPlus_ItemViewers_Default_CheckboxItemViewer extends Tx_SavLibraryPlus_ItemViewers_Default_AbstractItemViewer {

  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {
  
    if ($this->itemConfigurationNotSet('displayasimage') || $this->getItemConfiguration('displayasimage')) {
      $renderIfChecked = Tx_SavLibraryPlus_Utility_HtmlElements::htmlDivElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'checkbox'),
        ),
        $this->renderCheckedAsImage()
      );
      $renderIfNotChecked = Tx_SavLibraryPlus_Utility_HtmlElements::htmlDivElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'checkbox'),
        ),
        $this->renderNotCheckedAsImage()
      );
    } else {
      $renderIfChecked = Tx_Extbase_Utility_Localization::translate('itemviewer.yes', 'sav_library_plus');
      $renderIfNotChecked = (
        $this->getItemConfiguration('donotdisplayifnotchecked') ?
        '' :
        Tx_Extbase_Utility_Localization::translate('itemviewer.no', 'sav_library_plus')
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
  		$imageFileName = 'checkboxSelected.gif';
  	}
  	// Gets the icons directory for the file name
    $iconsDirectory = $this->getController()->getLibraryConfigurationManager()->getIconsDirectory($imageFileName);  
    // Renders the content
    $content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlImgElement(
      array(
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'checkboxSelected'),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('src', $iconsDirectory . $imageFileName),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('title', Tx_Extbase_Utility_Localization::translate('itemviewer.checkboxSelected', 'sav_library_plus')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('alt', Tx_Extbase_Utility_Localization::translate('itemviewer.checkboxSelected', 'sav_library_plus')),
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
  		$imageFileName = 'clear.gif';
  	} else {
  		$imageFileName = $this->getItemConfiguration('checkboxnotselectedimage');
  		if (empty($imageFileName)) {
  			$imageFileName = 'checkboxNotSelected.gif';
  		}
  	}
  	
  	// Gets the icons directory for the file name
    $iconsDirectory = $this->getController()->getLibraryConfigurationManager()->getIconsDirectory($imageFileName);
    // Renders the content
    $content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlImgElement(
      array(
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'checkboxNotSelected'),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('src', $iconsDirectory . $imageFileName),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('title', Tx_Extbase_Utility_Localization::translate('itemviewer.checkboxNotSelected', 'sav_library_plus')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('alt', Tx_Extbase_Utility_Localization::translate('itemviewer.checkboxNotSelected', 'sav_library_plus')),
      )
    );

    return $content;
  }
}
?>
