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
 * Edit String item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_ItemViewers_Edit_StringItemViewer extends Tx_SavLibraryPlus_ItemViewers_Edit_AbstractItemViewer {

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
    
		if ($this->getItemConfiguration('default')) {
      $ondblclick = 'this.value=\'' .
        (
          !$this->getItemConfiguration('value') ?
          stripslashes($this->getItemConfiguration('default')) :
          stripslashes($value)
        ) .
        '\';';
    } else {
		  $ondblclick = '';
    }

    // Checks if the string is a password
		$evalAttributes = explode(',', $this->getItemConfiguration('eval'));
    if (in_array('password', $evalAttributes) === TRUE) {
      // Adds the input password element
      $content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputPasswordElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', stripslashes($value)),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('size', $this->getItemConfiguration('size')),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('onchange', 'document.changed=1;'),
        )
      );
    } else {
      // Adds the Input text element
      $content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputTextElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', stripslashes($value)),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('size', $this->getItemConfiguration('size')),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('onchange', 'document.changed=1;'),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttributeIfNotNull('ondblclick', $ondblclick),
        )
      );
    }

    return $content;
  }
}
?>
