<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

// Make the extension version number available to the extension scripts
require_once(t3lib_extMgm::extPath($_EXTKEY) . 'ext_emconf.php');
$TYPO3_CONF_VARS['EXTCONF'][$_EXTKEY]['version'] = $EM_CONF[$_EXTKEY]['version'];

// Extends the context sensitive help
$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/view_help.php'] =
      t3lib_extMgm::extPath($_EXTKEY) . 'Classes/Utility/ViewHelpXclass.php';
                                     
?>
