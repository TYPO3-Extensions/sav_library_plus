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
 * Edit Textarea item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_ItemViewers_Edit_TextItemViewer extends Tx_SavLibraryPlus_ItemViewers_Edit_AbstractItemViewer {

  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {
  
    // Adds the textarea element
    $content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlTextareaElement(
      array(
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('cols', $this->getItemConfiguration('cols')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('rows', $this->getItemConfiguration('rows')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('onchange', 'document.changed=1;'),
      ),
      $this->getItemConfiguration('value')
    );

    return $content;
  }
}
?>
