<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');      
 
// Adds user function for help in flexforms for extension depending on the SAV Library Plus
if (!function_exists('user_savlibraryPlusHelp')) {
  function user_savlibraryPlusHelp($PA, $fobj){

		$message = $fobj->sL('LLL:EXT:sav_library_plus/Resources/Private/Language/locallang.xml:pi_flexform.help');	
		$cshTag = $PA['fieldConf']['config']['userFuncParameters']['cshTag'];
		$skinnedIcon = \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($GLOBALS['BACK_PATH'], 'gfx/helpbubble.gif', '');
		$icon = '<img'.$skinnedIcon.' class="typo3-csh-icon" alt="' . \TYPO3\CMS\Core\Utility\GeneralUtility::lcfirst($cshTag) . '" />';
  	if (version_compare(TYPO3_version, '6.0', '<')) {
			$helpUrl = 'view_help.php?';
		}	else {
			$helpUrl = 'mod.php?M=help_cshmanual&';			
		}		
		
    return '<a href="#" onclick="vHWin=window.open(\'' . $helpUrl . 'tfID=xEXT_sav_library_plus_' .
    	 \TYPO3\CMS\Core\Utility\GeneralUtility::lcfirst($cshTag) .
    	 '.*\',\'viewFieldHelp\',\'height=400,width=600,status=0,menubar=0,scrollbars=1\');vHWin.focus();return FALSE;">' . 
    	 $icon . ' '. $message . '</a>';     
  }
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_savlibraryplus_export_configuration');

$TCA['tx_savlibraryplus_export_configuration'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tx_savlibraryplus_export_configuration',		
		'label'     => 'name',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',	
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'Configuration/TCA/tca.php',
		'iconfile'          => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY).'Resources/Private/Icons/icon_tx_savlibraryplus_export_configuration.gif',
	),
	'feInterface' => array (
		'fe_admin_fieldList' => 'hidden, fe_group, name, cid, configuration',
	)
);

// Adds the csh for the flexform
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('xEXT_' . $_EXTKEY . '_helpGeneral', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/ContextSensitiveHelp/locallang_csh_flexform_helpGeneral.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('xEXT_' . $_EXTKEY . '_helpInputControls', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/ContextSensitiveHelp/locallang_csh_flexform_helpInputControls.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('xEXT_' . $_EXTKEY . '_helpAdvanced', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/ContextSensitiveHelp/locallang_csh_flexform_helpAdvanced.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('xEXT_' . $_EXTKEY . '_helpHelpPages', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/ContextSensitiveHelp/locallang_csh_flexform_helpHelpPages.xml');

?>
