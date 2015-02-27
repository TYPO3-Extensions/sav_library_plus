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
 * Edit Selectorbox item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class SelectorboxItemViewer extends AbstractItemViewer {

  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {
 
    $htmlArray = array();

    // Initializes the option element array
    $htmlOptionArray = array();
		$htmlOptionArray[] = '';

    // Adds the empty item option if any
		if ($this->getItemConfiguration('emptyitem')) {
			// Adds the Option element
			$htmlOptionArray[] = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlOptionElement(
        array(
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('value', '0'),
        ),
        ''
      );
    }
    
    // Adds the option elements
    $items = $this->getItemConfiguration('items');
    $value = $this->getItemConfiguration('value');
    foreach ($items as $itemKey => $item) {
      $selected = ($item[1] == $value ? 'selected' : '');
			// Adds the Option element
			$htmlOptionArray[] = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlOptionElement(
        array(
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('class', 'item' . $itemKey),
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttributeIfNotNull('selected', $selected),
          \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('value', $item[1]),
        ),
        stripslashes(\SAV\SavLibraryPlus\Controller\FlashMessages::translate($item[0]))
      );
    }

    // Adds the select element
		$htmlArray[] = \SAV\SavLibraryPlus\Utility\HtmlElements::htmlSelectElement(
      array(
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('size', $this->getItemConfiguration('size')),
        \SAV\SavLibraryPlus\Utility\HtmlElements::htmlAddAttribute('onchange', 'document.changed=1;'),
      ),
      $this->arrayToHTML($htmlOptionArray)
    );

    return $this->arrayToHTML($htmlArray);
  }

}
?>
