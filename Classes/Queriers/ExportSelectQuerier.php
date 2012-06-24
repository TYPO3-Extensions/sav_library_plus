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
 * Default Export Select Querier.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */

class Tx_SavLibraryPlus_Queriers_ExportSelectQuerier extends Tx_SavLibraryPlus_Queriers_AbstractQuerier {

	/**
	 * The export table name
	 * 
	 * @var string
	 */	
	public static $exportTableName = 'tx_savlibraryplus_export_configuration';

	/**
	 * The export configuration
	 * 
	 * @var array
	 */
	protected $exportConfiguration;	

	/**
	 * The fields to exclude
	 * 
	 * @var array
	 */	
	protected $fieldsToExclude = array (
      'uid','pid','crdate','tstamp','hidden','deleted','cruser_id','disable',
      'starttime','endtime','password','lockToDomain','is_online','lastlogin',
			't3ver_id','t3ver_oid','t3ver_label','t3ver_wsid','t3ver_stage','t3ver_state','t3ver_tstamp','t3_origuid','t3ver_count',
      'TSconfig',
  );
	
  /**
   * Executes the query
   *
   * @param none
   *
   * @return none
   */
  protected function executeQuery() {
  	
    // Executes the select query to get the field names
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

    // Replaces the field values by the checkbox value 
    $this->exportConfiguration = array();
    foreach ($this->rows[0] as $rowKey => $row) {
    	if ($this->isFieldToExclude($rowKey) === false) {
    		$this->exportConfiguration['fields'][$rowKey]['selected'] = 0;
    		$this->exportConfiguration['fields'][$rowKey]['render'] = 0;   
    	} 	
    } 

    return;  	
  }

  /**
   * Returns true if the field must be excluded
   *
   * @param string $fieldName
   *
   * @return boolean
   */
  public function isFieldToExclude($fieldName) {
  	$fileNameParts = explode('.', $fieldName);
  	return in_array($fileNameParts[1], $this->fieldsToExclude);  
  }  
  
  /**
   * Gets the export configuration
   *
   * @param none
   *
   * @return none
   */
  public function getExportConfiguration() {
  	
  	// Unsets fileds which should not be displayed
  	foreach ($this->exportConfiguration['fields'] as $fieldKey => $field) {
  		if ($this->isFieldToExclude($fieldKey) && empty($this->exportConfiguration['includeAllFields'])) {
  			unset($this->exportConfiguration['fields'][$fieldKey]);
  		}
  	}
    return $this->exportConfiguration;  	
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

    // Adds the enable fields conditions for the main table
    $mainTable = $this->queryConfigurationManager->getMainTable();
    $whereClause .= $extensionConfigurationManager->getExtensionContentObject()->enableFields($mainTable);
    
		// Adds the allowed pages condition
    $whereClause .= $this->getAllowedPages($mainTable);

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
		return '1';
  }  

}
?>
