<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

// Adds user function for help in flexforms for extension depending on the SAV Library Plus
if (!function_exists('user_savlibraryPlusHelp')) {
  function user_savlibraryPlusHelp($PA, $fobj){
    return '';
  }
}

t3lib_extMgm::allowTableOnStandardPages('tx_savlibraryplus_export_configuration');

$TCA['tx_savlibraryplus_export_configuration'] = array (
	"ctrl" => array (
		'title'     => 'LLL:EXT:sav_library_plus/Resources/Private/Language/locallang_db.xml:tx_savlibraryplus_export_configuration',		
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
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'Configuration/TCA/tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'Resources/Private/Icons/icon_tx_savlibraryplus_export_configuration.gif',
	),
	'feInterface' => array (
		'fe_admin_fieldList' => 'hidden, fe_group, name, cid, configuration',
	)
);

?>
