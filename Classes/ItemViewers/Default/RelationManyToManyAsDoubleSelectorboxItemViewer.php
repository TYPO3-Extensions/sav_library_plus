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
 * Default RelationManyToManyAsDoubleSelectorbox item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_ItemViewers_Default_RelationManyToManyAsDoubleSelectorboxItemViewer extends Tx_SavLibraryPlus_ItemViewers_Default_AbstractItemViewer {

  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {

    if ($this->getItemConfiguration('MM')) {
      return $this->renderDoubleSelectorbox('buildQueryConfigurationForTrueManyToManyRelation');
    } else {
      return $this->renderDoubleSelectorbox('buildQueryConfigurationForCommaListManyToManyRelation');
    }
  }

  /**
   * Renders the double selector box content.
   *
   * @param string $buildQueryConfigurationMethod The method to build the query configuration
   *
   * @return string
   */
  protected function renderDoubleSelectorbox($buildQueryConfigurationMethod) {

    $htmlArray = array();

    // Creates the querier
    $querierClassName = 'Tx_SavLibraryPlus_Queriers_ForeignTableSelectQuerier';
    $querier = t3lib_div::makeInstance($querierClassName);
    $querier->injectController($this->getController());
    $this->itemConfiguration['uidLocal'] = $this->itemConfiguration['uid'];
    $querier->$buildQueryConfigurationMethod($this->itemConfiguration);
    $querier->injectQueryConfiguration();
    
    // Gets the rows
    $querier->processQuery();
    $rows = $querier->getRows();

		// Gets the label for the foreign_table
		$label = $this->getItemConfiguration('foreign_table') . '.' . (
      $this->getItemConfiguration('labelselect') ?
      $this->getItemConfiguration('labelselect') :
      Tx_SavLibraryPlus_Managers_TcaConfigurationManager::getTcaCtrlField($this->getItemConfiguration('foreign_table'), 'label')
    );

    // Processes the rows
    $maxCount = count($rows) - 1;
    foreach ($rows as $rowKey => $row) {
      $htmlArray[] = Tx_SavLibraryPlus_Utility_HtmlElements::htmlDivElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'item' . $row['uid']),
        ),
        $row[$label] . ($rowKey < $maxCount  ? $this->getItemConfiguration('separator') : '')
      );
    }

    return $this->arrayToHTML($htmlArray);
  }

}
?>
