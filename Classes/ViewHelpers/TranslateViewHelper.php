<?php
namespace SAV\SavLibraryPlus\ViewHelpers;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

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
class TranslateViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Translate a given key or use the tag body as default.
	 *
	 * @param string $key The locallang key
	 * @param string $default if the given locallang key could not be found, this value is used. . If this argument is not set, child nodes will be used to render the default
	 * @param boolean $htmlEscape TRUE if the result should be htmlescaped. This won't have an effect for the default value
	 * @param array $arguments Arguments to be replaced in the resulting string
	 * @param string $extension Name of the extension
	 * @return string The translated key or tag body if key doesn't exist
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function render($key, $default = NULL, $htmlEscape = TRUE, array $arguments = NULL, $extension = NULL) {

    // The library is the default extension
    if ($extension === NULL) {
      $extension = \SAV\SavLibraryPlus\Controller\AbstractController::LIBRARY_NAME;
    }

    // Translates the key
		$value = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, $extension, $arguments);

		if ($value === NULL) {
			$value = $default !== NULL ? $default : $key;
		} elseif ($htmlEscape) {
			$value = htmlspecialchars($value);
		}
		return $value;
	}
}

?>
