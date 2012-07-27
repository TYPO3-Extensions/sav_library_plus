+<?php
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
 * Default Form Admin Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_Viewers_FormAdminViewer extends Tx_SavLibraryPlus_Viewers_FormViewer {
 
  /**
   * The template file
   *
   * @var string
   */
  protected $templateFile = 'FormAdmin.html';
  
  /**
	 * Parses the ###field[]### markers
	 *
	 * @param string $template
	 *
	 * @return string 
	 */
	protected function parseFieldSpecialTags($template) {  
    // Processes the field marker
    preg_match_all('/###field\[([^\],]+)(,?)([^\]]*)\]###/', $template, $matches);  

    foreach($matches[0] as $matchKey => $match) {
			if ($matches[2][$matchKey]) {
				$querier = $this->getController()->getQuerier();
				$fullFieldName =  $querier->buildFullFieldName($matches[1][$matchKey]);
				$class = ($querier->getFieldValueFromCurrentRow($fullFieldName) == $querier->getFieldValueFromSavedRow($fullFieldName) ? 'column4Same' : 'column4Different');
				$replacementString = 
           '<div class="column1">$$$label[' . $matches[3][$matchKey] . ']$$$</div>' .
           '<div class="column2">###renderSaved[' . $matches[1][$matchKey] . ']###</div>' .
           '<div class="column3">###renderEdit[' . $matches[1][$matchKey] . ']###</div>' .
           '<div class="' . $class . '">###renderValidation[' . $matches[1][$matchKey] . ']###</div>';				
			} else {
				$replacementString = '###renderEdit[' . $matches[1][$matchKey] . ']###' .
					'###renderValidation[' . $matches[1][$matchKey] . ']###';				
			}
			$template = str_replace($matches[0][$matchKey], $replacementString, $template);				
    }
    return $template;  	  
	}  
   
}
?>
