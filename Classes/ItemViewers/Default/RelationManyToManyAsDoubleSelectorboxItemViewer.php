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
   * The Foreign Table Select Querier
   *
   * @var Tx_SavLibraryPlus_Queriers_ForeignTableSelectQuerier
   */
  protected $foreignTableSelectQuerier; 	
	
  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {

    if ($this->getItemConfiguration('MM')) {
      $this->setForeignTableSelectQuerier('buildQueryConfigurationForTrueManyToManyRelation');
    } else {
      $this->setForeignTableSelectQuerier('buildQueryConfigurationForCommaListManyToManyRelation');
    }
    return $this->renderDoubleSelectorbox();
  }

  /**
   * Sets the Foreign Table Select Querier.
   *
	 * @param $getQuerierMethod string The method name to get the querier
   *
   * @return string
   */
  protected function setForeignTableSelectQuerier($buildQueryConfigurationMethod) {

    $querierClassName = 'Tx_SavLibraryPlus_Queriers_ForeignTableSelectQuerier';
    $this->foreignTableSelectQuerier = t3lib_div::makeInstance($querierClassName);
    $this->foreignTableSelectQuerier->injectController($this->getController());
    
    $this->itemConfiguration['uidLocal'] = $this->itemConfiguration['uid'];
    $this->foreignTableSelectQuerier->$buildQueryConfigurationMethod($this->itemConfiguration);
    $this->foreignTableSelectQuerier->injectQueryConfiguration();
  }  

  /**
   * Renders the double selector box content.
   *
   * @param none
   *
   * @return string
   */
  protected function renderDoubleSelectorbox() {

    $htmlArray = array();
    
    // Gets the rows
    $this->foreignTableSelectQuerier->processQuery();
    $rows = $this->foreignTableSelectQuerier->getRows();

    // Gets the label for the foreign_table
		$label = $this->getItemConfiguration('labelselect');
		if (!empty($label)) {
			// Checks if it is an alias
			if (!$this->foreignTableSelectQuerier->fieldExistsInCurrentRow($label)) {
				$label = $this->getItemConfiguration('foreign_table') . '.' . $label;
			}
		} else {
			$label = $this->getItemConfiguration('foreign_table') . '.' . Tx_SavLibraryPlus_Managers_TcaConfigurationManager::getTcaCtrlField($this->getItemConfiguration('foreign_table'), 'label');	
		}

    // Processes the rows
    $maxCount = count($rows) - 1;
    foreach ($rows as $rowKey => $row) {
    	$content = $row[$label];
			// Applies the function if any and allowed
    	if ($this->getItemConfiguration('func') && $this->getItemConfiguration('applyfunctorecords')) {
    	  // Injects the special markers
    	  $specialFields = str_replace(' ', '', $this->getItemConfiguration('specialfields'));
    	  if (!empty($specialFields)) {
    	  	$specialFieldsArray = explode(',', $specialFields);
    			foreach($row as $fieldKey => $field) {
    				if (in_array($fieldKey, $specialFieldsArray)) {
    					$this->getController()->getQuerier()->injectAdditionalMarkers(array('###special[' . $fieldKey . ']###' => $field));
    				}
    			}
    		}
    		$content = $this->processFuncAttribute($content);
    	}
    	$content .= ($rowKey < $maxCount  ? $this->getItemConfiguration('separator') : '');
    	
      $htmlArray[] = Tx_SavLibraryPlus_Utility_HtmlElements::htmlDivElement(
        array(
          Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'doubleSelectorbox item' . $row['uid']),
        ),
        $content
      );
    }

    return $this->arrayToHTML($htmlArray);
  }

}
?>
