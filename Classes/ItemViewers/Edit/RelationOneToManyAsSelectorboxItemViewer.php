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
 * Edit RelationOneToManyAsSelectorbox item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_ItemViewers_Edit_RelationOneToManyAsSelectorboxItemViewer extends Tx_SavLibraryPlus_ItemViewers_Edit_AbstractItemViewer {

  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {

    $htmlArray = array();         

    // Gets the label
    $labelSelect = $this->getItemConfiguration('labelselect');
    if (empty($labelSelect) === false) {
    	// Checks if this label comes from an aliasSelect attribute
    	$aliasSelect = $this->getItemConfiguration('aliasselect');
    	if (preg_match('/(?:AS|as) ' . $labelSelect . '/', $aliasSelect)) {
    		// Uses the alias
    		$label = $labelSelect;
    		$labelSelect = '';
    	} else {
    		// Builds a full field name
    		$label = $this->getItemConfiguration('foreign_table') . '.' . $labelSelect;	
    		$labelSelect = ',' . $label;	
    	}
    } else {
    	// Gets the label from the TCA
    	$label =  $this->getItemConfiguration('foreign_table') . '.' . Tx_SavLibraryPlus_Managers_TcaConfigurationManager::getTcaCtrlField($this->getItemConfiguration('foreign_table'), 'label');
			$labelSelect = ',' . $label;
    }    
   
		// Sets the SELECT Clause
		$this->itemConfiguration['selectclause'] = $this->getItemConfiguration('foreign_table') . '.uid' . $labelSelect;
		
    // Builds the querier
    $querierClassName = 'Tx_SavLibraryPlus_Queriers_ForeignTableSelectQuerier';
    $querier = t3lib_div::makeInstance($querierClassName);
    $querier->injectController($this->getController());
    $querier->buildQueryConfigurationForForeignTable($this->itemConfiguration);
    $querier->injectQueryConfiguration();
    $querier->processQuery();
    
    // Gets the rows
    $rows = $querier->getRows();

    // Initializes the option element array
    $htmlOptionArray = array();
		$htmlOptionArray[] = '';

    // Adds the empty item option if any
    $items = $this->getItemConfiguration('items');
		if (isset($items[0]) || $this->getItemConfiguration('emptyitem')) {
			// Adds the Option element
			$htmlOptionArray[] = Tx_SavLibraryPlus_Utility_HtmlElements::htmlOptionElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', '0'),
        ),
        ''
      );
    }
    
    // Adds the option elements
    foreach ($rows as $rowKey => $row) {
			// Sets the rowId for the localization and field tags    	
      $querier->setCurrentRowId($rowKey);     
			// Adds the Option element
			$option = $row[$label];
			$option = $querier->parseLocalizationTags($option);
			$option = $querier->parseFieldTags($option);    	
			// Sets the selected attribute
			$value = $this->getItemConfiguration('value');
      $selected = ($row['uid'] == $value || (empty($value) && $row['uid'] == $this->getItemConfiguration('default')) ? 'selected' : ''); 
			// Adds the Option element
			$htmlOptionArray[] = Tx_SavLibraryPlus_Utility_HtmlElements::htmlOptionElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'item' . $row['uid']),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttributeIfNotNull('selected', $selected),
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', $row['uid']),
        ),
        stripslashes($option)
      );
    }

    // Adds the select element
		$htmlArray[] = Tx_SavLibraryPlus_Utility_HtmlElements::htmlSelectElement(
      array(
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $this->getItemConfiguration('itemName')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('size', $this->getItemConfiguration('size')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('onchange', 'document.changed=1;'),
      ),
      $this->arrayToHTML($htmlOptionArray)
    );

    return $this->arrayToHTML($htmlArray);
  }
  
}
?>
