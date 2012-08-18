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
 * Abstract Querier.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
abstract class Tx_SavLibraryPlus_Queriers_AbstractQuerier {

  /**
   * The controller
   *
   * @var Tx_SavLibraryPlus_Controller_Controller
   */
  private $controller;
  
  /**
   * The query configuration manager
   *
   * @var Tx_SavLibraryPlus_Managers_QueryConfigurationManager
   */
  protected $queryConfigurationManager;
  
  /**
   * The query resource
   *
   * @var resource
   */
  protected $resource;

  /**
   * The rows
   *
   * @var array
   */
  protected $rows;

  /**
   * The total rows count, i.e. without the limit clause
   *
   * @var array
   */
  private $totalRowsCount = 1;
  
  /**
   * The curent row id
   *
   * @var integer
   */
  protected $currentRowId = 0;
  
  /**
   * The query parameters
   *
   * @var array
   */
  protected $queryParameters = array();
  
  /**
   * The table aliases
   *
   * @var array
   */
  protected $tableAliases = array();

  /**
   * The query configuration
   *
   * @var array
   */
  protected $queryConfiguration = NULL;
  
  /**
   * The update querier
   *
   * @var Tx_SavLibraryPlus_Queriers_UpdateQuerier
   */
  protected $updateQuerier = NULL;

  /**
   * The pages to clear
   *
   * @var array
   */
  protected $pageIdentifiersToClearInCache = array();
  
	/**
	 * Injects the controller
	 *
	 * @param Tx_SavLibraryPlus_Controller_AbstractController $controller The controller
	 *
	 * @return  none
	 */
  public function injectController($controller) {
    $this->controller = $controller;
  }
  
	/**
	 * Injects the query configuration
	 *
	 * @param none
	 *
	 * @return  none
	 */
  public function injectQueryConfiguration() {

    $this->queryConfigurationManager = t3lib_div::makeInstance('Tx_SavLibraryPlus_Managers_QueryConfigurationManager');
    if ($this->queryConfiguration === NULL) {
      // Sets the query configuration manager
      $libraryConfigurationManager = $this->getController()->getLibraryConfigurationManager();
      $this->queryConfiguration = $libraryConfigurationManager->getQueryConfiguration();
    }

    // Injects the query configuration
    $this->queryConfigurationManager->injectQueryConfiguration($this->queryConfiguration);
  }

	/**
	 * Injects the update querier
	 *
	 * @param Tx_SavLibraryPlus_Queriers_UpdateQuerier $updateQuerier
	 *
	 * @return  none
	 */
  public function injectUpdateQuerier($updateQuerier) {
    $this->updateQuerier = $updateQuerier;
  }  
  
  /**
   * Processes the query
   *
   * @param none
   *
   * @return none
   */  	
  public function processQuery() {
  	
  	if ($this->executeQuery() === false) {
  		return false;
  	}
  	// Clear pages cache if needed
  	$this->clearPagesCache();
  	return true;
  }
  
  /**
   * Executes the query
   *
   * @param none
   *
   * @return none
   */  
  protected function executeQuery() {
  }	
  
  /**
   * Clears the pages cache if needed
   *
   * @param none
   *
   * @return none
   */  
  protected function clearPagesCache() {
  	// if the plugin type is not USER, the cache has not to be cleared
		if (Tx_SavLibraryPlus_Managers_ExtensionConfigurationManager::isUserPlugin() === false) {
			return;
		}	
		
		// If the page identifiers list is empty, just returns
		if (empty($this->pageIdentifiersToClearInCache)) {
			return;
		}
		
		// Deletes the pages in the cache
		$GLOBALS['TYPO3_DB']->exec_DELETEquery(
			'cache_pages',
			'page_id IN (' . implode(',', $this->pageIdentifiersToClearInCache) . ')'
		);

  }	
  
 	/**
	 * Sets the current row identifier
	 *
	 * @param integer $rowId The row identifier
	 *
	 * @return none
	 */
  public function setCurrentRowId($rowId) {
    $this->currentRowId = $rowId;
  }
  
 	/**
	 * Gets the current row identifier
	 *
	 * @param none
	 *
	 * @return integer
	 */
  public function getCurrentRowId() {
    return $this->currentRowId;
  }
  
	/**
	 * Gets the rows
	 *
	 * @param none
	 *
	 * @return array The rows
	 */
  public function getRows() {
    return $this->rows;
  }
  
	/**
	 * Adds an empty row
	 *
	 * @param none
	 *
	 * @return none
	 */
  public function addEmptyRow() {
    $this->rows[0] = array();
  }
  
	/**
	 * Gets the rows count
	 *
	 * @param none
	 *
	 * @return integer The rows count
	 */
  public function getRowsCount() {
    return count($this->rows);
  }
  
	/**
	 * Gets the total rows count, i.e. without the limit clause
	 *
	 * @param none
	 *
	 * @return integer The rows count
	 */
  public function getTotalRowsCount() {
    return $this->totalRowsCount;
  }
  
	/**
	 * Sets the total rows count
	 *
	 * @param integer $totalRowsCount The total rows count
	 *
	 * @return none
	 */
  public function setTotalRowsCount($totalRowsCount) {
    $this->totalRowsCount = $totalRowsCount;
  }

