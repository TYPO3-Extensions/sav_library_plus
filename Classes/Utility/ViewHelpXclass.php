<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Laurent Foulloy (yolf.typo3@orange.fr)
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
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

class ux_SC_view_help extends SC_view_help {

	private $firstHeader = true;
	
	private $allowedIdentifiers = array('sav_library_kickstarter', 'sav_library_plus');
	
	/**
	 * Adds the kickstarter css file
	 *
	 * @return	void
	 */
	function init()	{
		global $TBE_TEMPLATE;		

		parent::init();		
			
		$TBE_TEMPLATE->getPageRenderer()->addCssFile(t3lib_extMgm::extRelPath('sav_library_plus') . 'Resources/Private/Styles/help.css');
	}

	/**
	 * Checks if the Id comes from the SAV Library Kickstarter
	 *
	 * @return	void
	 */
	protected function isAllowedIdentifer() {
		foreach ($this->allowedIdentifiers as $allowedIdentifer) {
			if (preg_match('/^' . $allowedIdentifer . '/', $this->tfID) > 0) {
				return true;
			}
		}
		return false;
	}	
	
	/**
	 * Returns header HTML content with span element for a better css processing
	 *
	 * @param	string		Header text
	 * @param	string		Header type (1, 0)
	 * @return	string		The HTML for the header.
	 */
	function headerLine($str,$type=0)	{
		
		if ($this->isAllowedIdentifer() === false) {
			return parent::headerLine($str,$type);
		}

		$header = htmlspecialchars($str);
		if (preg_match('/^([^:]+)([:]?)(.*)$/', $header, $match)) {
			$header = 
				'<span class="lhs">' . $match[1] . '</span>' .
				'<span class="token">' . $match[2] . '</span>' .
				'<span class="rhs">' . $match[3] . '</span>';	
		}
		if ($this->firstHeader) {
			$header = '<div class="firstHeader">' . $header . '</div>';
			$this->firstHeader = false;
		}
		
		switch($type)	{
			case 1:
				$str = '<h2 class="t3-row-header">' . $header . '</h2>
				';
			break;
			case 0:
				$str = '<h3 class="divider">' . $header . '</h3>
				';
			break;
		}

		return $str;
	}
	
	/**
	 * Render CSH for a full cshKey/table
	 *
	 * @param string $key Full CSH key (may be different from table name)
	 * @param string $table CSH key / table name
	 * @return string HTML output
	 */
	function render_Table($key, $table = NULL) {
		global $BE_USER,$TCA_DESCR,$TCA,$LANG;

		if ($this->isAllowedIdentifer() === false) {
			return parent::render_Table($key, $table);
		}
		
		$output = '';

			// take default key if not explicitly specified
		if ($table === NULL) {
			$table = $key;
		}

			// Load table TCA
		t3lib_div::loadTCA($key);

			// Load descriptions for table $table
		$LANG->loadSingleTableDescription($key);

		if (is_array($TCA_DESCR[$key]['columns']) && (!$this->limitAccess || $BE_USER->check('tables_select', $table))) {
				// Initialize variables:
			$parts = array();
			$parts[0] = '';	// Reserved for header of table

				// Traverse table columns as listed in TCA_DESCR
			foreach ($TCA_DESCR[$key]['columns'] as $field => $_) {

				$fieldValue = isset($TCA[$key]) && strcmp($field, '') ? $TCA[$key]['columns'][$field] : array();

				if (is_array($fieldValue) && (!$this->limitAccess || !$fieldValue['exclude'] || $BE_USER->check('non_exclude_fields', $table . ':' . $field))) {
					if (!$field)	{
						$parts[0] = $this->printItem($key, '', 1);	// Header
					} else {
						$parts[] = $this->printItem($key, $field, 1);	// Field
					}
				}
			}

			if (!$parts[0])	{
				unset($parts[0]);
			}
//			$output .= implode('<br />', $parts);
			$output .= implode('', $parts);
		}

			// Substitute glossary words:
		$output = $this->substituteGlossaryWords($output);

		// Back link
		$backLink =	($this->back ? '<p class="c-nav"><a href="' . htmlspecialchars('view_help.php?tfID=' . rawurlencode($this->back)) . '" class="typo3-goBack">' . htmlspecialchars($LANG->getLL('goBack')) . '</a></p>' : '');
		
			// TOC link:
		if (!$this->renderALL) {
			$tocLink = '<p class="c-nav"><a href="view_help.php">' . $LANG->getLL('goToToc', 1) . '</a></p>';

			$output =
//				$tocLink.'
//				<br/>'.
				$output.
				'<div class="links">'	.
				$backLink . 
				$tocLink .
				'</div>'
				;
		}

		return $output;
	}
	
	/**
	 * Prints a single $table/$field information piece without a trailing <br />
	 * If $anchors is set, then seeAlso references to the same table will be page-anchors, not links.
	 *
	 * @param string $key CSH key / table name
	 * @param string $field Sub key / field name
	 * @param boolean $anchors If anchors is to be shown.
	 * @return string HTML content
	 */
	function printItem($key, $field, $anchors = FALSE) {
		global $TCA_DESCR, $LANG, $TCA, $BE_USER;

		if ($this->isAllowedIdentifer() === false) {
			return parent::printItem($key, $field, $anchors);
		}		
		
		$out = '';

			// Load full table definition in $TCA
		t3lib_div::loadTCA($key);

		if ($key && (!$field || is_array($TCA_DESCR[$key]['columns'][$field])))	{
				// Make seeAlso references.
			$seeAlsoRes = $this->make_seeAlso($TCA_DESCR[$key]['columns'][$field]['seeAlso'], $anchors ? $key : '');

				// Making item:
			$out = '<a name="' . $key . '.' . $field . '"></a>' .
					$this->headerLine($this->getTableFieldLabel($key, $field), 1) .
					$this->prepareContent($TCA_DESCR[$key]['columns'][$field]['description']) .
					($TCA_DESCR[$key]['columns'][$field]['details'] ? $this->headerLine($LANG->getLL('details').':').$this->prepareContent($TCA_DESCR[$key]['columns'][$field]['details']) : '') .
					($TCA_DESCR[$key]['columns'][$field]['syntax'] ? $this->headerLine($LANG->getLL('syntax').':').$this->prepareContent($TCA_DESCR[$key]['columns'][$field]['syntax']) : '') .
					($TCA_DESCR[$key]['columns'][$field]['image'] ? $this->printImage($TCA_DESCR[$key]['columns'][$field]['image'],$TCA_DESCR[$key]['columns'][$field]['image_descr']) : '') .
					($TCA_DESCR[$key]['columns'][$field]['seeAlso'] && $seeAlsoRes ? $this->headerLine($LANG->getLL('seeAlso').':').'<p>'.$seeAlsoRes.'</p>' : '') .
//					($this->back ? '<br /><p><a href="' . htmlspecialchars('view_help.php?tfID=' . rawurlencode($this->back)) . '" class="typo3-goBack">' . htmlspecialchars($LANG->getLL('goBack')) . '</a></p>' : '') .
//					'<br />';
					'';
		}
		return $out;
	}
		
}