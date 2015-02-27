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
 * Edit Checkboxes item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class CheckboxesItemViewer extends AbstractItemViewer {

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

    $value = $this->getItemConfiguration('value');
    $items = $this->getItemConfiguration('items');
    foreach ($items as $itemKey => $item) {
      $checked = (($value & 0x01 || $item[1] == 1 ) ? 'checked' : '');
      $value = $value >> 1;

      // Adds the hidden input element
      $htmlItem = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlInputHiddenElement(
        array(
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName'). '[' . $itemKey . ']'),
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('value', '0'),
        )
      );

      // Adds the checkbox input element
      $htmlItem .= \SAV\SavLibraryPlus\Utility\HtmlElements::htmlInputCheckBoxElement(
        array(
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName'). '[' . $itemKey . ']'),
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('value', '1'),
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttributeIfNotNull('checked', $checked),
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('onchange', 'document.changed=1;'),
        )
      );
    
      // Adds the span element
      $htmlItem .= \SAV\SavLibraryPlus\Utility\HtmlElements::htmlSpanElement(
        array(
        ),
        stripslashes(\SAV\SavLibraryPlus\Controller\FlashMessages::translate($item[0]))
      );

      // Sets the class for the item
      $class = 'checkbox item' . $itemKey;

      // Checks if the columns count is reached
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
      $htmlArray[] = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlDivElement(
        array(
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('class', $class),
          $this->getItemConfiguration('addattributes'),
        ),
        $htmlItem
      );

    }
    return $this->arrayToHTML($htmlArray);
  }

}
?>
