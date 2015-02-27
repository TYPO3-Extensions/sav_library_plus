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
 * A view helper for creating links to extbase actions.
 *
 * = Examples =
 *
 * <code title="link to the show-action of the current controller">
 * <f:link.action action="show">action link</f:link.action>
 * </code>
 * <output>
 * <a href="index.php?id=123&tx_myextension_plugin[action]=show&tx_myextension_plugin[controller]=Standard&cHash=xyz">action link</f:link.action>
 * (depending on the current page and your TS configuration)
 * </output>
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
require_once(t3lib_extMgm::extPath('sav_library_plus') . 'Classes/ViewHelpers/Link/ActionViewHelper.php');

class Tx_SavLibraryPlus_ViewHelpers_Link_ActionViewHelper extends \SAV\SavLibraryPlus\ViewHelpers\Link\ActionViewHelper {
}
?>
