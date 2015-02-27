<?php
namespace SAV\SavLibraryPlus\Compatibility\Utility;

/***************************************************************
*  Copyright notice
*
*  (c) 2013 Laurent Foulloy (yolf.typo3@orange.fr)
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
class GeneralUtility {

	/**
	 * Loads the $GLOBALS['TCA'] (Table Configuration Array) for the $table
	 *
	 * Requirements:
	 * 1) must be configured table (the ctrl-section configured),
	 * 2) columns must not be an array (which it is always if whole table loaded), and
	 * 3) there is a value for dynamicConfigFile (filename in typo3conf)
	 *
	 * @param string $table Table name for which to load the full TCA array part into $GLOBALS['TCA']
	 * @return void
	 * 
	 */
	static public function loadTCA($table) {
    if (version_compare(TYPO3_version, '6.1', '<')) { 
      \TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA($table);	
    }
	}	
}
	
?>
