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
 * Default Form Admin Select Querier.
 *
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_Queriers_FormAdminSelectQuerier extends Tx_SavLibraryPlus_Queriers_FormSelectQuerier {

  /**
   * Executes the query
   *
   * @param none
   *
   * @return none
   */
  public function executeQuery() {

    // Checks if the user is authenticated
    if($this->getController()->getUserManager()->userIsAllowedToInputData() === false) {
      Tx_SavLibraryPlus_Controller_FlashMessages::addError('fatal.notAllowedToEnterInFormAdministration');
      return false;
    }

    // Processes the parent query
    parent::executeQuery();   
  }
  
  /**
   * Builds the WHERE clause
   *
   * @param none
   *
   * @return string The WHERE clause
   */
  protected function buildWhereClause() {
  	
    // Gets the uid
    $uid = Tx_SavLibraryPlus_Managers_UriManager::getUid();

    // Builds the where clause
    $whereClause = '1 AND ';
    $whereClause .= $this->getQueryConfigurationManager()->getMainTable(). '.uid = ' . intval($uid);
    
    return $whereClause;
  }
  
  
}
?>
