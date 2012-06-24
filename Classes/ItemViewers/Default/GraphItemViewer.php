<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Laurent Foulloy (yolf.typo3@orange.fr)
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
 * Default Graph item Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_ItemViewers_Default_GraphItemViewer extends Tx_SavLibraryPlus_ItemViewers_Default_AbstractItemViewer {

  /**
   * The xml graph
   *
   * @var xmlGraph
   */
  protected $xmlGraph;
  
  /**
   * If true the template is not processed
   *
   * @var boolean
   */
  protected $doNotProcessTemplate = false;  
  
  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {  
    // Checks that sav_jpgraph is loaded
    if (t3lib_extMgm::isLoaded('sav_jpgraph')) {

      // Creates the xlmgraph
      $this->createXmlGraph();

      // Processes the markes
      $this->processMarkers();

      // Defines the file name for the resulting image
      if ($this->doNotProcessTemplate === false) {
      	$content = $this->processTemplate();
    	}

    } else {
      Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.savJpGraphNotLoaded');
      $content = '';
    }

    return $content;
  }
  
	/**
	 * Creates the xml graph.
	 *
   * @param none
 	 *
	 * @return none
	 */
  protected function createXmlGraph() {
    // Defines the constant LOCALE for the use in the template
    define(LOCALE, $GLOBALS['TSFE']->config['config']['locale_all']);

    // Defines the constant CURRENT_PID for the use in the template
    define(CURRENT_PID, $GLOBALS['TSFE']->page['uid']);

    // Defines the constant STORAGE_PID for the use in the template
    $storageSiterootPids = $GLOBALS['TSFE']->getStorageSiterootPids();
    define(STORAGE_PID, $storageSiterootPids['_STORAGE_PID']);

    // Redefines the constant for TTF directory if necessary
    $unserializedConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sav_jpgraph']);
    if ($unserializedConfiguration['plugin.']['sav_jpgraph.']['ttfDir']) {
      define('TTF_DIR', $unserializedConfiguration['plugin.']['sav_jpgraph.']['ttfDir']);
    }

    // Defines the main directory
    define('JP_maindir', t3lib_extMgm::extPath('sav_jpgraph') . 'src/');

    // Defines the cache dir
    define('CACHE_DIR', 'typo3temp/sav_jpgraph/');

    // Requires the xml class
    require_once(t3lib_extMgm::extPath('sav_jpgraph'). 'class.typo3.php');
    require_once(t3lib_extMgm::extPath('sav_jpgraph'). 'class.xmlgraph.php');

    // Creates the xlmgraph
    $this->xmlGraph = t3lib_div::makeInstance('xmlGraph');
  }

	/**
	 * Creates the image.
	 *
   * @param none
 	 *
	 * @return string The image element
	 */
  protected function createImage() {
    // Defines the file name for the resulting image
    if (is_dir('typo3temp/sav_jpgraph') === false) {
      mkdir('typo3temp/sav_jpgraph');
    }
    $jpGraphCounter = $this->getController()->getViewer()->getJpGraphCounter();
    $formName = Tx_SavLibraryPlus_Controller_AbstractController::getFormName();
    $imageFileName = 'typo3temp/sav_jpgraph/img_' . $formName . '_' . $jpGraphCounter++ . '.png';
    $this->getController()->getViewer()->setJpGraphCounter($jpGraphCounter);

    // Sets the file reference
    $this->xmlGraph->setReferenceArray(
      'file',
      1,
      $imageFileName
    );

    // Deletes the file if it exists
    if (file_exists(PATH_site . $imageFileName)) {
      unlink(PATH_site . $imageFileName);
    }

    // Creates the image element
    $content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlImgElement(
      array(
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'jpgraph'),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('src', $imageFileName),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('title', ''),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('alt', ''),
      )
    );
    
    return $content;
  }

	/**
	 * Processes the markers.
	 *
   * @param none
 	 *
	 * @return none
	 */
  protected function processMarkers() {

    // Sets the markers if any
    if ($this->getItemConfiguration('markers')) {
      $markers = explode(',', $this->getItemConfiguration('markers'));

      // Processes the markes
      foreach($markers as $marker) {
        if (preg_match('/^([0-9A-Za-z_]+)#([0-9A-Za-z_]+)\s*=\s*(.*)$/', trim($marker), $match)) {
       	
          $name = $match[1];
          $id = $match[2];
          $value = $match[3];

          // Processes the value
          $value = $this->getController()->getQuerier()->parseLocalizationTags($value);
          $value = $this->getController()->getQuerier()->parseFieldTags($value, false);

					// Checks if the not empty condition is satisfied
					if (strtolower($value) == 'notempty[]') {
      			Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.savJpGraphFieldIsEmpty', array($match[3]));	
      			$this->doNotProcessTemplate = true;
      			continue;					
					} else {
						$value = preg_replace('/(?i)notempty\[([^\]]+)\]/', '$1', $value);
					}

          // Adds the marker to the reference array if it has been replaced.
          if (preg_match('/^###[0-9A-Za-z_]+###$/', $value) == 0) {
          
            // Post-processes it
            switch($name) {
              case 'data':
                $value = explode(',', $value);
                break;
            }

            // Adds it
            $this->xmlGraph->setReferenceArray($name, $id, $value);
          }
        }
      }
    }
  }

	/**
	 * Processes the template.
	 *
   * @param none
 	 *
	 * @return string The image element or empty string
	 */
  protected function processTemplate() {
  
    // Defines the file name for the resulting image
    $content = $this->createImage();

    // Processes the template
    $graphTemplate = $this->getItemConfiguration('graphtemplate');
    if(empty($graphTemplate)) {
      Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.savJpGraphTemplateNotSet');
      $content = '';
    } else {    	
      if (file_exists(PATH_site . $graphTemplate)) {
        $this->xmlGraph->loadXmlFile($graphTemplate);
        $this->xmlGraph->processXmlGraph();
      } else {
        Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.savJpGraphTemplateUnknown', array($graphTemplate));
        $content = '';
      }
    }
    
    return $content;
  }

}
?>
