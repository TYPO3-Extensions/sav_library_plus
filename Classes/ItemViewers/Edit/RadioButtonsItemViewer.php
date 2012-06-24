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
 * Edit Radio buttons item Viewer.
 * 
 *
 * @package SavLibraryPlus
 * @version $ID:$
 */
class Tx_SavLibraryPlus_ItemViewers_Edit_RadioButtonsItemViewer extends Tx_SavLibraryPlus_ItemViewers_Edit_AbstractItemViewer {

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

    // Adds the option elements
    $items = $this->getItemConfiguration('items');
    $value = $this->getItemConfiguration('value');
    foreach ($items as $itemKey => $item) {
      $checked = ($item[1] == $value ? 'selected' : '');


      // Adds the radio input element
      $htmlItem = Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputRadioElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', $item[1]),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttributeIfNotNull('checked', $checked),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('onchange', 'document.changed=1;'),
        )
      );
      
      // Adds the span element
      $htmlItem .= Tx_SavLibraryPlus_Utility_HtmlElements::htmlSpanElement(
        array(
        ),
        stripslashes(Tx_Extbase_Utility_Localization::translate($item[0]))
      );

      // Sets the class for the item
      $class = 'radioButton item' . $itemKey;
        
      // Checks if the columns count is reached

      if ($counter == $columnsCount) {
        // Additional class
        $class .= ' clearLeft';
        // Resets the counter
        $counter = 0;
      }
      $counter++;
      
      // Adds the Div element
      $htmlArray[] = Tx_SavLibraryPlus_Utility_HtmlElements::htmlDivElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', $class),
          $this->getItemConfiguration('addattributes'),
        ),
        $htmlItem
      );

    }

    return $this->arrayToHTML($htmlArray);
  }

}
?>
