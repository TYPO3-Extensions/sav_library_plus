<?php/****************************************************************  Copyright notice**  (c) 2011 Laurent Foulloy <yolf.typo3@orange.fr>*  All rights reserved**  This script is part of the TYPO3 project. The TYPO3 project is*  free software; you can redistribute it and/or modify*  it under the terms of the GNU General Public License as published by*  the Free Software Foundation; either version 2 of the License, or*  (at your option) any later version.**  The GNU General Public License can be found at*  http://www.gnu.org/copyleft/gpl.html.**  This script is distributed in the hope that it will be useful,*  but WITHOUT ANY WARRANTY; without even the implied warranty of*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the*  GNU General Public License for more details.**  This copyright notice MUST APPEAR in all copies of the script!***************************************************************//** * Extension configuration manager * * @package SavLibraryPlus * @version $ID:$ */class Tx_SavLibraryPlus_Managers_ExtensionConfigurationManager extends Tx_SavLibraryPlus_Managers_AbstractManager {  /**   * Constants associated with the flag showNoAvailableInformation   */  const SHOW_MESSAGE = 0;  const DO_NOT_SHOW_MESSAGE = 1;	  const DO_NOT_SHOW_EXTENSION = 2;	  /**   * The form name hash algorithm   *   * @var string   */	  protected static $formNameHashAlgorithm = 'crc32'; // Default hash algorithm for form name  /**   * The extension class   *   * @var tslib_pibase   */  private $extension;      /**   * The TypoScript configuration   *   * @var array   */  private static $typoScriptConfiguration;  /**   * The extension configuration   *   * @var array   */  private $extensionConfiguration;  /**   * The content object   *   * @var tslib_cObj   */  private static $extensionContentObject;  /**   * The extension key   *   * @var string   */  private static $extensionKey;    /**   * Post-processing after controller injection   *   * @return none   */  protected function postProcessingAfterControllerInjection() {  }    /**   * Injects the extension   *   * @param array $extensionConfiguration   *   * @return none   */  public function injectExtension($extension) {    $this->extension = $extension;		    // Sets the extension content object as a static variable    self::$extensionContentObject = $this->extension->cObj;        // Sets the extension key    self::$extensionKey = $this->extension->extKey;      }  /**   * Injects the TypoScript configuration   *   * @param array $typoScriptConfiguration   *   * @return none   */  public function injectTypoScriptConfiguration($typoScriptConfiguration) {    self::$typoScriptConfiguration = $typoScriptConfiguration;    // Sets the extension configuration    $this->setExtensionConfiguration();  }  /**   * Gets the extension.   *   * @param none   *   * @return tslib_pibase   */  public function getExtension() {    return $this->extension;  }    /**   * Gets the extension key.   *   * @param none   *   * @return string   */  public static function getExtensionKey() {    return self::$extensionKey;  }  /**   * Gets the extension prefix id.   *   * @param none   *   * @return string   */  public function getExtensionPrefixId() {    return $this->extension->prefixId;  }  /**   * Gets the extension content object.   *   * @param none   *   * @return tslib_cObj	 */  public static function getExtensionContentObject() {  	return self::$extensionContentObject;  }  /**   * Gets the TypoScript configuration.   *   * @param none   *   * @return array	 */  public static function getTypoScriptConfiguration() {  	if (is_array(self::$typoScriptConfiguration)) {  		return self::$typoScriptConfiguration;  	} else {  		return NULL;  	}  }      /**   * Gets the content identifier.   *   * @param none   *   * @return integer   */  public function getContentIdentifier() {    return self::getExtensionContentObject()->data['uid'];  }      /**   * Gets TS Config plugin name.   *   * @param none   *   * @return string   */  public function getTSconfigPluginName() {    return 'tx_' . str_replace('_', '', self::getExtensionKey());  } /**   *  Sets the extension configuration   *   * @param none   *   * @return boolean   */  public function setExtensionConfiguration() {        // Gets the extension configuration from the flexform    $extensionConfigurationFromFlexform = array();    $this->getExtension()->pi_initPIflexForm();    if (!isset(self::getExtensionContentObject()->data['pi_flexform']['data'])) {      return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectExtensionConfiguration', array(self::getExtensionKey()));    }        foreach (self::getExtensionContentObject()->data['pi_flexform']['data'] as $sheetKey => $sheet) {      foreach ($sheet['lDEF'] as $attributeKey => $attribute) {  		  $extensionConfigurationFromFlexform[$attributeKey] = $this->getExtension()->pi_getFFvalue(          self::getExtensionContentObject()->data['pi_flexform'],          $attributeKey,          $sheetKey        );      }    }    // Merges the TypoScript configuration with the configuration from the flexform    $this->extensionConfiguration = array_merge(self::$typoScriptConfiguration, $extensionConfigurationFromFlexform);		// Adds the form name hash algorithm		$formNameHashAlgorithm = $this->getExtensionConfigurationItem('formNameHashAlgo');		if(empty($formNameHashAlgorithm) === false) {      self::$formNameHashAlgorithm = $formNameHashAlgorithm;    }    return true;  }  /**   * Gets extension configuration item   *   * @param $itemKey string   *   * @return mixed   */  public function getExtensionConfigurationItem($itemKey) {    return $this->extensionConfiguration[$itemKey];  }     /**   * Gets the form identifier.   *   * @param none   *   * @return string   */  public function getFormIdentifier() {    return $this->getExtensionConfigurationItem('formId');  }  /**   * Gets the maxItems.   *   * @param none   *   * @return string   */  public function getMaxItems() {    return $this->getExtensionConfigurationItem('maxItems');  }  /**   * Gets the form name hash algorithm.   *   * @param none   *   * @return string   */  public function getFormNameHashAlgorithm() {    return self::$formNameHashAlgorithm;  }  /**   * Gets the storage page.   *   * @param none   *   * @return string   */  public function getStoragePage() {    return $this->getExtensionConfigurationItem('storagePage');  }  /**   * Gets the flag "noFilterShowAll".   *   * @param none   *   * @return boolean   */  public function getShowAllIfNoFilter() {    return $this->getExtensionConfigurationItem('noFilterShowAll');  }  /**   * Gets the flag "showNoAvailableInformation".   *   * @param none   *   * @return integer   */  public function getShowNoAvailableInformation() {    return $this->getExtensionConfigurationItem('showNoAvailableInformation');  }      /**   * Gets the flag "permanentFilter".   *   * @param none   *   * @return boolean   */  public function getPermanentFilter() {    return $this->getExtensionConfigurationItem('permanentFilter');  }  /**   * Gets the flag "inputIsAllowed".   *   * @param none   *   * @return string   */  public function getInputIsAllowed() {    return $this->getExtensionConfigurationItem('inputIsAllowed');  }    /**   * Gets the flag "noNewButton".   *   * @param none   *   * @return string   */  public function getNoNewButton() {    return $this->getExtensionConfigurationItem('noNewButton');  }  /**   * Gets the flag "noEditButton".   *   * @param none   *   * @return string   */  public function getNoEditButton() {    return $this->getExtensionConfigurationItem('noEditButton');  }  /**   * Gets the flag "noDeleteButton".   *   * @param none   *   * @return string   */  public function getNoDeleteButton() {    return $this->getExtensionConfigurationItem('noDeleteButton');  }  /**   * Gets the flag "deleteButtonOnlyForCruser".   *   * @param none   *   * @return string   */  public function getDeleteButtonOnlyForCreationUser() {    return $this->getExtensionConfigurationItem('deleteButtonOnlyForCreationUser');  }  /**   * Gets the field "inputStartDate".   *   * @param none   *   * @return integer   */  public function getInputStartDate() {    return $this->getExtensionConfigurationItem('inputStartDate');  }  /**   * Gets the field "inputStopDate".   *   * @param none   *   * @return integer   */  public function getInputEndDate() {    return $this->getExtensionConfigurationItem('inputEndDate');  }  /**   * Gets the field "dateUserRestriction".   *   * @param none   *   * @return integer   */  public function getDateUserRestriction() {    return $this->getExtensionConfigurationItem('dateUserRestriction');  }  /**   * Gets the field "allowedGroups".   *   * @param none   *   * @return integer   */  public function getAllowedGroups() {    return $this->getExtensionConfigurationItem('allowedGroups');  }    /**   * Gets the field "maxPages" (maximum number of pages to display in the browser).   *   * @param none   *   * @return integer   */  public function getMaxPages() {    if ($this->getExtensionConfigurationItem('maxPages')) {      return $this->getExtensionConfigurationItem('maxPages');    } else {      return 10;    }  }    /**   * Gets the field "inputAdminField".   *   * @param none   *   * @return integer   */  public function getInputAdminField() {    return $this->getExtensionConfigurationItem('inputAdminField');  }  /**   * Gets the field "allowQueryProperty".   *   * @param none   *   * @return boolean   */  public function getAllowQueryProperty() {    return $this->getExtensionConfigurationItem('allowQueryProperty');  }  /**   * Gets the field "allowExec".   *   * @param none   *   * @return boolean   */  public function getAllowExec() {    return $this->getExtensionConfigurationItem('allowExec');  }        /**   * Gets the help page for the list view.   *   * @param none   *   * @return integer   */  public function getHelpPageForListView() {    return $this->getExtensionConfigurationItem('helpPageListView');  }    /**   * Gets the help page for the single view.   *   * @param none   *   * @return integer   */  public function getHelpPageForSingleView() {    return $this->getExtensionConfigurationItem('helpPageSingleView');  }  /**   * Gets the help page for the edit view.   *   * @param none   *   * @return integer   */  public function getHelpPageForEditView() {    return $this->getExtensionConfigurationItem('helpPageEditView');  }  /**   * Gets the default date format from the extension TypoScript configuration if any.   *   * @param none   *   * @return string   */  public static function getDefaultDateFormat() {  	$typoScriptConfiguration = self::getTypoScriptConfiguration();  	if ($typoScriptConfiguration !== NULL) {  				// Gets the TypoScript associated with the form name if any			$formTitle = Tx_SavLibraryPlus_Managers_FormConfigurationManager::getFormTitle() . '.';			if (is_array($typoScriptConfiguration[$formTitle])) {				$format = $typoScriptConfiguration[$formTitle]['format.'];			} else {				$format = $typoScriptConfiguration['format.'];			}			// Processes the format	  	if (is_array($format) && empty($format['date']) === false) {	  		return $format['date'];	  	}  	} 	  return NULL;  }   /**   * Gets the default dateTime format from the extension TypoScript configuration if any.   *   * @param none   *   * @return string   */  public static function getDefaultDateTimeFormat() {   	$typoScriptConfiguration = self::getTypoScriptConfiguration();  	if ($typoScriptConfiguration !== NULL) {  	 				// Gets the TypoScript associated with the form name if any			$formTitle = Tx_SavLibraryPlus_Managers_FormConfigurationManager::getFormTitle() . '.';			if (is_array($typoScriptConfiguration[$formTitle])) {				$format = $typoScriptConfiguration[$formTitle]['format.'];			} else {				$format = $typoScriptConfiguration['format.'];			}			// Processes the format	  	if (is_array($format) && empty($format['dateTime']) === false) {	  		return $format['dateTime'];	  	}  	} 	  return NULL;  }   /**   * Sets the view configuration files from the page TypoScript configuration   *   * @param none   *   * @return none   */  public function setViewConfigurationFilesFromTypoScriptConfiguration() {  	  	// Gets the viewer  	$viewer = $this->getController()->getViewer();    if ($viewer === NULL) {			return;		}   		// Gets the extension TypoScript configuration  		$extensionTypoScriptConfiguration = self::getTypoScriptConfiguration();		if ($extensionTypoScriptConfiguration === NULL) {			return;		}		   		// Gets the form title		$formTitle = Tx_SavLibraryPlus_Managers_FormConfigurationManager::getFormTitle() . '.';   		  		// Initializes the TypoScript configuration  		$typoScriptConfiguration = $extensionTypoScriptConfiguration;			// Sets the template root path if any		if (is_array($extensionTypoScriptConfiguration[$formTitle])) {			$typoScriptConfiguration = $extensionTypoScriptConfiguration[$formTitle];		}		$templateRootPath = $typoScriptConfiguration['templateRootPath'];		if (empty($templateRootPath) === false) {			$viewer->setTemplateRootPath($templateRootPath);		}						// Sets the partial root path if any  	if (is_array($extensionTypoScriptConfiguration[$formTitle])) {			$typoScriptConfiguration = $extensionTypoScriptConfiguration[$formTitle];		}	  	$viewType = t3lib_div::lcfirst($viewer->getViewType()) . '.';		if (is_array($typoScriptConfiguration[$viewType])){			$partialRootPath = $typoScriptConfiguration[$viewType]['partialRootPath'];		} else {			$partialRootPath = $typoScriptConfiguration['partialRootPath'];		}					if (empty($partialRootPath) === false) {			$viewer->setPartialRootPath($partialRootPath);		}							// Sets the layout root path if any  	if (is_array($extensionTypoScriptConfiguration[$formTitle])) {			$typoScriptConfiguration = $extensionTypoScriptConfiguration[$formTitle];		}				$layoutRootPath = $typoScriptConfiguration['layoutRootPath'];		if (empty($layoutRootPath) === false) {			$viewer->setLayoutRootPath($layoutRootPath);		}			   				  }     /**   * Gets the view configuration field from the page TypoScript configuration   *   * @param string $fieldName   *   * @return array   */  public function getViewConfigurationFieldFromTypoScriptConfiguration($fieldName) {  	// Gets the TypoScript configuration  		$typoScriptConfiguration = $this->getTypoScriptConfiguration();		if ($typoScriptConfiguration === NULL) {			return;		}    // Gets the viewer  	$viewer = $this->getController()->getViewer();    if ($viewer === NULL) {			return;		}				    // Gets the plugin TypoScript configuration    $formTypoScriptConfiguration = $typoScriptConfiguration[Tx_SavLibraryPlus_Managers_FormConfigurationManager::getFormTitle() . '.'];     if (is_array($formTypoScriptConfiguration) === false) {    	return NULL;    } 							// Gets the view page TypoScript configuration    	$viewType = t3lib_div::lcfirst($viewer->getViewType()) . '.';				$viewTypoScriptConfiguration = $formTypoScriptConfiguration[$viewType];		if ($viewTypoScriptConfiguration === NULL) {			return NULL;		}	    // Processes the view configuration fields    $viewConfigurationFields = $viewTypoScriptConfiguration['fields.'];           // Processes the field name    $fieldNameParts = explode('.', $fieldName);    $tableNameWithDot = $fieldNameParts[0] . '.';    $fieldNameWithDot = $fieldNameParts[1] . '.'; 	    // Checks if the field is in the main table    $querier = $this->getController()->getQuerier();    if ($querier !== NULL) {    	$isMainTableField = $querier->getQueryConfigurationManager()->getMainTable() == $fieldNameParts[0];    } else {    	$isMainTableField = false;    }    	    // Builds the view field attributes configuration    if ($isMainTableField && is_array($viewConfigurationFields[$fieldNameWithDot])) {    	$viewConfigurationFieldAttributes = $viewConfigurationFields[$fieldNameWithDot];    } elseif (is_array($viewConfigurationFields[$tableNameWithDot][$fieldNameWithDot])) {    	$viewConfigurationFieldAttributes = $viewConfigurationFields[$tableNameWithDot][$fieldNameWithDot];    		    } else {    	return NULL;    }		// Processes the field attributes    $fieldAttributes = array();    foreach($viewConfigurationFieldAttributes as $viewConfigurationFieldAttributeKey => $viewConfigurationFieldAttribute) {    	$fieldAttributes[strtolower($viewConfigurationFieldAttributeKey)] = $viewConfigurationFieldAttribute;    }    return $fieldAttributes;  }      /**   * Checks if the plugin type is USER.   *   * @param none   *   * @return boolean   */  public static function isUserPlugin() {  	$contentObject = self::getExtensionContentObject(); 	    return ($contentObject->getUserObjectType() == tslib_cObj::OBJECTTYPE_USER);   }  /**   * Checks if a cHash is required.   *   * @param none   *   * @return boolean   */  public static function isCacheHashRequired() {	    return self::isUserPlugin();   }  }?>