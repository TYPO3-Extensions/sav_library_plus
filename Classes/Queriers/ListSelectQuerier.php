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
 * Default List Select Querier.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
class Tx_SavLibraryPlus_Queriers_ListSelectQuerier extends Tx_SavLibraryPlus_Queriers_AbstractQuerier {


  /**
   * Processes the total rows count query
   *
   * @param none
   *
   * @return none
   */  
  public function processTotalRowsCountQuery() {
    
    // Select the item count
		$this->resource = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			/* SELECT   */	'count(' . ($this->buildGroupByClause() ? 'DISTINCT ' . $this->buildGroupByClause() : '*') . ') as itemCount',
			/* FROM     */	$this->buildFromClause(),
 			/* WHERE    */	$this->buildWhereClause()
		);

    // Gets the row and the item count
    $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->resource);
	  $this->setTotalRowsCount($row['itemCount']);
  }
  
	/**
   * Executes the query
   *
   * @param none
   *
   * @return none
   */
  protected function executeQuery() {
   
    // Sets the rows count
    $this->processTotalRowsCountQuery();
		
    // Executes the select query
		$this->resource = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			/* SELECT   */	$this->buildSelectClause(),
			/* FROM     */	$this->buildFromClause(),
 			/* WHERE    */	$this->buildWhereClause(),
			/* GROUP BY */	$this->buildGroupByClause(),
			/* ORDER BY */  $this->buildOrderByClause(),
			/* LIMIT    */  $this->buildLimitClause()
		);

    // Sets the rows from the query
    $this->setRows();

    return;
  }

   /**
   * Builds the SELECT Clause.
   *
   * @param none
   *
   * @return string
   */ 
  protected function buildSelectClause() {
    $selectClause = '*';
    $aliases = $this->queryConfigurationManager->getAliases();
    $selectClause .= $this->replaceTableNames($aliases ? ', ' . $aliases : '');

		// Checks if a field name alias comes from the filter
		$selectedFilterKey = Tx_SavLibraryPlus_Managers_SessionManager::getSelectedFilterKey();
		if (empty($selectedFilterKey) === false) {
		  $fieldName = Tx_SavLibraryPlus_Managers_SessionManager::getFilterField($selectedFilterKey, 'fieldName');    
			$selectClause .= (empty($fieldName)=== false ? ', ' . $fieldName . ' as fieldname' : '');
		}
		return $selectClause;
  }

   /**
   * Builds the WHERE BY Clause.
   *
   * @param none
   *
   * @return string
   */ 
  protected function buildWhereClause() {

  	// Gets the extension configuration manager
  	$extensionConfigurationManager = $this->getController()->getExtensionConfigurationManager();
			
  	// Gets the Default WHERE clause from the query configuration manager
    $whereClause = $this->queryConfigurationManager->getWhereClause();
   
    // Adds the WHERE clause coming from the selected filter if any
		$selectedFilterKey = Tx_SavLibraryPlus_Managers_SessionManager::getSelectedFilterKey();

		if (empty($selectedFilterKey) === false) {
		  $additionalWhereClause = Tx_SavLibraryPlus_Managers_SessionManager::getFilterField($selectedFilterKey, 'addWhere');
		  $searchRequestFromFilter = Tx_SavLibraryPlus_Managers_SessionManager::getFilterField($selectedFilterKey, 'search');
		  if (empty($searchRequestFromFilter) === false) {
		  	// The WHERE clause coming from the filter replaces the default WHERE Clause
		  	$whereClause = (empty($additionalWhereClause) ? '0' : $additionalWhereClause);
		  } else {
		  	// The WHERE clause coming from the filter is added to the default WHERE Clause
		  	$whereClause .= ' AND ' . (empty($additionalWhereClause) ? '0' : $additionalWhereClause); 
		  } 
		}	else {
			// Sets the WHERE clause to 0 if the rows should not be searched
			$showAllIfNoFilter = $extensionConfigurationManager->getShowAllIfNoFilter();
			if (empty($showAllIfNoFilter)) {
				return '0';
			}			
		}	

    // Adds the enable fields conditions for the main table
    $mainTable = $this->queryConfigurationManager->getMainTable();
    $whereClause .= $extensionConfigurationManager->getExtensionContentObject()->enableFields($mainTable);
    
		// Adds the allowed pages condition
    $whereClause .= $this->getAllowedPages($mainTable);		
		
		// Adds the permanent filter if any
		$permanentFilter = $extensionConfigurationManager->getPermanentFilter();
		if (empty($permanentFilter) === false) {
			$whereClause .= ' AND '. $permanentFilter; 
		}
		
		// Processes WHERE clause tags
		$whereClause = $this->processWhereClauseTags($whereClause);

    return $whereClause;
  }
  
   /**
   * Builds the LIMIT BY Clause.
   *
   * @param none
   *
   * @return string
   */ 
  protected function buildLimitClause() {
    $maxItems = $this->getController()->getExtensionConfigurationManager()->getMaxItems();
		return ($maxItems ?	($maxItems *	Tx_SavLibraryPlus_Managers_UriManager::getPage()) . ',' . ($maxItems) : '');
  }
  
}
?>
