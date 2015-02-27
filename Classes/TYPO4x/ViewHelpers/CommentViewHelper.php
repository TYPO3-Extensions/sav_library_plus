<?php

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * A view helper for creating comments.
 *
 * = Examples =
 *
 * <code title="Comment">
 * <f:comment>This is a comment</f:comment>
 * </code>
 *
 * Output:
 * None
 *
 * @package SavLibraryMvc
 * @subpackage ViewHelpers
 * @version $Id: 
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
require_once(t3lib_extMgm::extPath('sav_library_plus') . 'Classes/ViewHelpers/CommentViewHelper.php');

class Tx_SavLibraryPlus_ViewHelpers_CommentViewHelper extends \SAV\SavLibraryPlus\ViewHelpers\CommentViewHelper {
}
?>

