<?php
$classMap = array();
	
if (version_compare(TYPO3_version, '6.0', '<')) {
  require_once(t3lib_extMgm::extPath('sav_library_plus') . 'Configuration/Autoload/ext_autoload.php'); 
} else {
  // For compatibility with loaded and not yet updated extensions
  $extensionClassesPath = t3lib_extMgm::extPath('sav_library_plus') . 'Classes/';  
  
  $classMap = array(
		'tx_savlibraryplus_compatibility_utility_generalutility' =>  $extensionClassesPath . 'TYPO4x/Compatibility/Utility/GeneralUtility.php',
		'tx_savlibraryplus_filters_abstractfilter' =>  $extensionClassesPath . 'TYPO4x/Filters/AbstractFilter.php',
  );    
}
return $classMap;
?>
