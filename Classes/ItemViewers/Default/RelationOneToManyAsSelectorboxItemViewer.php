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
 * Default RelationOneToManyAsSelectorbox item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_ItemViewers_Default_RelationOneToManyAsSelectorboxItemViewer extends Tx_SavLibraryPlus_ItemViewers_Default_AbstractItemViewer {

  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {

    // Builds the querier
    $querierClassName = 'Tx_SavLibraryPlus_Queriers_ForeignTableSelectQuerier';
    $querier = t3lib_div::makeInstance($querierClassName);
    $querier->injectController($this->getController());
    $querier->buildQueryConfigurationForOneToManyRelation($this->itemConfiguration);
    $querier->injectQueryConfiguration();
    $querier->processQuery();
    
    // Gets the rows
    $rows = $querier->getRows();

      // Gets the label
    $labelSelect = $this->getItemConfiguration('labelselect');
    if (empty($labelSelect) === false) {
    	if ($querier->fieldExistsInCurrentRow($labelSelect)) {
    		// The attribute is an alias
    		$label = $labelSelect;
    	} else {
    		// The attribute is a field, adds the foreign table
    		$label = $this->getItemConfiguration('foreign_table') . '.' . $this->getItemConfiguration('labelselect');
    	}
    } else {
    	// Gets the label from the TCA
    	$label =  $this->getItemConfiguration('foreign_table') . '.' . Tx_SavLibraryPlus_Managers_TcaConfigurationManager::getTcaCtrlField($this->getItemConfiguration('foreign_table'), 'label');
		}

    // Gets the selected element
		$content = stripslashes($rows[0][$label]);
		$content = $querier->parseLocalizationTags($content);
		$content = $querier->parseFieldTags($content);       

    return $content;
  }
  
}
?>