  /**
	 * Gets the value of a field in the current row
	 *
	 * @param string $fieldName The field name
	 *
	 * @return mixed
	 */  
  public function getFieldValueFromCurrentRow($fieldName) {
    return $this->rows[$this->currentRowId][$fieldName];
  }

   /**
	 * Checks if a field exists in the current row
	 *
	 * @param string $fieldName The field name
	 *
	 * @return boolean
	 */
  public function fieldExistsInCurrentRow($fieldName) {
    return array_key_exists($fieldName, $this->rows[$this->currentRowId]);
  }

  /**
	 * Builds the full field name
	 *
	 * @param string $fieldName The field name
	 *
	 * @return string
	 */
  public function buildFullFieldName($fieldName) {
  	$fieldNameParts = explode('.', $fieldName);
  	if(count($fieldNameParts) == 1) {
  		// The main table is assumed by default
  		$fieldName = $this->getQueryConfigurationManager()->getMainTable() . '.' . $fieldName;
  	}
  	return $fieldName;
  }
  
	/**
	 * Gets the controller
	 *
	 * @param none
	 *
	 * @return Tx_SavLibraryPlus_Controller_AbstractController
	 */
  public function getController() {
    return $this->controller;
  }

	/**
	 * Gets the update querier
	 *
	 * @param none
	 *
	 * @return Tx_SavLibraryPlus_Queriers_UpdateQuerier 
	 */
  public function getUpdateQuerier() {
    return $this->updateQuerier;
  }  
  
 	/**
	 * Checks if the was at leat one error during the update.
	 *
	 * @param none
	 *
	 * @return boolean
	 */		
	public function errorDuringUpdate() {
		$updateQuerier = $this->getUpdateQuerier();
		if ($updateQuerier !== NULL) {
			return $updateQuerier->errorDuringUpdate();
		}	else {
			return false;
		}
	}	 
	
	/**
	 * Gets the value content from the POST variable after processing by the update querier.
	 * It is called when an error occurs in order to retrieve the user's inputs.
	 *
	 * @param string $fieldName
	 *
	 * @return mixed
	 */	
	public function getFieldValueFromProcessedPostVariables($fieldName) {
		$uid = $this->getFieldValueFromCurrentRow(preg_replace('/\.\w+$/', '.uid', $fieldName));
    $processedPostVariable = $this->getUpdateQuerier()->getProcessedPostVariable($fieldName, $uid);
    $value = $processedPostVariable['value'];
		return $value;
	}	

	/**
	 * Gets the error code from the POST variable after processing by the update querier.
	 * It is called when an error occurs in order to retrieve the user's inputs.
	 *
	 * @param string $fieldName
	 *
	 * @return integer
	 */	
	public function getFieldErrorCodeFromProcessedPostVariables($fieldName) {
		$uid = $this->getFieldValueFromCurrentRow(preg_replace('/\.\w+$/', '.uid', $fieldName));
    $processedPostVariable = $this->getUpdateQuerier()->getProcessedPostVariable($fieldName, $uid);
    $errorCode = $processedPostVariable['errorCode'];
		return $errorCode;
	}		
	
	/**
	 * Gets the query configuration manager
	 *
	 * @return Tx_SavLibraryPlus_Managers_QueryConfigurationManager
	 */
  public function getQueryConfigurationManager() {
    return $this->queryConfigurationManager;
  }
  
  /**
   * Gets a query parameter.
   *
   * @param string $parameterName The parameter name
   *
   * @return string
   */
  protected function getQueryParameter($parameterName) {
    return $this->queryParameters[$parameterName];
  }

	/**
	 * Builds the SELECT clause
	 *
	 * @return string
	 */
  protected function buildSelectClause() {
    $selectClause = '*';
    $aliases = $this->queryConfigurationManager->getAliases();
    $selectClause .= $this->replaceTableNames($aliases ? ', ' . $aliases : '');
    return $selectClause;
  }

	/**
	 * Builds the FROM clause
	 *
	 * @return string
	 */
  protected function buildFromClause() {
    return $this->buildTableReferences();
  }
  
	/**
	 * Builds the WHERE clause
	 *
	 * @return string
	 */
  protected function buildWhereClause() {
  	$whereClause = $this->queryConfigurationManager->getWhereClause();
  	$whereClause = $this->parseLocalizationTags($whereClause);
  	$whereClause = $this->parseFieldTags($whereClause);
    return $whereClause;
  }
  
	/**
	 * Builds the GROUP BY clause
	 *
	 * @return string
	 */
  protected function buildGroupByClause() {
    return $this->queryConfigurationManager->getGroupByClause();
  }

	/**
	 * Builds the ORDER BY clause
	 *
	 * @return string
	 */
  protected function buildOrderByClause() {
    return $this->queryConfigurationManager->getOrderByClause();
  }

	/**
	 * Builds the LIMIT clause
	 *
	 * @return string
	 */
  protected function buildLimitClause() {
    return $this->queryConfigurationManager->getLimitClause();
  }

