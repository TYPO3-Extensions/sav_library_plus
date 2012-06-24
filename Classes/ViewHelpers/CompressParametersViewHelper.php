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
 * Compresses parameters
 *
 * @package SavLibraryMvc
 * @version $Id:
 */
class Tx_SavLibraryPlus_ViewHelpers_CompressParametersViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @param array $arguments Arguments
	 */
	public function render($arguments) {
	
    $compressedParameters = Tx_SavLibraryPlus_Controller_AbstractController::compressParameters($arguments);

		return $compressedParameters;
	}
}
?>
