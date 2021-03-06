<?php
namespace SAV\SavLibraryPlus\ViewHelpers;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
*  Copyright notice
*
*  (c) 2009 Laurent Foulloy <yolf.typo3@orange.fr>
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
 * View helper which builds the src attribute
 *
 * @version $Id:$
 * @copyright Copyright belongs to the respective authors
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 * @scope prototype
 * @entity
 */
class GetIconViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Renders the content.
	 *
	 * @param string $fileName relative file name.
	 * @return string Rendered string
	 *
	 * @author Laurent Foulloy <yolf.typo3@orange.fr>
	 */
	public function render($fileName) {

    // Checks if the file Name exists in the SAV Library Plus
    $filePath = \SAV\SavLibraryPlus\Managers\LibraryConfigurationManager::getIconPath($fileName);

    if (file_exists($filePath)) {
      return $filePath;
    } else {
	    return NULL;
    }
	}
}

?>
