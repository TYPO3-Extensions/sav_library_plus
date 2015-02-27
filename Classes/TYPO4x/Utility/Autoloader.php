<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2011 Laurent Foulloy <yolf.typo3@orange.fr>
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
 * Autoloader
 *
 * @package SavLibraryPlus
 * @version $ID:$
 */

class Tx_SavLibraryPlus_Utility_Autoloader {
  
  protected static $classAliases = array(
    // CMS
  	'TYPO3\\CMS\\Backend\\Utility\\BackendUtility' => 't3lib_BEfunc', 
    'TYPO3\\CMS\\Frontend\\Plugin\\AbstractPlugin' => 'tslib_pibase', 
  	'TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer' => 'tslib_cObj',
  	'TYPO3\\CMS\\Core\\Messaging\\FlashMessage' => 't3lib_FlashMessage',
  	'TYPO3\\CMS\\Core\\Messaging\\FlashMessageQueue' => 't3lib_FlashMessageQueue',
		'TYPO3\\CMS\\Frontend\\Page\\PageRepository' => 't3lib_pageSelect',
    'TYPO3\\CMS\\Core\\TypoScript\\Parser\\TypoScriptParser' => 't3lib_TSparser',  
  	'TYPO3\\CMS\\Core\\Utility\\ExtensionManagementUtility' => 't3lib_extMgm',
  	'TYPO3\\CMS\\Core\\Utility\\GeneralUtility' => 't3lib_div',  
    // Fluid
  	'TYPO3\\CMS\\Fluid\\Core\\ViewHelper\\AbstractTagBasedViewHelper' => 'Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper',
  	'TYPO3\\CMS\\Fluid\\Core\\ViewHelper\\AbstractViewHelper' => 'Tx_Fluid_Core_ViewHelper_AbstractViewHelper',
  	'TYPO3\\CMS\\Fluid\\View\\StandaloneView' => 'Tx_Fluid_View_StandaloneView',
  	'TYPO3\\CMS\\Fluid\\ViewHelpers\\FlashMessagesViewHelper' => 'Tx_Fluid_ViewHelpers_FlashMessagesViewHelper',
  	'TYPO3\\CMS\\Fluid\\ViewHelpers\\FormViewHelper' => 'Tx_Fluid_ViewHelpers_FormViewHelper',
  	'TYPO3\\CMS\\Fluid\\ViewHelpers\\Form\\AbstractFormFieldViewHelper' => 'Tx_Fluid_ViewHelpers_Form_AbstractFormFieldViewHelper',
    // Extbase
  	'TYPO3\\CMS\\Extbase\\Utility\\LocalizationUtility' => 'Tx_Extbase_Utility_Localization',
    // RTE
  	'TYPO3\\CMS\\Rtehtmlarea\\Controller\\FrontendRteController' => 'tx_rtehtmlarea_pi2',
    // SAV Library Plus - Compatibility  
  	'SAV\\SavLibraryPlus\\Compatibility\\Database\\DatabaseConnection' => 'Tx_SavLibraryPlus_Compatibility_Database_DatabaseConnection',
  	'SAV\\SavLibraryPlus\\Compatibility\\Utility\\GeneralUtility' => 'Tx_SavLibraryPlus_Compatibility_Utility_GeneralUtility',
  	'SAV\\SavLibraryPlus\\Compatibility\\View\\StandaloneView' => 'Tx_SavLibraryPlus_Compatibility_View_StandaloneView',
    // SAV Library Plus - Controller
  	'SAV\\SavLibraryPlus\\Controller\\AbstractController' => 'Tx_SavLibraryPlus_Controller_AbstractController', 
  	'SAV\\SavLibraryPlus\\Controller\\Controller' => 'Tx_SavLibraryPlus_Controller_Controller', 
  	'SAV\\SavLibraryPlus\\Controller\\FlashMessages' => 'Tx_SavLibraryPlus_Controller_FlashMessages', 
    // SAV Library Plus - Datepicker    
  	'SAV\\SavLibraryPlus\\DatePicker\\DatePicker' => 'Tx_SavLibraryPlus_DatePicker_DatePicker',
    // SAV Library Plus - Exception   
  	'SAV\\SavLibraryPlus\\Exception' => 'Tx_SavLibraryPlus_Exception',
    // SAV Library Plus - Filter   
  	'SAV\\SavLibraryPlus\\Filters\\AbstractFilter' => 'Tx_SavLibraryPlus_Filters_AbstractFilter',
    // SAV Library Plus - General itemviewers   	
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\AbstractItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_AbstractItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\CheckboxItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_CheckboxItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\CheckboxesItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_CheckboxesItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\CurrencyItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_CurrencyItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\DateItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_DateItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\DateTimeItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_DateTimeItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\FilesItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_FilesItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\GraphItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_GraphItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\IntegerItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_IntegerItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\LinkItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_LinkItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\SelectorboxItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_SelectorboxItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\ShowOnlyItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_ShowOnlyItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\RadioButtonsItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_RadioButtonsItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\RelationManyToManyAsDoubleSelectorboxItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_RelationManyToManyAsDoubleSelectorboxItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\RelationManyToManyAsSubformItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_RelationManyToManyAsSubformItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\RelationOneToManyAsSelectorboxItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_RelationOneToManyAsSelectorboxItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\RichTextEditorItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_RichTextEditorItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\StringItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_StringItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\General\\TextItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_General_TextItemViewer',
    // SAV Library Plus - Edit itemviewers   
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\AbstractItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_AbstractItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\CheckboxItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_CheckboxItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\CheckboxesItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_CheckboxesItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\CurrencyItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_CurrencyItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\DateItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_DateItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\DateTimeItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_DateTimeItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\FilesItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_FilesItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\GraphItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_GraphItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\IntegerItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_IntegerItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\LinkItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_LinkItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\SelectorboxItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_SelectorboxItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\ShowOnlyItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_ShowOnlyItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\RadioButtonsItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_RadioButtonsItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\RelationManyToManyAsDoubleSelectorboxItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_RelationManyToManyAsDoubleSelectorboxItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\RelationManyToManyAsSubformItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_RelationManyToManyAsSubformItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\RelationOneToManyAsSelectorboxItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_RelationOneToManyAsSelectorboxItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\RichTextEditorItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_RichTextEditorItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\StringItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_StringItemViewer',
  	'SAV\\SavLibraryPlus\\ItemViewers\\Edit\\TextItemViewer' => 'Tx_SavLibraryPlus_ItemViewers_Edit_TextItemViewer',
    // SAV Library Plus - Managers   
  	'SAV\\SavLibraryPlus\\Managers\\AbstractManager' => 'Tx_SavLibraryPlus_Managers_AbstractManager', 
  	'SAV\\SavLibraryPlus\\Managers\\AdditionalHeaderManager' => 'Tx_SavLibraryPlus_Managers_AdditionalHeaderManager', 
  	'SAV\\SavLibraryPlus\\Managers\\ExtensionConfigurationManager' => 'Tx_SavLibraryPlus_Managers_ExtensionConfigurationManager', 
  	'SAV\\SavLibraryPlus\\Managers\\FieldConfigurationManager' => 'Tx_SavLibraryPlus_Managers_FieldConfigurationManager', 
  	'SAV\\SavLibraryPlus\\Managers\\FormConfigurationManager' => 'Tx_SavLibraryPlus_Managers_FormConfigurationManager', 
  	'SAV\\SavLibraryPlus\\Managers\\LibraryConfigurationManager' => 'Tx_SavLibraryPlus_Managers_LibraryConfigurationManager', 
  	'SAV\\SavLibraryPlus\\Managers\\TcaConfigurationManager' => 'Tx_SavLibraryPlus_Managers_TcaConfigurationManager', 
  	'SAV\\SavLibraryPlus\\Managers\\TemplateConfigurationManager' => 'Tx_SavLibraryPlus_Managers_TemplateConfigurationManager', 
  	'SAV\\SavLibraryPlus\\Managers\\PageTypoScriptConfigurationManager' => 'Tx_SavLibraryPlus_Managers_PageTypoScriptConfigurationManager', 
  	'SAV\\SavLibraryPlus\\Managers\\QueryConfigurationManager' => 'Tx_SavLibraryPlus_Managers_QueryConfigurationManager', 
  	'SAV\\SavLibraryPlus\\Managers\\SessionManager' => 'Tx_SavLibraryPlus_Managers_SessionManager', 
  	'SAV\\SavLibraryPlus\\Managers\\UriManager' => 'Tx_SavLibraryPlus_Managers_UriManager', 
  	'SAV\\SavLibraryPlus\\Managers\\UserManager' => 'Tx_SavLibraryPlus_Managers_UserManager', 
    // SAV Library Plus - Queriers   
  	'SAV\\SavLibraryPlus\\Queriers\\AbstractQuerier' => 'Tx_SavLibraryPlus_Queriers_AbstractQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\DeleteInSubformQuerier' => 'Tx_SavLibraryPlus_Queriers_DeleteInSubformQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\DeleteQuerier' => 'Tx_SavLibraryPlus_Queriers_DeleteQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\DownInSubformQuerier' => 'Tx_SavLibraryPlus_Queriers_DownInSubformQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\EditSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_EditSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\ExportSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_ExportSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\ExportDeleteConfigurationSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_ExportDeleteConfigurationSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\ExportExecuteSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_ExportExecuteSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\ExportLoadConfigurationSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_ExportLoadConfigurationSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\ExportQueryModeSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_ExportQueryModeSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\ExportSaveConfigurationSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_ExportSaveConfigurationSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\ExportToggleDisplaySelectQuerier' => 'Tx_SavLibraryPlus_Queriers_ExportToggleDisplaySelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\ForeignTableSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_ForeignTableSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\FormSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_FormSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\FormAdminSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_FormAdminSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\ListInEditModeSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_ListInEditModeSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\ListSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_ListSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\NewInSubformSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_NewInSubformSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\NewSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_NewSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\PrintInListSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_PrintInListSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\PrintInSingleSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_PrintInSingleSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\SingleSelectQuerier' => 'Tx_SavLibraryPlus_Queriers_SingleSelectQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\UpInSubformQuerier' => 'Tx_SavLibraryPlus_Queriers_UpInSubformQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\UpdateQuerier' => 'Tx_SavLibraryPlus_Queriers_UpdateQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\FormAdminUpdateQuerier' => 'Tx_SavLibraryPlus_Queriers_FormAdminUpdateQuerier',
  	'SAV\\SavLibraryPlus\\Queriers\\FormUpdateQuerier' => 'Tx_SavLibraryPlus_Queriers_FormUpdateQuerier',
    // SAV Library Plus - Utility   	
  	'SAV\\SavLibraryPlus\\Utility\\Conditions' => 'Tx_SavLibraryPlus_Utility_Conditions',
  	'SAV\\SavLibraryPlus\\Utility\\HtmlElements' => 'Tx_SavLibraryPlus_Utility_HtmlElements',
    // SAV Library Plus - Viewers   
  	'SAV\\SavLibraryPlus\\Viewers\\AbstractDefaultRootPath' => 'Tx_SavLibraryPlus_Viewers_AbstractDefaultRootPath',
  	'SAV\\SavLibraryPlus\\Viewers\\AbstractViewer' => 'Tx_SavLibraryPlus_Viewers_AbstractViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\EditViewer' => 'Tx_SavLibraryPlus_Viewers_EditViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\ExportViewer' => 'Tx_SavLibraryPlus_Viewers_ExportViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\ExportDeleteConfigurationViewer' => 'Tx_SavLibraryPlus_Viewers_ExportDeleteConfigurationViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\ExportExecuteViewer' => 'Tx_SavLibraryPlus_Viewers_ExportExecuteViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\ExportLoadConfigurationViewer' => 'Tx_SavLibraryPlus_Viewers_ExportLoadConfigurationViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\ExportQueryModeViewer' => 'Tx_SavLibraryPlus_Viewers_ExportQueryModeViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\ExportSaveConfigurationViewer' => 'Tx_SavLibraryPlus_Viewers_ExportSaveConfigurationViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\ExportToggleDisplayViewer' => 'Tx_SavLibraryPlus_Viewers_ExportToggleDisplayViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\ErrorViewer' => 'Tx_SavLibraryPlus_Viewers_ErrorViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\FormViewer' => 'Tx_SavLibraryPlus_Viewers_FormViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\FormAdminViewer' => 'Tx_SavLibraryPlus_Viewers_FormAdminViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\ListViewer' => 'Tx_SavLibraryPlus_Viewers_ListViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\ListInEditModeViewer' => 'Tx_SavLibraryPlus_Viewers_ListInEditModeViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\NewViewer' => 'Tx_SavLibraryPlus_Viewers_NewViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\NewInSubformViewer' => 'Tx_SavLibraryPlus_Viewers_NewInSubformViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\PrintInListViewer' => 'Tx_SavLibraryPlus_Viewers_PrintInListViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\PrintInSingleViewer' => 'Tx_SavLibraryPlus_Viewers_PrintInSingleViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\SingleViewer' => 'Tx_SavLibraryPlus_Viewers_SingleViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\SubformEditViewer' => 'Tx_SavLibraryPlus_Viewers_SubformEditViewer',
  	'SAV\\SavLibraryPlus\\Viewers\\SubformSingleViewer' => 'Tx_SavLibraryPlus_Viewers_SubformSingleViewer',   
  );
  
  static public function register() {
    spl_autoload_register(array(__CLASS__, 'autoload'));
  }

  static public function autoload($class) {    
    if ((strpos($class, 'TYPO3\\') === 0 || strpos($class, 'SAV\\SavLibraryPlus') === 0) && array_key_exists($class, self::$classAliases)) {   
      class_alias(self::$classAliases[$class], $class);
    }   
  }
  
}
?>
