<?php

$extensionClassesPath = t3lib_extMgm::extPath('sav_library_plus') . 'Classes/';
return array(

	'tx_savlibraryplus_datepicker_datepicker' => $extensionClassesPath . 'DatePicker/DatePicker.php',

	'tx_savlibraryplus_managers_abstractmanager' => $extensionClassesPath . 'Managers/AbstractManager.php',
	'tx_savlibraryplus_managers_additionalheadermanager' => $extensionClassesPath . 'Managers/AdditionalHeaderManager.php',
	'tx_savlibraryplus_managers_extensionconfigurationmanager' => $extensionClassesPath . 'Managers/ExtensionConfigurationManager.php',
	'tx_savlibraryplus_managers_fieldconfigurationmanager' => $extensionClassesPath . 'Managers/FieldConfigurationManager.php',
	'tx_savlibraryplus_managers_formconfigurationmanager' => $extensionClassesPath . 'Managers/FormConfigurationManager.php',
	'tx_savlibraryplus_managers_libraryconfigurationmanager' => $extensionClassesPath . 'Managers/LibraryConfigurationManager.php',
	'tx_savlibraryplus_managers_tcaconfigurationmanager' => $extensionClassesPath . 'Managers/TcaConfigurationManager.php',
	'tx_savlibraryplus_managers_templateconfigurationmanager' => $extensionClassesPath . 'Managers/TemplateConfigurationManager.php',
	'tx_savlibraryplus_managers_pagetyposcriptconfigurationmanager' => $extensionClassesPath . 'Managers/PageTypoScriptConfigurationManager.php',
	'tx_savlibraryplus_managers_queryconfigurationmanager' => $extensionClassesPath . 'Managers/QueryConfigurationManager.php',
	'tx_savlibraryplus_managers_sessionmanager' => $extensionClassesPath . 'Managers/SessionManager.php',
  'tx_savlibraryplus_managers_urimanager' => $extensionClassesPath . 'Managers/UriManager.php',
	'tx_savlibraryplus_managers_usermanager' => $extensionClassesPath . 'Managers/UserManager.php',

	'tx_savlibraryplus_controller_abstractcontroller' => $extensionClassesPath . 'Controller/AbstractController.php',
	'tx_savlibraryplus_controller_flashmessages' => $extensionClassesPath . 'Controller/FlashMessages.php',
	
	'tx_savlibraryplus_exception' => $extensionClassesPath . 'Exception.php',

	'tx_savlibraryplus_filters_abstractfilter' => $extensionClassesPath . 'Filters/AbstractFilter.php',
	
	'tx_savlibraryplus_itemviewers_default_abstractitemviewer' => $extensionClassesPath . 'ItemViewers/Default/AbstractItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_checkboxitemviewer' => $extensionClassesPath . 'ItemViewers/Default/CheckboxItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_checkboxesitemviewer' => $extensionClassesPath . 'ItemViewers/Default/CheckboxesItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_dateitemviewer' => $extensionClassesPath . 'ItemViewers/Default/DateItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_datetimeitemviewer' => $extensionClassesPath . 'ItemViewers/Default/DateTimeItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_filesitemviewer' => $extensionClassesPath . 'ItemViewers/Default/FilesItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_graphitemviewer' => $extensionClassesPath . 'ItemViewers/Default/GraphItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_integeritemviewer' => $extensionClassesPath . 'ItemViewers/Default/IntegerItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_linkitemviewer' => $extensionClassesPath . 'ItemViewers/Default/LinkItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_selectorboxitemviewer' => $extensionClassesPath . 'ItemViewers/Default/SelectorboxItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_showonlyitemviewer' => $extensionClassesPath . 'ItemViewers/Default/ShowOnlyItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_stringitemviewer' => $extensionClassesPath . 'ItemViewers/Default/StringItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_radiobuttonsitemviewer' => $extensionClassesPath . 'ItemViewers/Default/RadioButtonsItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_relationmanytomanyasdoubleselectorboxitemviewer' => $extensionClassesPath . 'ItemViewers/Default/RelationManyToManyAsDoubleSelectorboxItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_relationmanytomanyassubformitemviewer' => $extensionClassesPath . 'ItemViewers/Default/RelationManyToManyAsSubformItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_relationonetomanyasselectorboxitemviewer' => $extensionClassesPath . 'ItemViewers/Default/RelationOneToManyAsSelectorboxItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_richtexteditoritemviewer' => $extensionClassesPath . 'ItemViewers/Default/RichTextEditorItemViewer.php',
	'tx_savlibraryplus_itemviewers_default_textitemviewer' => $extensionClassesPath . 'ItemViewers/Default/TextItemViewer.php',

	'tx_savlibraryplus_itemviewers_edit_abstractitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/AbstractItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_checkboxitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/CheckboxItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_checkboxesitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/CheckboxesItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_dateitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/DateItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_datetimeitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/DateTimeItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_filesitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/FilesItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_graphitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/GraphItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_integeritemviewer' => $extensionClassesPath . 'ItemViewers/Edit/IntegerItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_linkitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/LinkItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_selectorboxitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/SelectorboxItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_showonlyitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/ShowOnlyItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_stringitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/StringItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_radiobuttonsitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/RadioButtonsItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_relationmanytomanyasdoubleselectorboxitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/RelationManyToManyAsDoubleSelectorboxItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_relationmanytomanyassubformitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/RelationManyToManyAsSubformItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_relationonetomanyasselectorboxitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/RelationOneToManyAsSelectorboxItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_richtexteditoritemviewer' => $extensionClassesPath . 'ItemViewers/Edit/RichTextEditorItemViewer.php',
	'tx_savlibraryplus_itemviewers_edit_textitemviewer' => $extensionClassesPath . 'ItemViewers/Edit/TextItemViewer.php',

	'tx_savlibraryplus_queriers_abstractquerier' => $extensionClassesPath . 'Queriers/AbstractQuerier.php',
	'tx_savlibraryplus_queriers_deletequerier' => $extensionClassesPath . 'Queriers/DeleteQuerier.php',
	'tx_savlibraryplus_queriers_deleteinsubformquerier' => $extensionClassesPath . 'Queriers/DeleteInSubformQuerier.php',
	'tx_savlibraryplus_queriers_downinsubformquerier' => $extensionClassesPath . 'Queriers/DownInSubformQuerier.php',
	'tx_savlibraryplus_queriers_editselectquerier' => $extensionClassesPath . 'Queriers/EditSelectQuerier.php',
	'tx_savlibraryplus_queriers_exportselectquerier' => $extensionClassesPath . 'Queriers/ExportSelectQuerier.php',
	'tx_savlibraryplus_queriers_exportdeleteconfigurationselectquerier' => $extensionClassesPath . 'Queriers/ExportDeleteConfigurationSelectQuerier.php',
	'tx_savlibraryplus_queriers_exportexecuteselectquerier' => $extensionClassesPath . 'Queriers/ExportExecuteSelectQuerier.php',
	'tx_savlibraryplus_queriers_exportloadconfigurationselectquerier' => $extensionClassesPath . 'Queriers/ExportLoadConfigurationSelectQuerier.php',
	'tx_savlibraryplus_queriers_exportsaveconfigurationselectquerier' => $extensionClassesPath . 'Queriers/ExportSaveConfigurationSelectQuerier.php',
	'tx_savlibraryplus_queriers_exporttoggledisplayselectquerier' => $extensionClassesPath . 'Queriers/ExportToggleDisplaySelectQuerier.php',
	'tx_savlibraryplus_queriers_foreigntableselectquerier' => $extensionClassesPath . 'Queriers/ForeignTableSelectQuerier.php',
	'tx_savlibraryplus_queriers_formadminselectquerier' => $extensionClassesPath . 'Queriers/FormAdminSelectQuerier.php',
	'tx_savlibraryplus_queriers_formadminupdatequerier' => $extensionClassesPath . 'Queriers/FormAdminUpdateQuerier.php',
	'tx_savlibraryplus_queriers_formselectquerier' => $extensionClassesPath . 'Queriers/FormSelectQuerier.php',
	'tx_savlibraryplus_queriers_formupdatequerier' => $extensionClassesPath . 'Queriers/FormUpdateQuerier.php',
	'tx_savlibraryplus_queriers_listselectquerier' => $extensionClassesPath . 'Queriers/ListSelectQuerier.php',
	'tx_savlibraryplus_queriers_listineditmodeselectquerier' => $extensionClassesPath . 'Queriers/ListInEditModeSelectQuerier.php',
	'tx_savlibraryplus_queriers_newselectquerier' => $extensionClassesPath . 'Queriers/NewSelectQuerier.php',
	'tx_savlibraryplus_queriers_newinsubformselectquerier' => $extensionClassesPath . 'Queriers/NewInSubformSelectQuerier.php',
	'tx_savlibraryplus_queriers_singleselectquerier' => $extensionClassesPath . 'Queriers/SingleSelectQuerier.php',
	'tx_savlibraryplus_queriers_upinsubformquerier' => $extensionClassesPath . 'Queriers/UpInSubformQuerier.php',
	'tx_savlibraryplus_queriers_updatequerier' => $extensionClassesPath . 'Queriers/UpdateQuerier.php',
	
	'tx_savlibraryplus_utility_conditions' => $extensionClassesPath . 'Utility/Conditions.php',
	'tx_savlibraryplus_utility_htmlelements' => $extensionClassesPath . 'Utility/HtmlElements.php',

	'tx_savlibraryplus_viewers_abstractviewer' => $extensionClassesPath . 'Viewers/AbstractViewer.php',
	'tx_savlibraryplus_viewers_editviewer' => $extensionClassesPath . 'Viewers/EditViewer.php',
	'tx_savlibraryplus_viewers_exportviewer' => $extensionClassesPath . 'Viewers/ExportViewer.php',
	'tx_savlibraryplus_viewers_exportdeleteconfigurationviewer' => $extensionClassesPath . 'Viewers/ExportDeleteConfigurationViewer.php',
	'tx_savlibraryplus_viewers_exportexecuteviewer' => $extensionClassesPath . 'Viewers/ExportExecuteViewer.php',
	'tx_savlibraryplus_viewers_exportloadconfigurationviewer' => $extensionClassesPath . 'Viewers/ExportLoadConfigurationViewer.php',
	'tx_savlibraryplus_viewers_exportsaveconfigurationviewer' => $extensionClassesPath . 'Viewers/ExportSaveConfigurationViewer.php',
	'tx_savlibraryplus_viewers_exporttoggledisplayviewer' => $extensionClassesPath . 'Viewers/ExportToggleDisplayViewer.php',
	'tx_savlibraryplus_viewers_errorviewer' => $extensionClassesPath . 'Viewers/ErrorViewer.php',
	'tx_savlibraryplus_viewers_formviewer' => $extensionClassesPath . 'Viewers/FormViewer.php',
	'tx_savlibraryplus_viewers_formadminviewer' => $extensionClassesPath . 'Viewers/FormAdminViewer.php',
	'tx_savlibraryplus_viewers_listviewer' => $extensionClassesPath . 'Viewers/ListViewer.php',
	'tx_savlibraryplus_viewers_listineditmodeviewer' => $extensionClassesPath . 'Viewers/ListInEditModeViewer.php',
	'tx_savlibraryplus_viewers_newviewer' => $extensionClassesPath . 'Viewers/NewViewer.php',
	'tx_savlibraryplus_viewers_newinsubformviewer' => $extensionClassesPath . 'Viewers/NewInSubformViewer.php',
	'tx_savlibraryplus_viewers_singleviewer' => $extensionClassesPath . 'Viewers/SingleViewer.php',
	'tx_savlibraryplus_viewers_subformeditviewer' => $extensionClassesPath . 'Viewers/SubformEditViewer.php',
	'tx_savlibraryplus_viewers_subformsingleviewer' => $extensionClassesPath . 'Viewers/SubformSingleViewer.php',
);
?>
