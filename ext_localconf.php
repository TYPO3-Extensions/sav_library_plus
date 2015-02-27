<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

// Makes the extension version available to the extension scripts
if (version_compare(TYPO3_version, '6.0', '<')) {
  Tx_SavLibraryPlus_Utility_Autoloader::register();
  require(t3lib_extMgm::extPath($_EXTKEY) . 'ext_emconf.php');   
} else {
  require(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'ext_emconf.php');
} 

$TYPO3_CONF_VARS['EXTCONF'][$_EXTKEY]['version'] = $EM_CONF[$_EXTKEY]['version'];

?>
