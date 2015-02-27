<?php

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * View helper which renders the flash messages (if there are any) as an unsorted list.
 *
 * In case you need custom Flash Message HTML output, please write your own ViewHelper for the moment.
 *
 *
 * = Examples =
 *
 * <code title="Simple">
 * <f:flashMessages />
 * </code>
 * <output>
 * An ul-list of flash messages.
 * </output>
 *
 * <code title="Output with custom css class">
 * <f:flashMessages class="specialClass" />
 * </code>
 * <output>
 * <ul class="specialClass">
 *  ...
 * </ul>
 * </output>
 *
 * <code title="TYPO3 core style">
 * <f:flashMessages renderMode="div" />
 * </code>
 * <output>
 * <div class="typo3-messages">
 *   <div class="typo3-message message-ok">
 *     <div class="message-header">Some Message Header</div>
 *     <div class="message-body">Some message body</div>
 *   </div>
 *   <div class="typo3-message message-notice">
 *     <div class="message-body">Some notice message without header</div>
 *   </div>
 * </div>
 * </output>
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 */
require_once(t3lib_extMgm::extPath('sav_library_plus') . 'Classes/ViewHelpers/FlashMessagesViewHelper.php');

class Tx_SavLibraryPlus_ViewHelpers_FlashMessagesViewHelper extends \SAV\SavLibraryPlus\ViewHelpers\FlashMessagesViewHelper {
}

?>