	/**
	 * Deletes a record in a MM table
	 *
	 * @param $tableName string Table name
	 * @param $uid integer uid of the record to delete
	 * @param $whereField string The where field - default uid_local
	 *
	 * @return none
	 */
  protected function deleteRecordsInRelationManyToMany($tableName, $uid, $whereField = 'uid_local') {

    $this->resource = $GLOBALS['TYPO3_DB']->exec_DELETEquery(
      /* TABLE   */	$tableName,
      /* WHERE   */	$tableName. '.' . $whereField . '=' . intval($uid)
    );
  }

	/**
	 * Inserts fields in a MM table
	 *
	 * @param $tableName string Table name
	 * @param $fields array Fields to insert
	 *
	 * @return none
	 */
  protected function insertFieldsInRelationManyToMany($tableName, $fields) {
    // Inserts the fields
  	$this->resource = $GLOBALS['TYPO3_DB']->exec_INSERTquery(
      /* TABLE   */	$tableName,
  		/* FIELDS  */	$fields
  	);
  }  
  
	/**
	 * Gets the row in a MM table
	 *
	 * @param $tableName string Table name
	 * @param $uidLocal integer uid of the record in the source table
	 * @param $uidInteger integer uid of the record in the foreign table
	 *
	 * @return none
	 */
  protected function getRowInRelationManyToMany($tableName, $uidLocal, $uidForeign) {

		$this->resource = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			/* SELECT   */	'*',
			/* FROM     */	$tableName,
 			/* WHERE    */	'uid_local = ' . $uidLocal . ' AND uid_foreign = ' . $uidForeign
		);
    $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->resource);
    return $row;
  }

	/**
	 * Gets the uid_foreign in a MM table
	 *
	 * @param $tableName string Table name
	 * @param $uidLocal integer
	 * @param $sorting integer
	 *
	 * @return integer
	 */
  protected function getUidForeignInRelationManyToMany($tableName, $uidLocal, $sorting) {

		$this->resource = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			/* SELECT   */	'uid_foreign',
			/* FROM     */	$tableName,
 			/* WHERE    */	'uid_local = ' . $uidLocal . ' AND sorting = ' . $sorting
		);
    $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->resource);
    return $row['uid_foreign'];
  }

	/**
	 * Gets the records count in a MM table
	 *
	 * @param $tableName string Table name
	 * @param $uidLocal integer 
	 *
	 * @return none
	 */
  protected function getRowsCountInRelationManyToMany($tableName, $uidLocal) {

		$this->resource = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			/* SELECT   */	'count(*) as recordsCount, max(sorting) as maxSorting',
			/* FROM     */	$tableName,
 			/* WHERE    */	'uid_local = ' . $uidLocal
		);
    $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->resource);
    
    // Reorders the sorting field if needed
    if ($row['recordsCount'] != $row['maxSorting']) {
      $this->reorderSortingInRelationManyToMany($tableName, $uidLocal);
    }
    return intval($row['recordsCount']);
  }

	/**
	 * Gets the sorting field in a MM table
	 *
	 * @param $tableName string Table name
	 * @param $uidLocal integer
	 *
	 * @return none
	 */
  protected function updateSortingInRelationManyToMany($tableName, $uidLocal, $uidForeign, $sorting) {

		$this->resource = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
			/* TABLE   */	$tableName,
 			/* WHERE   */	'uid_local=' . $uidLocal . ' AND uid_foreign=' . $uidForeign,
			/* FIELDS  */	array('sorting' => $sorting)
		);
  }

	/**
	 * Reorders the  sorting field in a MM table 
	 *
	 * @param $tableName string Table name
	 * @param $uid integer uid of the record to delete
	 *
	 * @return none
	 */
  protected function reorderSortingInRelationManyToMany($tableName, $uidLocal) {
    if (!empty($uidLocal)) {
      // Sets a counter variable
      $query = 'SET @counter=0';
      $this->resource = $GLOBALS['TYPO3_DB']->exec_PREPAREDquery($query, array());

      // Reorders the table
      $query = 'UPDATE '. $tableName . ' SET sorting=(@counter:=@counter+1) WHERE uid_local=' . $uidLocal . ' ORDER BY sorting';
      $this->resource = $GLOBALS['TYPO3_DB']->exec_PREPAREDquery($query, array());
    }
  }

	/**
	 * Sets the deleted field in a table
	 *
	 * @param $tableName string Table name
	 * @param $uid integer uid of the record to delete
	 *
	 * @return none
	 */
  protected function setDeletedField($tableName, $uid) {

		$this->resource = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
			/* TABLE   */	$tableName,
 			/* WHERE   */	$tableName . '.uid=' . intval($uid),
			/* FIELDS  */	array('deleted' => 1)
		);
		
		$this->addToPageIdentifiersToClearInCache($tableName, $uid);
  }

	/**
	 * Updates a record in a table
	 *
	 * @param $tableName string Table name
	 * @param $fields array Fields to update
	 * @param $uid integer uid of the record to update
	 *
	 * @return none
	 */
  protected function updateFields($tableName, $fields, $uid) {
    if ($GLOBALS['TCA'][$tableName]['ctrl']['tstamp']) {
      $fields = array_merge($fields,
        array($GLOBALS['TCA'][$tableName]['ctrl']['tstamp'] => time())
      );
    }

    $this->resource = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
      /* TABLE   */	$tableName,
      /* WHERE   */	$tableName.'.uid=' . intval($uid),
      /* FIELDS  */	$fields
    );
    
		$this->addToPageIdentifiersToClearInCache($tableName, $uid);
  }

	/**
	 * Inserts a record in a table
	 *
	 * @param $tableName string Table name
	 * @param $fields array Fields to update
	 *
	 * @return integer The uid of the inserted record
	 */
  protected function insertFields($tableName, $fields) { 
  	
    // Adds the controls
  	if ($GLOBALS['TCA'][$tableName]['ctrl']['cruser_id']) {
      $fields = array_merge($fields,
  		  array($GLOBALS['TCA'][$tableName]['ctrl']['cruser_id'] => $GLOBALS['TSFE']->fe_user->user['uid'])
      );
  	}
  	if ($GLOBALS['TCA'][$tableName]['ctrl']['crdate']) {
      $fields = array_merge($fields,
  		  array($GLOBALS['TCA'][$tableName]['ctrl']['crdate'] => time())
      );
    }
  	if ($GLOBALS['TCA'][$tableName]['ctrl']['tstamp']) {
      $fields = array_merge($fields,
  		  array($GLOBALS['TCA'][$tableName]['ctrl']['tstamp'] => time())
      );
    }
     
  	$this->resource = $GLOBALS['TYPO3_DB']->exec_INSERTquery(
      /* TABLE   */	$tableName,
  		/* FIELDS  */	$fields
  	);  

  	$uid = $GLOBALS['TYPO3_DB']->sql_insert_id($this->resource);
  	
		$this->addToPageIdentifiersToClearInCache($tableName, $uid);
  	 	
  	return $uid;
  }  
  
	/**
	 * Gets the records count in a table
	 *
	 * @param $tableName string Table name
	 *
	 * @return integer
	 */
  protected function getRowsCountInTable($tableName) {

		$this->resource = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			/* SELECT   */	'count(*) as recordsCount',
			/* FROM     */	$tableName,
 			/* WHERE    */	'1 ' . $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject()->enableFields($tableName)
		);
    $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->resource);

    return intval($row['recordsCount']);
  }

	/**
	 * Adds the pid to the page identifiers to clear in the cache if needed.
	 * If the record lies on a page, then we clear the cache of this page.
	 * If the record has no PID column, we clear the cache of the current page as best-effort.
	 *
	 * Much of this code is taken from Tx_Extbase_Persistence_Storage_Typo3DbBackend::clearPageCache .
	 *
	 * @param $tableName Table name of the record
	 * @param $uid UID of the record
	 * @return void
	 */
	protected function addToPageIdentifiersToClearInCache($tableName, $uid) {
		
		// if the plugin type is not USER, the cache has not to be clerared
		if(Tx_SavLibraryPlus_Managers_ExtensionConfigurationManager::isUserPlugin() === false) {
			return;
		}
		
		$pageIdsToClear = array();
		$storagePage = NULL;

		$columns = $GLOBALS['TYPO3_DB']->admin_get_fields($tableName);
		if (array_key_exists('pid', $columns)) {
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('pid', $tableName, 'uid=' . intval($uid));
			if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result))	{
				$storagePage = $row['pid'];
				$this->pageIdentifiersToClearInCache[] = intval($storagePage);
			}
		} elseif (isset($GLOBALS['TSFE'])) {
			// No PID column - we can do a best-effort to clear the cache of the current page if in FE
			$storagePage = $GLOBALS['TSFE']->id;
			$this->pageIdentifiersToClearInCache[] = intval($storagePage);
		}
		
		// Gets the storage page
		$storagePage = $this->getController()->getExtensionConfigurationManager()->getStoragePage();
		if (empty($storagePage) === false) {
			$this->pageIdentifiersToClearInCache[] = intval($storagePage);			
		}
	} 
  
	/**
	 * Builds the table references (from part) of the query
	 *
   * @param $query array (query array)
	 * @param $addTables string (additional tables)
 	 *
	 * @return string (the tables with their left join fields )
	 */
  public function buildTableReferences(&$query, $addTables = '') {

  	// Initializes the table aliases
    $this->tableAliases = array();
    
    // Loads the main table configuration
    $mainTable = $this->queryConfigurationManager->getMainTable();

    $this->addNewTableAlias($mainTable);
		t3lib_div::loadTCA($mainTable);
		$TCA = Tx_SavLibraryPlus_Managers_TcaConfigurationManager::getTcaColumns($mainTable);

    if (is_array($TCA)) {
      foreach ($TCA as $fieldKey => $field) {
        $TCA[$fieldKey]['mainTable'] = $mainTable;
        // Checks if there is a subform
        if ($field['config']['type'] == 'inline' && !$field['config']['norelation']) {
          $foreignTable = $field['config']['foreign_table'];
          t3lib_div::loadTCA($foreignTable);
          $fields = Tx_SavLibraryPlus_Managers_TcaConfigurationManager::getTcaColumns($foreignTable);
          if (is_array($fields)) {
            foreach ($fields as $fieldKey => $field) {
              $fields[$fieldKey]['mainTable'] = $foreignTable;
            }
            $TCA = array_merge($TCA, $fields);
          }
        }
      }
    } else {
			throw new Tx_SavLibraryPlus_Exception(Tx_SavLibraryPlus_Controller_FlashMessages::translate('fatal.incorrectTCA'));
    }
		t3lib_div::loadTCA('fe_users');
		

    // Adds the columns for existing tables.
    $externalTcaConfiguration = $this->getController()->getLibraryConfigurationManager()->getExternalTcaConfiguration();
    if (is_array($externalTcaConfiguration[$mainTable])) {
      $fields = $externalTcaConfiguration[$mainTable];
      foreach ($fields as $fieldKey => $field) {
        $fields[$fieldKey]['mainTable'] = $mainTable;
      }
      $TCA = array_merge($TCA, $fields);
    }
   
    // Intializes the table references
    $tableReferences = $mainTable;

    $tableArray = array();

    // Builds the reference
    foreach ($TCA as $fieldKey => $field) {
    
      // Gets the config part of the TCA array
      $configuration = $field['config'];

      if ($configuration['type'] == 'inline' && !$configuration['norelation']) {
        $this->addNewTableAlias($configuration['MM']);
        $this->addNewTableAlias($configuration['foreign_table']);
        $tableReferences .= ' LEFT JOIN ' . $this->getTableAliasDefinition($configuration['MM']) .
          ' ON (' . $configuration['MM'] . '.uid_local=' . $field['mainTable'] . '.uid) LEFT JOIN ' . $this->getTableAliasDefinition($configuration['foreign_table']) . ' ON (' . $configuration['MM'] . '.uid_foreign=' . $configuration['foreign_table'] . '.uid)';
      } elseif ($configuration['type'] == 'select') {
        if ($configuration['MM']) {
          // MM table
          $this->addNewTableAlias($configuration['MM']);
          $this->addNewTableAlias($configuration['foreign_table']);
          $tableReferences .= ' LEFT JOIN ' . $this->getTableAliasDefinition($configuration['MM']) .
            ' ON (' . $configuration['MM'] . '.uid_local=' . $field['mainTable'] . '.uid) LEFT JOIN ' . $this->getTableAliasDefinition($configuration['foreign_table']) . ' ON (' . $configuration['MM'] . '.uid_foreign=' . $configuration['foreign_table'] . '.uid)';
        } elseif ($configuration['foreign_table']) {
          $this->addNewTableAlias($configuration['foreign_table']);
          // Checks if there is a comma-separated MM relation
          if ($configuration['maxitems'] > 1) {
           $tableReferences .= ' LEFT JOIN ' . $this->getTableAliasDefinition($configuration['foreign_table']) .
            ' ON (FIND_IN_SET(' . $this->getTableAlias($configuration['foreign_table']) . '.uid, ' . $field['mainTable'] . '.' . $fieldKey . ')>0)';
          } else {
          $tableReferences .= ' LEFT JOIN ' . $this->getTableAliasDefinition($configuration['foreign_table']) .
            ' ON (' . $this->getTableAlias($configuration['foreign_table']) . '.uid=' . $field['mainTable'] . '.' . $fieldKey . ')';
          }

          // Checks if a link is defined
          $view = $this->extConfig['views'][$this->savlibrary->viewId];
          if (is_array($view) && $this->savlibrary->folderTab == Tx_SavLibraryPlus_Controller_AbstractController::cryptTag('0')) {
            reset($view);
            $folderTab = key($view);
          } else {
            $folderTab = $this->savlibrary->folderTab;
          }
          $extendLink = $view[$folderTab]['fields'][Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($mainTable . '.' . $field)]['config']['setextendlink'];
          if ($extendLink) {
            $alias2 = $this->buidAliasTable($extendLink);
            $tableReferences .= ' LEFT JOIN ' . $alias2['def'] .
              ' ON (' . $alias1['table'] . '.' . $extendLink . '=' . $alias2['table'] . '.uid)';
          }
        }
      }
    }

// TOD0
    // Checks for duplicate table names with addTables
    $addTablesArray = array();
    $temp = explode(',', $addTables);
    foreach ($temp as $key => $table) {
      if($table && !in_array($table, $addTablesArray) && !array_key_exists($table, $this->refTable)) {
        $addTablesArray[] = $table;
      }
    }
    $addTables = implode(',', $addTablesArray);

    // Adds the foreign table
    // Checks that the 'tableForeign' start either by LEFT JOIN, INNER JOIN or RIGHT JOIN or a comma
    $foreignTables = $this->getQueryConfigurationManager()-> getForeignTables();
    if (empty($foreignTables) === false) {
      if (!preg_match('/^[\s]*(?i)(,|inner join|left join|right join)\s?([^ ]*)/', $foreignTables, $match)) {
				Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectQueryForeignTable');
      } else {
        if (!in_array(trim($match[2]), $addTablesArray) && !array_key_exists(trim($match[2]), $this->refTable)) {
          $tableForeign .= ' ' . $foreignTables;
        }
      }
    }
// End of TODO
    return $tableReferences . ($addTables ? ', ' . $addTables : '') . $tableForeign;
  }
  
  
	/**
	 * Builds the aliases for tables
	 *
   * @param $tableName string (table name)
 	 *
	 * @return array (result)
	 */
  protected function addNewTableAlias($tableName) {
    if (isset($this->tableAliases[$tableName])) {
      $this->tableAliases[$tableName] = $this->tableAliases[$tableName] + 1;
    } else {
      $this->tableAliases[$tableName] = 1;
    }
  }
  
  protected function getTableAliasDefinition($tableName) {
    if (isset($tableName) === false) {
die('pb');
    } elseif ($this->tableAliases[$tableName] == 1) {
      return $tableName;
    } else {
      return $tableName . ' AS ' . $tableName . '_' . $this->tableAliases[$tableName];
    }
  }
  
  protected function getTableAlias($tableName) {
    if (isset($tableName) === false) {
die('pb');
    } elseif ($this->tableAliases[$tableName] == 1) {
      return $tableName;
    } else {
      return $tableName . '_' . $this->tableAliases[$tableName];
    }
  }
   /***************************************************************
    *
    *   Utils
    *
   ***************************************************************/


	/**
	 * Gets allowed Pages from the starting point and the storage page
	 *
   * @param string $tableName The table name
 	 *
	 * @return string
	 */
  public function getAllowedPages($tableName) {
    if (empty($tableName)) {
      return '';
    } else {
      // Adds the starting point pages
      $extensionConfigurationManager = $this->getController()->getExtensionConfigurationManager();
      $contentObject = $extensionConfigurationManager->getExtensionContentObject();
      if ($contentObject->data['pages']) {
        $pageListArray = explode(',', $contentObject->data['pages']);
      } else {
        $pageListArray = array();
      }
      // Adds the storage page
      $storagePage = $extensionConfigurationManager->getStoragePage();
      if (empty($storagePage) === false) {
        $pageListArray[] = $storagePage;
      }

      $pageList = implode(',', $pageListArray);

   		return ($pageList ? ' AND ' . $tableName . '.pid IN (' . $pageList . ')' : '');
    }
  }

	/**
	 * Parses contant tags
	 *
	 * @param $value string (string to process)
	 *
	 * @return string ()
	 */
  public function parseConstantTags($value) {
    // Processes constants
    if (preg_match_all('/\$\$\$constant\[([^\]]+)\]\$\$\$/', $value, $matches)) {
      foreach ($matches[1] as $matchKey => $match) {
        if (defined($match)) {
          $value = str_replace($matches[0][$matchKey], constant($match), $value);
        }
      }
    }
    return $value;
  } 
  
	/**
	 * Parses localization tags
	 *
	 * @param $value string The string to process
	 * @param boolean $reportError If true report the error associated when the marker is not found
	 *
	 * @return string
	 */
  public function parseLocalizationTags($value, $reportError = true) {

    // Gets the extension key
    $extensionKey = $this->getController()->getExtensionConfigurationManager()->getExtensionKey();

    // Builds the localization prefix
    $localizationPrefix = 'LLL:EXT:' . $extensionKey . '/' . $this->getController()->getLibraryConfigurationManager()->getLanguagePath();
    
    // Processes labels associated with fields
    if (preg_match_all('/\$\$\$label\[([^\]]+)\]\$\$\$/', $value, $matches)) {

      foreach ($matches[1] as $matchKey => $match) {
        // Checks if the label is in locallang_db.xml, no default table is assumed
        // In that case the full name must be used, i.e. tableName.fieldName
        $label = $GLOBALS['TSFE']->sL($localizationPrefix . 'locallang_db.xml:' . $match);

        if (empty($label) === false) {
          $value = str_replace($matches[0][$matchKey], $label, $value);
        } else {
          // Checks if the label is in locallang_db.xml, the main table is assumed
          $mainTable = $this->getQueryConfigurationManager()->getMainTable();
          $label = $GLOBALS['TSFE']->sL($localizationPrefix . 'locallang_db.xml:' . $mainTable . '.' . $match);

          if (empty($label) === false) {
            // Found in locallang_db.xml file, replaces it
            $value = str_replace($matches[0][$matchKey], $label, $value);
          } elseif ($reportError === true) {
            Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.missingLabel', array($match));
          } else {
      			$value = str_replace($matches[0][$matchKey], $matches[1][$matchKey], $value);
      		}
        }
      }
    }

    // Checks if the label is in the locallang.xml file
    preg_match_all('/\$\$\$([^\$]+)\$\$\$/', $value, $matches);
    foreach ($matches[1] as $matchKey => $match) {
      $label = Tx_Extbase_Utility_Localization::translate($match, $extensionKey);
      if (!empty($label)) {
        // Found in locallang.xml file, replaces it
        $value = str_replace($matches[0][$matchKey], $label, $value);
      } elseif ($reportError === true) {
        Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.missingLabel', array($match));
      } else {
      	$value = str_replace($matches[0][$matchKey], $matches[1][$matchKey], $value);
      }
    }

    return $value;
  }

	/**
	 * Parses ###field### tags.
	 *
	 * @param string $value The string to process
	 * @param boolean $reportError If true report the error associated when the marker is not found
 	 *
	 * @return string 
	 */
  public function parseFieldTags($value, $reportError = true) {
  	
  	// Gets the extension object
  	$extension = $this->getController()->getExtensionConfigurationManager()->getExtension();
	
  	// Initaializes the markers
  	$markers = $this->buildSpecialMarkers();
  	
		// Processes special tags
    $markers['###linkToPage###'] = str_replace(
      '<a href="',
      '<a href="' . t3lib_div::getIndpEnv('TYPO3_SITE_URL'),
      $extension->pi_linkToPage('', $GLOBALS['TSFE']->id)
    );  	
    // Compatiblity with SAV Library
    $value = preg_replace('/###row\[([^\]]+)\]###/', '###$1###', $value);

    // Gets the main table
    $mainTable = $this->getQueryConfigurationManager()->getMainTable();

    // Gets the tags
    preg_match_all('/###(?P<render>render\[)?(?P<fullFieldName>(?<TableNameOrAlias>[^\.#\]]+)\.?(?<fieldName>[^#\]]*))\]?###/', $value, $matches);

    foreach ($matches['fullFieldName'] as $matchKey => $match) {
      if ($matches['fieldName'][$matchKey]) {
        // It's a full field name, i.e. tableName.fieldName
        if ($this->fieldExistsInCurrentRow($matches['fullFieldName'][$matchKey])) {
        	$fullFieldName = $matches['fullFieldName'][$matchKey];  	
        } else {	
        	// Unknown marker
          Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.unknownMarker', array($matches[0][$matchKey]));   
          continue;  
        }
      } else {
        // Checks if it's an alias
        if ($this->fieldExistsInCurrentRow($matches['TableNameOrAlias'][$matchKey])) {
          $fullFieldName = $matches['TableNameOrAlias'][$matchKey]; 
        } elseif ($this->fieldExistsInCurrentRow($mainTable . '.' . $matches['TableNameOrAlias'][$matchKey])) {
        // The main table was omitted
          $fullFieldName = $mainTable . '.' . $matches['TableNameOrAlias'][$matchKey];
        } elseif ($matches['TableNameOrAlias'][$matchKey] == 'user') {
          $markers[$matches[0][$matchKey]] = $GLOBALS['TSFE']->fe_user->user['uid'];
          continue;
      	} elseif ($reportError === true) {
          // Unknown marker
          Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.unknownMarker', array($matches[0][$matchKey]));
         continue;
        } else {
        	// Error is not reported and the value is unchanged
        	$markers[$matches[0][$matchKey]] = $matches[0][$matchKey];
        	continue;
        }
      }
 
      // Sets the marker either by rendering the field from the single view configuration or directly from the database
      if ($matches['render'][$matchKey]) {  

    		// Renders the field based on the TCA configuration as it would be rendered in a single view
    		$fieldKey = Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($fullFieldName);
    		$basicFieldConfiguration = $this->getController()->getLibraryConfigurationManager()->searchBasicFieldConfiguration($fieldKey);    		
    		$fieldConfiguration = Tx_SavLibraryPlus_Managers_TcaConfigurationManager::getTcaConfigFieldFromFullFieldName($fullFieldName);
    		
    		// Adds the basic configuration if found
    		if (is_array($basicFieldConfiguration)) {
    			$fieldConfiguration = array_merge($fieldConfiguration, $basicFieldConfiguration);
    		}
    		
    		// Adds the value from the current row
    		$fieldConfiguration['value'] = $this->getFieldValueFromCurrentRow($fullFieldName);  
  		
				// Calls the item viewer 		
      	$className = 'Tx_SavLibraryPlus_ItemViewers_Default_' . $fieldConfiguration['fieldType'] . 'ItemViewer';
      	$itemViewer = t3lib_div::makeInstance($className);
      	$itemViewer->injectController($this->getController());
      	$itemViewer->injectItemConfiguration($fieldConfiguration);        	
      	$markers[$matches[0][$matchKey]] = $itemViewer->render();    
      } else {
        $markers[$matches[0][$matchKey]] = $this->getFieldValueFromCurrentRow($fullFieldName);             	
      }      
    }
    
    // Gets the content object
  	$contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();

    return $contentObject->substituteMarkerArrayCached($value, $markers, array(), array());
  }

	/**
	 * Processes tags in where clause.
	 *
	 * @param string $whereClause The string to process
 	 *
	 * @return string 
	 */
  public function processWhereClauseTags($whereClause) {
  	
  	// Initaializes the markers
  	$markers = $this->buildSpecialMarkers();

  	// Gets the content object
  	$contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();
  	
  	// Replaces the special markers
  	$whereClause = $contentObject->substituteMarkerArrayCached($whereClause, $markers, array(), array());
    
		// Processes the ###group_list### tag
    if (preg_match_all('/###group_list[ ]*([!]?)=([^#]*)###/', $whereClause, $matches)) {
      foreach ($matches[2] as $matchKey => $match) {
        $groups = explode (',', str_replace(' ', '', $match)); 
        $clause = '';    

        // Gets the content object
      	$extensionConfigurationManager = $this->getController()->getExtensionConfigurationManager();
      	$contentObject = $extensionConfigurationManager->getExtensionContentObject();
        
        // Gets the group list of uid
        $this->resource = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				    /* SELECT   */	'uid,title',	
				    /* FROM     */	'fe_groups',
	 			    /* WHERE    */	'1' .
              $contentObject->enableFields('fe_groups')
		    );

        while ($rows = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($this->resource)) {
          if (in_array($rows['title'], $groups)) {
            if ($matches[1][$matchKey] == '!') {
              $clause .= ' AND find_in_set(' . $rows['uid'] . ', fe_users.usergroup)=0';
            } else {
              $clause .= ' OR find_in_set(' . $rows['uid'] . ', fe_users.usergroup)>0';
            }        
          }     
        }
    
        // Replaces the tag
        if ($matches[1][$matchKey] == '!') {
          $whereClause = preg_replace(
            '/###group_list[ ]*!=([^#]*)###/',
            '(1' . $clause . ')',
            $whereClause
          );
        } else {
          $whereClause = preg_replace(
            '/###group_list[ ]*=([^#]*)###/',
            '(0' . $clause . ')',
            $whereClause
          );
        }
      }
    }

    // Processes conditionnal part
    if (preg_match_all('/###([^:]+):([^#]+)###/', $whereClause, $matches)) {

      foreach ($matches[1] as $matchKey => $match) {
        $replace = '1';
        preg_match('/([^\(]+)(?:\(([^\)]*)\)){0,1}/', $match, $matchFunctions);
        
        $conditionFunction = $matchFunctions[1];
        if ($conditionFunction && method_exists('Tx_SavLibraryPlus_Utility_Conditions', $conditionFunction)) {
          // Checks if there is one parameter
          if ($matchFunctions[2]) {
            if (Tx_SavLibraryPlus_Utility_Conditions::$conditionFunction($matchFunctions[2])) {
              $replace .= ' AND ' . $matches[2][$matchKey];
            }          
          } else {
            if (Tx_SavLibraryPlus_Utility_Conditions::$conditionFunction()) {
              $replace .= ' AND ' . $matches[2][$matchKey];
            }
          }
        } else {
          Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.unknownFunctionInWhere', array($matchFunc[1]));
        }       

        $whereClause = preg_replace('/###[^:]+:[^#]+###/', $replace, $whereClause);
      }

    }

    return $whereClause;  
  }  
 
	/**
	 * Builds special markers
	 *
	 * @return array
	 */
  protected function buildSpecialMarkers() {
    // ###uid### marker
    $markers['###uid###'] = Tx_SavLibraryPlus_Managers_UriManager::getUid();;   
    
    // ###user### marker
    $markers['###user###'] = $GLOBALS['TSFE']->fe_user->user['uid'];

    // ###STORAGE_PID### marker
    $storageSiterootPids = $GLOBALS['TSFE']->getStorageSiterootPids();
    $markers['###STORAGE_PID###'] = $storageSiterootPids['_STORAGE_PID'];

    // ###CURRENT_PID### marker
    $markers['###CURRENT_PID###'] = $GLOBALS['TSFE']->page['uid'];
    
    return $markers;  
  } 
  
	/**
	 * Check if a quey is a SELECT query
	 *
	 */
	public function isSelectQuery($query) {
    return preg_match('/^[ \r\t\n]*(?i)select[ ]*/', $query);
  }  
  
	/**
	 * Replaces table names by their alias
	 *
   * @param $x string (string to process)
 	 *
	 * @return string (result)
	 */
  public function replaceTableNames($x) {

    preg_match_all('/([^(\. =0-9]+)([0-9]*)\./', $x, $matches);

    if ($matches[1]) {
      foreach($matches[1] as $key=>$match) {
        if ($matches[2][$key]) {
          if ($this->aliasTable[$match.$matches[2][$key]]) {
            $x = str_replace(
              $matches[0][$key],
              $this->aliasTable[$match . $matches[2][$key]] . '.',
              $x
            );
          }
        }
      }
    }

    return $x;
  }

	/**
	 * Sets the rows
	 *
	 * @param none
 	 *
	 * @return none
	 */
  protected function setRows() {
    $counter = 0;
    $mainTable = $this->queryConfigurationManager->getMainTable();
    $this->rows = array();
		while ($row = $this->getRowWithFullFieldNames($counter++)) {
		  $row['uid'] = $row[$mainTable . '.uid'];
		  $row['cruser_id'] = $row[$mainTable . '.cruser_id'];
			$this->rows[] = $row;
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($this->resource);
  }

	/**
	 * Reads rows and return an array with the tablenames
	 *
	 * @param $rowCounter integer (row counter)
 	 *
	 * @return array or boolean
	 */
  protected function getRowWithFullFieldNames($rowCounter = 0) {

    $rows = array();
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_row($this->resource);
    if ($row) {
  		foreach($row as $fieldKey => $field) {
  	    if ($rowCounter == 0) {
    		  $this->fieldObjects[$fieldKey] = mysql_fetch_field($this->resource, $fieldKey);
        }

        $fieldObject = $this->fieldObjects[$fieldKey];
        if ($fieldObject->table) {
    		  $result[$fieldObject->table . '.' . $fieldObject->name] = $field;
    		} else {
    		  $result[$fieldObject->name] = $field;
        }
      }
    return $result;
    } else {
      return false;
    }
  }

}
?>
