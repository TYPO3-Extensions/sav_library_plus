<?php

/**
 *  File: calendar.php | (c) dynarch.com 2004
 *  Distributed as part of "The Coolest DHTML Calendar"
 *  under the same terms.
 *  -----------------------------------------------------------------
 *  This file implements a simple PHP wrapper for the calendar.  It
 *  allows you to easily include all the calendar files and setup the
 *  calendar by instantiating and calling a PHP object.
 */


class Tx_SavLibraryPlus_DatePicker_DatePicker {

  var $datePickerCssFile = 'calendar-win2k-2.css';
  var $datePickerJsFile = 'calendar.js';
  var $datePickerJsSetupFile = 'calendar-setup.js';
  var $datePickerLanguageFile;
  var $datePickerDirectory;


  public function __construct() {
    $this->datePickerDirectory = t3lib_extMgm::siteRelPath('sav_library_plus') . 'Classes/DatePicker/';
    $this->datePickerLanguageFile = 'calendar-' . $GLOBALS['TSFE']->config['config']['language'] . '.js';
    if (! file_exists($this->datePickerDirectory . 'lang/' . $this->datePickerLanguageFile)) {
      $this->calendarLanguageFile = 'calendar-en.js';
    }
  }

  
  public function setAdditionalHeader($key = 'DatePicker') {
    if (!isset($GLOBALS['TSFE']->additionalHeaderData[$key])) {
      $GLOBALS['TSFE']->additionalHeaderData[$key] = $this->buildHeader();
    }
  }

  public function setDatePickerCssFile($datePickerCssFile) {
    $this->datePickerCssFile = $datePickerCssFile;
  }

  
  protected function buildHeader() {
    $header[] = '<link rel="stylesheet" type="text/css" media="all" href="' . $this->datePickerDirectory . 'css/' . $this->datePickerCssFile . '" />';
    $header[] = '<script type="text/javascript" src="' . $this->datePickerDirectory . 'js/' . $this->datePickerJsFile . '"></script>';
    $header[] = '<script type="text/javascript" src="' . $this->datePickerDirectory . 'lang/' . $this->datePickerLanguageFile . '" charset="utf-8"></script>';
    $header[] = '<script type="text/javascript" src="' . $this->datePickerDirectory . 'js/' . $this->datePickerJsSetupFile . '"></script>';

    return implode(chr(10), $header);
  }

  public function buildDatePickerSetup($datePickerConfiguration) {
    $datePickerSetup[] = '<a href="#">';
    $datePickerSetup[] = '<img class="datePickerCalendar" id="button_' . $datePickerConfiguration['id'] . '" src="' . $datePickerConfiguration['iconPath'] . '" alt="" title="" />';
    $datePickerSetup[] = '</a>';
    $datePickerSetup[] = '<script type="text/javascript">';
    $datePickerSetup[] = '/*<![CDATA[*/';
    $datePickerSetup[] = '  Calendar.setup({';
    $datePickerSetup[] = '    inputField     :    "input_' . $datePickerConfiguration['id'] . '",';
    $datePickerSetup[] = '    ifFormat       :    "' . $datePickerConfiguration['format'] . '",';
    $datePickerSetup[] = '    button         :    "button_' . $datePickerConfiguration['id'] . '",';
    $datePickerSetup[] = '    showsTime      :    ' . ($datePickerConfiguration['showsTime'] ? 'true' : 'false') . ',';
    $datePickerSetup[] = '    singleClick    :    true';
    $datePickerSetup[] = '  });';
    $datePickerSetup[] = '/*]]>*/';
    $datePickerSetup[] = '</script>';
    
    return implode(chr(10), $datePickerSetup);
  }

}

?>
