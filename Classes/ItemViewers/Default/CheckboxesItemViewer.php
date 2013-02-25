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
 * Default Checkboxes item Viewer.
 *
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_ItemViewers_Default_CheckboxesItemViewer extends Tx_SavLibraryPlus_ItemViewers_Default_CheckboxItemViewer {

  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {
  
    $htmlArray = array();

    $columnsCount = ($this->getItemConfiguration('cols') ? $this->getItemConfiguration('cols') : 1);

    $counter = 0;
    $itemCounter = 0;

    // Gets the value
    $value = $this->getItemConfiguration('value');
    
    // Processes the items
    $items = $this->getItemConfiguration('items');
    foreach ($items as $itemKey => $item) {
      $checked = ($value & 0x01 ? 'checked' : '');
      $value = $value >> 1;

      $message = Tx_SavLibraryPlus_Utility_HtmlElements::htmlSpanElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'checkboxMessage'),
        ),
        stripslashes(Tx_Extbase_Utility_Localization::translate($item[0]))
      );

      // Checks if donotdisplayifnotchecked is set
      if ($this->getItemConfiguration('donotdisplayifnotchecked') && !$checked) {
        $message = '';
      }

      // Sets the class for the item
      $class = 'checkbox item' . $itemKey;

      $itemCounter++;
      if ($itemCounter == $this->getItemConfiguration('nbitems')){
        break;
      }
      if ($counter == $columnsCount){
        // Additional class
        $class .= ' clearLeft';
        // Resets the counter
        $counter = 0;
      }
      $counter++;
      
      // Adds the Div element
      if ($this->itemConfigurationNotSet('displayasimage') || $this->getItemConfiguration('displayasimage')) {
        $renderIfChecked = Tx_SavLibraryPlus_Utility_HtmlElements::htmlDivElement(
          array(
            Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', $class),
          ),
          $this->renderCheckedAsImage(). $message
        );
        $renderIfNotChecked = Tx_SavLibraryPlus_Utility_HtmlElements::htmlDivElement(
          array(
            Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', $class),
          ),
          $this->renderNotCheckedAsImage(). $message
        );

      } else {
        $renderIfChecked = Tx_SavLibraryPlus_Utility_HtmlElements::htmlDivElement(
          array(
            Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', $class),
          ),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlSpanElement(
            array(
              Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'checkboxSelected'),
            ),
            Tx_Extbase_Utility_Localization::translate('itemviewer.yesMult', 'sav_library_plus')
          ) . $message
        );
        $renderIfNotChecked = Tx_SavLibraryPlus_Utility_HtmlElements::htmlDivElement(
          array(
            Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', $class),
          ),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlSpanElement(
            array(
              Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'checkboxNotSelected'),
            ),
            Tx_Extbase_Utility_Localization::translate('itemviewer.noMult', 'sav_library_plus')
          ) . $message
        );
        
      	// Checks if donotdisplayifnotchecked is set
        if ($this->getItemConfiguration('donotdisplayifnotchecked')) {
        	$renderIfNotChecked = '';
				}        
      }
      $htmlArray[] = ($checked ? $renderIfChecked : $renderIfNotChecked);     
    }
    return $this->arrayToHTML($htmlArray);
  }

}
?>
