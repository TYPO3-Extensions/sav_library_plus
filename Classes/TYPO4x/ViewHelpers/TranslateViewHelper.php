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
 * Translate a key from locallang. The files are loaded from the folder
 * "Resources/Private/Language/".
 *
 * == Examples ==
 *
 * <code title="Translate key">
 * <f:translate key="key1" />
 * </code>
 * <output>
 * // value of key "key1" in the current website language
 * </output>
 *
 * <code title="Keep HTML tags">
 * <f:translate key="htmlKey" htmlEscape="false" />
 * </code>
 * <output>
 * // value of key "htmlKey" in the current website language, no htmlspecialchars applied
 * </output>
 *
 * <code title="Translate key from custom locallang file">
 * <f:translate key="LLL:EXT:myext/Resources/Private/Language/locallang.xml:key1" />
 * </code>
 * <output>
 * // value of key "key1" in the current website language
 * </output>
 *
 * <code title="Inline notation with arguments and default value">
 * {f:translate(key: 'argumentsKey', arguments: {0: 'dog', 1: 'fox'}, default: 'default value')}
 * </code>
 * <output>
 * // value of key "argumentsKey" in the current website language
 * // with "%1" and "%2" are replaced by "dog" and "fox" (printf)
 * // if the key is not found, the output is "default value"
 * </output>
 */
require_once(t3lib_extMgm::extPath('sav_library_plus') . 'Classes/ViewHelpers/TranslateViewHelper.php');

class Tx_SavLibraryPlus_ViewHelpers_TranslateViewHelper extends \SAV\SavLibraryPlus\ViewHelpers\TranslateViewHelper {
}

?>
