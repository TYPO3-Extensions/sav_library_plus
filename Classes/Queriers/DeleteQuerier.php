<?php
namespace SAV\SavLibraryPlus\Queriers;

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
 * Default Delete Querier.
 *
 * @package SavLibraryPlus
 * @version $ID:$
 */

class DeleteQuerier extends AbstractQuerier {

	/**
	 * Executes the query.
	 *
	 * @param none
	 * 
	 * @return none
	 */
  protected function executeQuery() {
  
    // Checks if the user is authenticated
 		if (is_null($GLOBALS['TSFE']->fe_user->user['uid'])) {
      \SAV\SavLibraryPlus\Controller\FlashMessages::addError('fatal.notAuthenticated');
      return FALSE;
		}

		// Gets the uid
    $uid = \SAV\SavLibraryPlus\Managers\UriManager::getUid();
    
    // Gets the main table 
    $mainTable = $this->getQueryConfigurationManager()->getMainTable();    
    
    $this->setDeletedField($mainTable, $uid);
  }
  
}
?>
