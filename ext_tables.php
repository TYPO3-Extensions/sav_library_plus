<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');      
 
if (version_compare(TYPO3_version, '6.0', '<')) {
  require_once(t3lib_extMgm::extPath('sav_library_plus') . 'Configuration/ExtTables/TYPO4x/ext_tables.php');
} else {
  require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('sav_library_plus') . 'Configuration/ExtTables/ext_tables.php');
}
?>
