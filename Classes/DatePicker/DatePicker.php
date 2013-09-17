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
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Date picker.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_DatePicker_DatePicker {

  // Constants
  const KEY = 'datePicker';	
	
  /**
   * The date picker path
   *
   * @var string
   */
  protected static $datePickerPath = 'Classes/DatePicker/';	

  /**
   * The date picker CSS file
   *
   * @var string
   */
  protected static $datePickerCssFile = 'calendar-win2k-2.css';	
    
  /**
   * The javaScript file
   *
   * @var string
   */
  protected static $datePickerJsFile = 'calendar.js';
  protected static $datePickerJsSetupFile = 'calendar-setup.js';
  protected static $datePickerLanguageFile;

  /**
   *  Constructor 
   *  
   * @param none
   *
   * @return none
   */
  public function __construct() {
    self::$datePickerLanguageFile = 'calendar-' . $GLOBALS['TSFE']->config['config']['language'] . '.js';
    $datePickerLanguagePath = t3lib_extMgm::siteRelPath(Tx_SavLibraryPlus_Controller_AbstractController::LIBRARY_NAME) . self::$datePickerPath . 'lang/';
    if (file_exists($datePickerLanguagePath . self::$datePickerLanguageFile) === FALSE) {
      self::$datePickerLanguageFile = 'calendar-en.js';
    }
    self::compatibility();
    self::addCascadingStyleSheet();    
    self::addJavaScript();  
  }

  /**
   *  Removes the additional header data inserted by SAV Library if it exists 
   *  
   * @param none
   *
   * @return none
   */  
	protected static function compatibility() {
		if (version_compare(TYPO3_version, '6.0', '<')) { 	
	  	if($GLOBALS['TSFE']->additionalHeaderData['DatePicker']) {
	  		unset($GLOBALS['TSFE']->additionalHeaderData['DatePicker']);
	  	}
		}    	
	} 
     
  /**
   *  Adds the date picker css file 
   *  - from the datePicker.stylesheet TypoScript configuration if any
   *  - else from the default css file
   *  
   * @param none
   *
   * @return none
   */
  protected static function addCascadingStyleSheet() {
    $extensionKey = Tx_SavLibraryPlus_Controller_AbstractController::LIBRARY_NAME;
  	$key = self::KEY . '.';
  	$extensionTypoScriptConfiguration = Tx_SavLibraryPlus_Managers_ExtensionConfigurationManager::getTypoScriptConfiguration();
  	$datePickerTypoScriptConfiguration = $extensionTypoScriptConfiguration[$key];
  	if (empty($datePickerTypoScriptConfiguration['stylesheet']) === FALSE) {
  		// The style sheet is given by the extension TypoScript
  		$cascadingStyleSheetAbsoluteFileName = t3lib_div::getFileAbsFileName($datePickerTypoScriptConfiguration['stylesheet']);
  		if (is_file($cascadingStyleSheetAbsoluteFileName)) {
  			$cascadingStyleSheet = substr($cascadingStyleSheetAbsoluteFileName, strlen(PATH_site));
				Tx_SavLibraryPlus_Managers_AdditionalHeaderManager::addCascadingStyleSheet($cascadingStyleSheet);
  		} else {
				throw new Tx_SavLibraryPlus_Exception(Tx_SavLibraryPlus_Controller_FlashMessages::translate('error.fileDoesNotExist', array(htmlspecialchars($cascadingStyleSheetAbsoluteFileName))));		
  		}
  	} else {
			$libraryTypoScriptConfiguration = Tx_SavLibraryPlus_Managers_LibraryConfigurationManager::getTypoScriptConfiguration();
  	  $datePickerTypoScriptConfiguration = $libraryTypoScriptConfiguration[$key];
  		if (empty($datePickerTypoScriptConfiguration['stylesheet']) === FALSE) {
  			// The style sheet is given by the library TypoScript		
  			$cascadingStyleSheetAbsoluteFileName = t3lib_div::getFileAbsFileName($datePickerTypoScriptConfiguration['stylesheet']);
  			if (is_file($cascadingStyleSheetAbsoluteFileName)) {
  				$cascadingStyleSheet = substr($cascadingStyleSheetAbsoluteFileName, strlen(PATH_site));
					Tx_SavLibraryPlus_Managers_AdditionalHeaderManager::addCascadingStyleSheet($cascadingStyleSheet);
  			} else {
					throw new Tx_SavLibraryPlus_Exception(Tx_SavLibraryPlus_Controller_FlashMessages::translate('error.fileDoesNotExist', array(htmlspecialchars($cascadingStyleSheetAbsoluteFileName))));		
  			}
  		} else {
  				// The style sheet is the default one
					$cascadingStyleSheet = t3lib_extMgm::siteRelPath($extensionKey) . self::$datePickerPath . 'css/'. self::$datePickerCssFile;
					Tx_SavLibraryPlus_Managers_AdditionalHeaderManager::addCascadingStyleSheet($cascadingStyleSheet); 			
  		}  		
  	} 
  }
  
  /**
   * Adds javascript
   *
   * @param none
   *
   * @return none
   */
  public static function addJavaScript() {
  	$datePickerSiteRelativePath = t3lib_extMgm::siteRelPath(Tx_SavLibraryPlus_Controller_AbstractController::LIBRARY_NAME) . self::$datePickerPath;
  	Tx_SavLibraryPlus_Managers_AdditionalHeaderManager::addJavaScriptFile($datePickerSiteRelativePath . 'js/' . self::$datePickerJsFile);
  	Tx_SavLibraryPlus_Managers_AdditionalHeaderManager::addJavaScriptFile($datePickerSiteRelativePath . 'lang/' . self::$datePickerLanguageFile);
  	Tx_SavLibraryPlus_Managers_AdditionalHeaderManager::addJavaScriptFile($datePickerSiteRelativePath . 'js/' . self::$datePickerJsSetupFile);
  }

  /**
   *  Gets the date picker format 
   *  
   * @param none
   *
   * @return none
   */
  protected static function getDatePickerFormat() {
    $extensionKey = Tx_SavLibraryPlus_Controller_AbstractController::LIBRARY_NAME;
  	$key = self::KEY . '.';
  	$extensionTypoScriptConfiguration = Tx_SavLibraryPlus_Managers_ExtensionConfigurationManager::getTypoScriptConfiguration();
  	$datePickerTypoScriptConfiguration = $extensionTypoScriptConfiguration[$key];
  	if (is_array($datePickerTypoScriptConfiguration['format.'])) {
  		return $datePickerTypoScriptConfiguration['format.'];
  	} else {
  		$libraryTypoScriptConfiguration = Tx_SavLibraryPlus_Managers_LibraryConfigurationManager::getTypoScriptConfiguration();
  		$datePickerTypoScriptConfiguration = $libraryTypoScriptConfiguration[$key];
  		if (is_array($datePickerTypoScriptConfiguration['format.'])) {
  			return $datePickerTypoScriptConfiguration['format.'];
  		}   		
  	} 
  	return NULL; 	
  } 
  
  /**
   * Renders the date picker
   *
   * @param none
   *
   * @return none
   */
  public function render($datePickerConfiguration) {
    $datePickerSetup[] = '<a href="#">';
    $datePickerSetup[] = '<img class="datePickerCalendar" id="button_' . $datePickerConfiguration['id'] . '" src="' . $datePickerConfiguration['iconPath'] . '" alt="" title="" />';
    $datePickerSetup[] = '</a>';
    $datePickerSetup[] = '<script type="text/javascript">';
    $datePickerSetup[] = '/*<![CDATA[*/';
    $datePickerSetup[] = '  Calendar.setup({';
    $datePickerSetup[] = '    inputField     :    "input_' . $datePickerConfiguration['id'] . '",';
    $datePickerSetup[] = '    ifFormat       :    "' . $datePickerConfiguration['format'] . '",';
    
    // Gets the date picker format
    $datePickerFormat = self::getDatePickerFormat();
    if (empty($datePickerFormat['toolTipDate']) === FALSE) {
    	$datePickerSetup[] = '    ttFormat       :    "' . $datePickerFormat['toolTipDate'] . '",';
    }
      if (empty($datePickerFormat['titleBarDate']) === FALSE) {
    	$datePickerSetup[] = '    tbFormat       :    "' . $datePickerFormat['titleBarDate'] . '",';
    }    
    $datePickerSetup[] = '    button         :    "button_' . $datePickerConfiguration['id'] . '",';
    $datePickerSetup[] = '    showsTime      :    ' . ($datePickerConfiguration['showsTime'] ? 'true' : 'false') . ',';
    $datePickerSetup[] = '    singleClick    :    true';
    $datePickerSetup[] = '  });';
    $datePickerSetup[] = '/*]]>*/';
    $datePickerSetup[] = '</script>';
    
    return implode(chr(10), $datePickerSetup);
  }

}

?>
