<?php
namespace SAV\SavLibraryPlus\Compatibility\Database;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
*  Copyright notice
*
*  (c) 2010 Laurent Foulloy (yolf.typo3@orange.fr)
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
 * Compatibility with version 6.x.
 *
 * @package SavLibraryPlus
 */
class DatabaseConnection {

	/**
	 * 
	 * Returns the field as an object
	 * 
	 * MySQLi is used for TYPO3 greater than 6.0
	 *
	 *
	 * @param resource $resource The resource identifier
	 * @param integer $fieldKey The field number
	 * 
	 * @return object
	 */
	static public function fetchField($resource, $fieldKey) {
    if (version_compare(TYPO3_version, '6.1', '<')) { 
      return mysql_fetch_field($resource, $fieldKey);
    } else {
    	return $resource->fetch_field_direct($fieldKey);
    }
	}	  

}
?>
