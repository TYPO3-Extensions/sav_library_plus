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
 * Default DateTime item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_ItemViewers_Edit_DateTimeItemViewer extends Tx_SavLibraryPlus_ItemViewers_Edit_AbstractItemViewer {

  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {
  
    $datePicker = t3lib_div::makeInstance('Tx_SavLibraryPlus_DatePicker_DatePicker');
    $datePicker->setAdditionalHeader();
  
    $htmlArray = array();

    // Sets the format
    $format = ($this->getItemConfiguration('format') ? $this->getItemConfiguration('format') : $this->getController()->getDefaultDateTimeFormat());

    // Sets the value
    $value = (
      $this->getItemConfiguration('value') ?
      strftime($format, $this->getItemConfiguration('value')) :
      ($this->getItemConfiguration('nodefault') ? '' : strftime($format))
    );

    $htmlArray[] = Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputTextElement(
      array(
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('id', 'input_' . strtr($this->getItemConfiguration('itemName'), '[]', '__')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttributeIfNotNull('class', $this->getItemConfiguration('classhtmltag')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttributeIfNotNull('style', $this->getItemConfiguration('stylehtmltag')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', $value),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('onchange', 'document.changed=1;'),
      )
    );

    // Gets the icon path
    $iconRootPath = Tx_SavLibraryPlus_Managers_LibraryConfigurationManager::getIconRootPath('calendar.gif');
    $iconPath = $iconRootPath . 'calendar.gif';

    $htmlArray[] = $datePicker->buildDatePickerSetup(
      array(
        'id' => strtr($this->getItemConfiguration('itemName'), '[]', '__'),
        'format' => $format,
        'showsTime' => true,
        'iconPath' => $iconPath,
      )
    );

    return $this->arrayToHTML($htmlArray);
  }

}
?>
