<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "sav_library_plus".
 *
 * Auto generated 27-02-2013 13:55
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'SAV Library Plus',
	'description' => 'The SAV Library Plus is the new SAV Library for TYPO3 4.5. Used with the SAV Library Kickstarter, it makes it possible to directly build extensions without any PHP coding, thanks to simple configuration parameters using the SAV Library Kickstarter as an extension editor. Multiple views of the data including forms can be generated.',
	'category' => 'misc',
	'author' => 'Laurent Foulloy',
	'author_email' => 'yolf.typo3@orange.fr',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.1.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-0.0.0',
			'fluid' => '',
			'extbase' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:320:{s:9:"ChangeLog";s:4:"338d";s:16:"ext_autoload.php";s:4:"51fe";s:21:"ext_conf_template.txt";s:4:"a243";s:12:"ext_icon.gif";s:4:"ed4f";s:17:"ext_localconf.php";s:4:"5490";s:14:"ext_tables.php";s:4:"7d7d";s:14:"ext_tables.sql";s:4:"d309";s:10:"README.txt";s:4:"ee2d";s:9:"Thumbs.db";s:4:"09d6";s:21:"Classes/Exception.php";s:4:"ae45";s:41:"Classes/Controller/AbstractController.php";s:4:"ae79";s:33:"Classes/Controller/Controller.php";s:4:"6929";s:36:"Classes/Controller/FlashMessages.php";s:4:"1b5a";s:28:"Classes/DatePicker/ChangeLog";s:4:"5628";s:33:"Classes/DatePicker/DatePicker.php";s:4:"30b8";s:25:"Classes/DatePicker/README";s:4:"fd96";s:40:"Classes/DatePicker/css/calendar-blue.css";s:4:"b8bb";s:41:"Classes/DatePicker/css/calendar-blue2.css";s:4:"654d";s:41:"Classes/DatePicker/css/calendar-brown.css";s:4:"f326";s:41:"Classes/DatePicker/css/calendar-green.css";s:4:"b44e";s:42:"Classes/DatePicker/css/calendar-system.css";s:4:"9b44";s:39:"Classes/DatePicker/css/calendar-tas.css";s:4:"fa3b";s:43:"Classes/DatePicker/css/calendar-win2k-1.css";s:4:"e3c6";s:43:"Classes/DatePicker/css/calendar-win2k-2.css";s:4:"332d";s:48:"Classes/DatePicker/css/calendar-win2k-cold-1.css";s:4:"b081";s:48:"Classes/DatePicker/css/calendar-win2k-cold-2.css";s:4:"3487";s:36:"Classes/DatePicker/css/menuarrow.gif";s:4:"cc11";s:36:"Classes/DatePicker/doc/reference.pdf";s:4:"ded1";s:44:"Classes/DatePicker/doc/html/field-button.jpg";s:4:"3b80";s:45:"Classes/DatePicker/doc/html/reference-Z-S.css";s:4:"4803";s:41:"Classes/DatePicker/doc/html/reference.css";s:4:"ab36";s:42:"Classes/DatePicker/doc/html/reference.html";s:4:"f27c";s:41:"Classes/DatePicker/js/calendar-handler.js";s:4:"02e4";s:50:"Classes/DatePicker/js/calendar-handler_stripped.js";s:4:"58bb";s:39:"Classes/DatePicker/js/calendar-setup.js";s:4:"529a";s:48:"Classes/DatePicker/js/calendar-setup_stripped.js";s:4:"8d0c";s:33:"Classes/DatePicker/js/calendar.js";s:4:"334b";s:42:"Classes/DatePicker/js/calendar_stripped.js";s:4:"60cc";s:38:"Classes/DatePicker/lang/calendar-af.js";s:4:"65fc";s:38:"Classes/DatePicker/lang/calendar-al.js";s:4:"87fd";s:38:"Classes/DatePicker/lang/calendar-bg.js";s:4:"3387";s:45:"Classes/DatePicker/lang/calendar-big5-utf8.js";s:4:"4b1a";s:40:"Classes/DatePicker/lang/calendar-big5.js";s:4:"8b21";s:38:"Classes/DatePicker/lang/calendar-br.js";s:4:"d8a9";s:38:"Classes/DatePicker/lang/calendar-ca.js";s:4:"e832";s:43:"Classes/DatePicker/lang/calendar-cs-utf8.js";s:4:"3530";s:42:"Classes/DatePicker/lang/calendar-cs-win.js";s:4:"894a";s:38:"Classes/DatePicker/lang/calendar-da.js";s:4:"47c7";s:38:"Classes/DatePicker/lang/calendar-de.js";s:4:"4169";s:38:"Classes/DatePicker/lang/calendar-du.js";s:4:"82ab";s:38:"Classes/DatePicker/lang/calendar-el.js";s:4:"5e3c";s:38:"Classes/DatePicker/lang/calendar-en.js";s:4:"4681";s:38:"Classes/DatePicker/lang/calendar-es.js";s:4:"22e6";s:38:"Classes/DatePicker/lang/calendar-eu.js";s:4:"9c03";s:38:"Classes/DatePicker/lang/calendar-fi.js";s:4:"f201";s:38:"Classes/DatePicker/lang/calendar-fr.js";s:4:"6164";s:43:"Classes/DatePicker/lang/calendar-he-utf8.js";s:4:"7ace";s:43:"Classes/DatePicker/lang/calendar-hr-utf8.js";s:4:"05f1";s:38:"Classes/DatePicker/lang/calendar-hr.js";s:4:"48e3";s:38:"Classes/DatePicker/lang/calendar-hu.js";s:4:"4b5e";s:38:"Classes/DatePicker/lang/calendar-it.js";s:4:"77ea";s:38:"Classes/DatePicker/lang/calendar-jp.js";s:4:"b47d";s:43:"Classes/DatePicker/lang/calendar-ko-utf8.js";s:4:"9411";s:38:"Classes/DatePicker/lang/calendar-ko.js";s:4:"882d";s:43:"Classes/DatePicker/lang/calendar-lt-utf8.js";s:4:"0a5f";s:38:"Classes/DatePicker/lang/calendar-lt.js";s:4:"a0cf";s:38:"Classes/DatePicker/lang/calendar-lv.js";s:4:"030a";s:38:"Classes/DatePicker/lang/calendar-nl.js";s:4:"f0e8";s:38:"Classes/DatePicker/lang/calendar-no.js";s:4:"d834";s:43:"Classes/DatePicker/lang/calendar-pl-utf8.js";s:4:"4247";s:38:"Classes/DatePicker/lang/calendar-pl.js";s:4:"2510";s:38:"Classes/DatePicker/lang/calendar-pt.js";s:4:"4265";s:38:"Classes/DatePicker/lang/calendar-ro.js";s:4:"6011";s:42:"Classes/DatePicker/lang/calendar-ru-UTF.js";s:4:"fbc5";s:38:"Classes/DatePicker/lang/calendar-ru.js";s:4:"3402";s:43:"Classes/DatePicker/lang/calendar-ru_win_.js";s:4:"eafc";s:38:"Classes/DatePicker/lang/calendar-si.js";s:4:"bb1e";s:38:"Classes/DatePicker/lang/calendar-sk.js";s:4:"6e55";s:38:"Classes/DatePicker/lang/calendar-sp.js";s:4:"e6e4";s:43:"Classes/DatePicker/lang/calendar-sr-utf8.js";s:4:"cf8c";s:38:"Classes/DatePicker/lang/calendar-sr.js";s:4:"6fce";s:38:"Classes/DatePicker/lang/calendar-sv.js";s:4:"c49d";s:38:"Classes/DatePicker/lang/calendar-tr.js";s:4:"52df";s:38:"Classes/DatePicker/lang/calendar-zh.js";s:4:"4e19";s:34:"Classes/DatePicker/lang/cn_utf8.js";s:4:"fb93";s:43:"Classes/DatePicker/skins/aqua/active-bg.gif";s:4:"f8fb";s:41:"Classes/DatePicker/skins/aqua/dark-bg.gif";s:4:"949f";s:42:"Classes/DatePicker/skins/aqua/hover-bg.gif";s:4:"803a";s:43:"Classes/DatePicker/skins/aqua/menuarrow.gif";s:4:"1f8c";s:43:"Classes/DatePicker/skins/aqua/normal-bg.gif";s:4:"8511";s:45:"Classes/DatePicker/skins/aqua/rowhover-bg.gif";s:4:"c097";s:43:"Classes/DatePicker/skins/aqua/status-bg.gif";s:4:"1238";s:39:"Classes/DatePicker/skins/aqua/theme.css";s:4:"ec69";s:39:"Classes/DatePicker/skins/aqua/Thumbs.db";s:4:"6539";s:42:"Classes/DatePicker/skins/aqua/title-bg.gif";s:4:"8d65";s:42:"Classes/DatePicker/skins/aqua/today-bg.gif";s:4:"9bef";s:44:"Classes/DatePicker/skins/tiger/active-bg.gif";s:4:"ba4f";s:42:"Classes/DatePicker/skins/tiger/dark-bg.gif";s:4:"8311";s:43:"Classes/DatePicker/skins/tiger/hover-bg.gif";s:4:"997d";s:44:"Classes/DatePicker/skins/tiger/menuarrow.gif";s:4:"1f8c";s:44:"Classes/DatePicker/skins/tiger/normal-bg.gif";s:4:"c821";s:46:"Classes/DatePicker/skins/tiger/rowhover-bg.gif";s:4:"7124";s:44:"Classes/DatePicker/skins/tiger/status-bg.gif";s:4:"bec6";s:40:"Classes/DatePicker/skins/tiger/theme.css";s:4:"4b87";s:40:"Classes/DatePicker/skins/tiger/Thumbs.db";s:4:"dedc";s:43:"Classes/DatePicker/skins/tiger/title-bg.gif";s:4:"ee2b";s:34:"Classes/Filters/AbstractFilter.php";s:4:"f0d5";s:50:"Classes/ItemViewers/Default/AbstractItemViewer.php";s:4:"5bb6";s:52:"Classes/ItemViewers/Default/CheckboxesItemViewer.php";s:4:"3dae";s:50:"Classes/ItemViewers/Default/CheckboxItemViewer.php";s:4:"72ca";s:46:"Classes/ItemViewers/Default/DateItemViewer.php";s:4:"19be";s:50:"Classes/ItemViewers/Default/DateTimeItemViewer.php";s:4:"1a9c";s:47:"Classes/ItemViewers/Default/FilesItemViewer.php";s:4:"6e23";s:47:"Classes/ItemViewers/Default/GraphItemViewer.php";s:4:"b6ab";s:49:"Classes/ItemViewers/Default/IntegerItemViewer.php";s:4:"a604";s:46:"Classes/ItemViewers/Default/LinkItemViewer.php";s:4:"e432";s:54:"Classes/ItemViewers/Default/RadioButtonsItemViewer.php";s:4:"4b9c";s:79:"Classes/ItemViewers/Default/RelationManyToManyAsDoubleSelectorboxItemViewer.php";s:4:"9e5f";s:69:"Classes/ItemViewers/Default/RelationManyToManyAsSubformItemViewer.php";s:4:"86ca";s:72:"Classes/ItemViewers/Default/RelationOneToManyAsSelectorboxItemViewer.php";s:4:"40f7";s:56:"Classes/ItemViewers/Default/RichTextEditorItemViewer.php";s:4:"f25c";s:53:"Classes/ItemViewers/Default/SelectorboxItemViewer.php";s:4:"4461";s:50:"Classes/ItemViewers/Default/ShowOnlyItemViewer.php";s:4:"22cf";s:48:"Classes/ItemViewers/Default/StringItemViewer.php";s:4:"5933";s:46:"Classes/ItemViewers/Default/TextItemViewer.php";s:4:"7e11";s:47:"Classes/ItemViewers/Edit/AbstractItemViewer.php";s:4:"eb61";s:49:"Classes/ItemViewers/Edit/CheckboxesItemViewer.php";s:4:"31e7";s:47:"Classes/ItemViewers/Edit/CheckboxItemViewer.php";s:4:"4400";s:43:"Classes/ItemViewers/Edit/DateItemViewer.php";s:4:"86e2";s:47:"Classes/ItemViewers/Edit/DateTimeItemViewer.php";s:4:"fc88";s:44:"Classes/ItemViewers/Edit/FilesItemViewer.php";s:4:"ead9";s:44:"Classes/ItemViewers/Edit/GraphItemViewer.php";s:4:"bcc0";s:46:"Classes/ItemViewers/Edit/IntegerItemViewer.php";s:4:"d136";s:43:"Classes/ItemViewers/Edit/LinkItemViewer.php";s:4:"269f";s:51:"Classes/ItemViewers/Edit/RadioButtonsItemViewer.php";s:4:"5ed9";s:76:"Classes/ItemViewers/Edit/RelationManyToManyAsDoubleSelectorboxItemViewer.php";s:4:"cce6";s:66:"Classes/ItemViewers/Edit/RelationManyToManyAsSubformItemViewer.php";s:4:"66db";s:69:"Classes/ItemViewers/Edit/RelationOneToManyAsSelectorboxItemViewer.php";s:4:"95ea";s:53:"Classes/ItemViewers/Edit/RichTextEditorItemViewer.php";s:4:"4059";s:50:"Classes/ItemViewers/Edit/SelectorboxItemViewer.php";s:4:"acd5";s:47:"Classes/ItemViewers/Edit/ShowOnlyItemViewer.php";s:4:"b80d";s:45:"Classes/ItemViewers/Edit/StringItemViewer.php";s:4:"3e5b";s:43:"Classes/ItemViewers/Edit/TextItemViewer.php";s:4:"79da";s:36:"Classes/Managers/AbstractManager.php";s:4:"c5b7";s:44:"Classes/Managers/AdditionalHeaderManager.php";s:4:"f98b";s:50:"Classes/Managers/ExtensionConfigurationManager.php";s:4:"ae33";s:46:"Classes/Managers/FieldConfigurationManager.php";s:4:"1b56";s:45:"Classes/Managers/FormConfigurationManager.php";s:4:"dca4";s:48:"Classes/Managers/LibraryConfigurationManager.php";s:4:"518b";s:55:"Classes/Managers/PageTypoScriptConfigurationManager.php";s:4:"89c4";s:46:"Classes/Managers/QueryConfigurationManager.php";s:4:"d7b0";s:35:"Classes/Managers/SessionManager.php";s:4:"50d0";s:44:"Classes/Managers/TcaConfigurationManager.php";s:4:"09df";s:49:"Classes/Managers/TemplateConfigurationManager.php";s:4:"0943";s:31:"Classes/Managers/UriManager.php";s:4:"ef3e";s:32:"Classes/Managers/UserManager.php";s:4:"ec48";s:36:"Classes/Queriers/AbstractQuerier.php";s:4:"d2bc";s:43:"Classes/Queriers/DeleteInSubformQuerier.php";s:4:"f07a";s:34:"Classes/Queriers/DeleteQuerier.php";s:4:"7c14";s:41:"Classes/Queriers/DownInSubformQuerier.php";s:4:"0f05";s:38:"Classes/Queriers/EditSelectQuerier.php";s:4:"dd30";s:59:"Classes/Queriers/ExportDeleteConfigurationSelectQuerier.php";s:4:"7812";s:47:"Classes/Queriers/ExportExecuteSelectQuerier.php";s:4:"e6a2";s:57:"Classes/Queriers/ExportLoadConfigurationSelectQuerier.php";s:4:"cbea";s:57:"Classes/Queriers/ExportSaveConfigurationSelectQuerier.php";s:4:"9f3f";s:40:"Classes/Queriers/ExportSelectQuerier.php";s:4:"a05d";s:53:"Classes/Queriers/ExportToggleDisplaySelectQuerier.php";s:4:"5d01";s:46:"Classes/Queriers/ForeignTableSelectQuerier.php";s:4:"60ab";s:43:"Classes/Queriers/FormAdminSelectQuerier.php";s:4:"df3b";s:43:"Classes/Queriers/FormAdminUpdateQuerier.php";s:4:"50a4";s:38:"Classes/Queriers/FormSelectQuerier.php";s:4:"4295";s:38:"Classes/Queriers/FormUpdateQuerier.php";s:4:"896f";s:48:"Classes/Queriers/ListInEditModeSelectQuerier.php";s:4:"82de";s:38:"Classes/Queriers/ListSelectQuerier.php";s:4:"85af";s:46:"Classes/Queriers/NewInSubformSelectQuerier.php";s:4:"d153";s:37:"Classes/Queriers/NewSelectQuerier.php";s:4:"8e56";s:40:"Classes/Queriers/SingleSelectQuerier.php";s:4:"d442";s:34:"Classes/Queriers/UpdateQuerier.php";s:4:"8de1";s:39:"Classes/Queriers/UpInSubformQuerier.php";s:4:"e32b";s:30:"Classes/Utility/Conditions.php";s:4:"f1b8";s:32:"Classes/Utility/HtmlElements.php";s:4:"62f9";s:41:"Classes/ViewHelpers/CommentViewHelper.php";s:4:"88c6";s:52:"Classes/ViewHelpers/CompressParametersViewHelper.php";s:4:"86dc";s:47:"Classes/ViewHelpers/FlashMessagesViewHelper.php";s:4:"f2f3";s:38:"Classes/ViewHelpers/FormViewHelper.php";s:4:"f712";s:41:"Classes/ViewHelpers/GetIconViewHelper.php";s:4:"3289";s:38:"Classes/ViewHelpers/HtmlViewHelper.php";s:4:"e46d";s:50:"Classes/ViewHelpers/RemoveEmptyLinesViewHelper.php";s:4:"9ccf";s:43:"Classes/ViewHelpers/TranslateViewHelper.php";s:4:"287b";s:44:"Classes/ViewHelpers/Form/ImageViewHelper.php";s:4:"ef81";s:45:"Classes/ViewHelpers/Link/ActionViewHelper.php";s:4:"173f";s:49:"Classes/ViewHelpers/Typoscript/WrapViewHelper.php";s:4:"69b7";s:34:"Classes/Viewers/AbstractViewer.php";s:4:"b356";s:30:"Classes/Viewers/EditViewer.php";s:4:"3061";s:31:"Classes/Viewers/ErrorViewer.php";s:4:"8f48";s:51:"Classes/Viewers/ExportDeleteConfigurationViewer.php";s:4:"fbe0";s:39:"Classes/Viewers/ExportExecuteViewer.php";s:4:"2dcf";s:49:"Classes/Viewers/ExportLoadConfigurationViewer.php";s:4:"3be0";s:49:"Classes/Viewers/ExportSaveConfigurationViewer.php";s:4:"9634";s:45:"Classes/Viewers/ExportToggleDisplayViewer.php";s:4:"456f";s:32:"Classes/Viewers/ExportViewer.php";s:4:"a019";s:35:"Classes/Viewers/FormAdminViewer.php";s:4:"97b0";s:30:"Classes/Viewers/FormViewer.php";s:4:"e095";s:40:"Classes/Viewers/ListInEditModeViewer.php";s:4:"aa6e";s:30:"Classes/Viewers/ListViewer.php";s:4:"b792";s:38:"Classes/Viewers/NewInSubformViewer.php";s:4:"a886";s:29:"Classes/Viewers/NewViewer.php";s:4:"0894";s:32:"Classes/Viewers/SingleViewer.php";s:4:"cb2a";s:37:"Classes/Viewers/SubformEditViewer.php";s:4:"45fa";s:39:"Classes/Viewers/SubformSingleViewer.php";s:4:"7dfe";s:25:"Configuration/TCA/tca.php";s:4:"d2dd";s:45:"Resources/Private/Icons/backwardFirstPage.gif";s:4:"354a";s:40:"Resources/Private/Icons/backwardPage.gif";s:4:"fe23";s:51:"Resources/Private/Icons/backwardPageNotPossible.gif";s:4:"a61b";s:36:"Resources/Private/Icons/calendar.gif";s:4:"c1e5";s:47:"Resources/Private/Icons/checkboxNotSelected.gif";s:4:"1fd8";s:44:"Resources/Private/Icons/checkboxSelected.gif";s:4:"7da5";s:33:"Resources/Private/Icons/clear.gif";s:4:"cc11";s:33:"Resources/Private/Icons/close.gif";s:4:"1443";s:34:"Resources/Private/Icons/delete.gif";s:4:"6de8";s:32:"Resources/Private/Icons/down.gif";s:4:"c27e";s:32:"Resources/Private/Icons/edit.gif";s:4:"651a";s:41:"Resources/Private/Icons/enterEditMode.gif";s:4:"1596";s:34:"Resources/Private/Icons/export.gif";s:4:"254c";s:53:"Resources/Private/Icons/exportDeleteConfiguration.gif";s:4:"6de8";s:41:"Resources/Private/Icons/exportExecute.gif";s:4:"9cb5";s:51:"Resources/Private/Icons/exportLoadConfiguration.gif";s:4:"9f46";s:51:"Resources/Private/Icons/exportSaveConfiguration.gif";s:4:"1431";s:47:"Resources/Private/Icons/exportToggleDisplay.gif";s:4:"b1d6";s:43:"Resources/Private/Icons/forwardLastPage.gif";s:4:"b501";s:39:"Resources/Private/Icons/forwardPage.gif";s:4:"f6ca";s:50:"Resources/Private/Icons/forwardPageNotPossible.gif";s:4:"273e";s:39:"Resources/Private/Icons/generateRtf.gif";s:4:"9389";s:32:"Resources/Private/Icons/help.gif";s:4:"7e7e";s:71:"Resources/Private/Icons/icon_tx_savlibraryplus_export_configuration.gif";s:4:"475a";s:41:"Resources/Private/Icons/leaveEditMode.gif";s:4:"755b";s:32:"Resources/Private/Icons/move.gif";s:4:"16fd";s:31:"Resources/Private/Icons/new.gif";s:4:"cdb3";s:35:"Resources/Private/Icons/newicon.gif";s:4:"6aba";s:35:"Resources/Private/Icons/newMail.gif";s:4:"ffa9";s:38:"Resources/Private/Icons/newMailOff.gif";s:4:"a2c1";s:32:"Resources/Private/Icons/next.gif";s:4:"6f9a";s:36:"Resources/Private/Icons/orderAsc.gif";s:4:"6ac6";s:44:"Resources/Private/Icons/orderAscSelected.gif";s:4:"8f1c";s:37:"Resources/Private/Icons/orderDesc.gif";s:4:"a3f2";s:45:"Resources/Private/Icons/orderDescSelected.gif";s:4:"a6e1";s:36:"Resources/Private/Icons/previous.gif";s:4:"b5a7";s:33:"Resources/Private/Icons/print.gif";s:4:"c144";s:50:"Resources/Private/Icons/radioButtonNotSelected.gif";s:4:"3230";s:47:"Resources/Private/Icons/radioButtonSelected.gif";s:4:"ae24";s:36:"Resources/Private/Icons/required.gif";s:4:"29f1";s:32:"Resources/Private/Icons/save.gif";s:4:"933e";s:40:"Resources/Private/Icons/saveAndClose.gif";s:4:"2c9f";s:38:"Resources/Private/Icons/saveAndNew.gif";s:4:"9748";s:39:"Resources/Private/Icons/saveAndShow.gif";s:4:"bab4";s:34:"Resources/Private/Icons/search.gif";s:4:"d07c";s:32:"Resources/Private/Icons/show.gif";s:4:"0c95";s:34:"Resources/Private/Icons/submit.gif";s:4:"4852";s:39:"Resources/Private/Icons/submitadmin.gif";s:4:"0604";s:33:"Resources/Private/Icons/Thumbs.db";s:4:"1ea1";s:34:"Resources/Private/Icons/toggle.gif";s:4:"664c";s:30:"Resources/Private/Icons/up.gif";s:4:"4928";s:34:"Resources/Private/Images/Thumbs.db";s:4:"2aa0";s:36:"Resources/Private/Images/unknown.gif";s:4:"0d50";s:48:"Resources/Private/JavaScript/sav_library_plus.js";s:4:"7712";s:40:"Resources/Private/Language/locallang.xlf";s:4:"542e";s:40:"Resources/Private/Language/locallang.xml";s:4:"4c2e";s:43:"Resources/Private/Language/locallang_db.xlf";s:4:"5254";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"66fe";s:87:"Resources/Private/Language/ContextSensitiveHelp/locallang_csh_flexform_helpAdvanced.xml";s:4:"4e72";s:86:"Resources/Private/Language/ContextSensitiveHelp/locallang_csh_flexform_helpGeneral.xml";s:4:"385a";s:88:"Resources/Private/Language/ContextSensitiveHelp/locallang_csh_flexform_helpHelpPages.xml";s:4:"b87f";s:92:"Resources/Private/Language/ContextSensitiveHelp/locallang_csh_flexform_helpInputControls.xml";s:4:"bcf5";s:38:"Resources/Private/Layouts/Default.html";s:4:"c937";s:56:"Resources/Private/Partials/Footers/EditView/Default.html";s:4:"d41d";s:56:"Resources/Private/Partials/Footers/EditView/Subform.html";s:4:"267c";s:56:"Resources/Private/Partials/Footers/FormView/Default.html";s:4:"9c29";s:55:"Resources/Private/Partials/Footers/ListView/Arrows.html";s:4:"587b";s:65:"Resources/Private/Partials/Footers/ListView/ArrowsInEditMode.html";s:4:"f7bb";s:60:"Resources/Private/Partials/Footers/ListView/PageBrowser.html";s:4:"a4d7";s:70:"Resources/Private/Partials/Footers/ListView/PageBrowserInEditMode.html";s:4:"b223";s:58:"Resources/Private/Partials/Footers/SingleView/Default.html";s:4:"d41d";s:58:"Resources/Private/Partials/Footers/SingleView/Subform.html";s:4:"83f8";s:58:"Resources/Private/Partials/TitleBars/EditView/Default.html";s:4:"d550";s:58:"Resources/Private/Partials/TitleBars/EditView/Subform.html";s:4:"c68c";s:60:"Resources/Private/Partials/TitleBars/ExportView/Default.html";s:4:"e52c";s:58:"Resources/Private/Partials/TitleBars/FormView/Default.html";s:4:"689f";s:58:"Resources/Private/Partials/TitleBars/ListView/Default.html";s:4:"9f64";s:68:"Resources/Private/Partials/TitleBars/ListView/DefaultInEditMode.html";s:4:"6b75";s:56:"Resources/Private/Partials/TitleBars/OrderLinks/Asc.html";s:4:"2b35";s:60:"Resources/Private/Partials/TitleBars/OrderLinks/Ascdesc.html";s:4:"b7c1";s:57:"Resources/Private/Partials/TitleBars/OrderLinks/Desc.html";s:4:"14f3";s:57:"Resources/Private/Partials/TitleBars/OrderLinks/Link.html";s:4:"ccb5";s:64:"Resources/Private/Partials/TitleBars/OrderLinks/LinkDefault.html";s:4:"d91c";s:58:"Resources/Private/Partials/TitleBars/OrderLinks/Value.html";s:4:"fd4c";s:60:"Resources/Private/Partials/TitleBars/SingleView/Default.html";s:4:"ec6b";s:60:"Resources/Private/Partials/TitleBars/SingleView/Subform.html";s:4:"1dc5";s:33:"Resources/Private/Styles/help.css";s:4:"4ae1";s:45:"Resources/Private/Styles/sav_library_plus.css";s:4:"458a";s:51:"Resources/Private/Styles/Images/folderTabBorder.gif";s:4:"a22e";s:49:"Resources/Private/Styles/Images/folderTabLeft.gif";s:4:"749f";s:50:"Resources/Private/Styles/Images/folderTabRight.gif";s:4:"9802";s:41:"Resources/Private/Styles/Images/Thumbs.db";s:4:"3cf2";s:45:"Resources/Private/Templates/Default/Edit.html";s:4:"ca64";s:46:"Resources/Private/Templates/Default/Error.html";s:4:"1aa4";s:47:"Resources/Private/Templates/Default/Export.html";s:4:"d854";s:45:"Resources/Private/Templates/Default/Form.html";s:4:"fa4e";s:50:"Resources/Private/Templates/Default/FormAdmin.html";s:4:"2cd7";s:45:"Resources/Private/Templates/Default/List.html";s:4:"f41b";s:55:"Resources/Private/Templates/Default/ListInEditMode.html";s:4:"5c46";s:47:"Resources/Private/Templates/Default/Single.html";s:4:"5c15";s:52:"Resources/Private/Templates/Default/SubformEdit.html";s:4:"8965";s:54:"Resources/Private/Templates/Default/SubformSingle.html";s:4:"32ea";s:14:"doc/manual.sxw";s:4:"150b";s:41:"tests/tx_savlibrary_queriers_testcase.php";s:4:"a971";s:32:"tests/tx_savlibrary_testcase.php";s:4:"04a3";s:40:"tests/tx_savlibrary_testcase_dataset.xml";s:4:"ea3e";s:24:"tests/utils_testcase.php";s:4:"4737";s:43:"tests/phpunit/class.tx_phpunit_frontend.php";s:4:"3842";s:30:"tests/phpunit/core_dataset.xml";s:4:"f101";}',
	'suggests' => array(
	),
);

?>