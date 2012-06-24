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
 * Abstract class list Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
abstract class Tx_SavLibraryPlus_Viewers_AbstractViewer {

  /**
   * The controller
   *
   * @var Tx_SavLibraryPlus_Controller_Controller
   */
  private $controller;

  /**
   * The partial root directory
   *
   * @var string
   */
  protected $partialRootPath = 'EXT:sav_library_plus/Resources/Private/Partials';

  /**
   * The layout root directory
   *
   * @var string
   */
  protected $layoutRootPath = 'EXT:sav_library_plus/Resources/Private/Layouts';

  /**
   * The template file
   *
   * @var string
   */
  protected $templateFile;

  /**
   * Item viewer directory
   *
   * @var string
   */
  protected $itemViewerDirectory = 'Default';  
  
  /**
   * The new view flag
   *
   * @var boolean
   */
	protected $isNewView = false;

  /**
   * The library configuration manager
   *
   * @var Tx_SavLibraryPlus_Managers_LibraryConfigurationManager
   */
  protected $libraryConfigurationManager = array();	

  /**
   * The view type
   *
   * @var string
   */
	protected $viewType;
  
  /**
   * The view identifier
   *
   * @var integer
   */	
  protected $viewIdentifier;
  
  /**
   * The library view configuration
   *
   * @var array
   */
  protected $libraryViewConfiguration = array();

  /**
   * The active folder key
   *
   * @var array
   */
  protected $activeFolderKey;
  
  /**
   * The folder configuration
   *
   * @var array
   */
  protected $folderFieldsConfiguration = array();

  /**
   * The jpGraph image counter
   *
   * @var integer
   */
  protected $jpGraphCounter = 0;

  /**
   * The view configuration
   *
   * @var array
   */
  protected $viewConfiguration = array(); 

  /**
   * Flag which is set when the rich text editor has been generated once in the view
   *
   * @var boolean
   */
  protected $richTextEditorIsInitialized = false;
  
	/**
	 * Injects the controller
	 *
	 * @param $controller Tx_SavLibraryPlus_Controller_AbstractController the controller
	 *
	 * @return  array
	 */
  public function injectController($controller) {
    $this->controller = $controller;
  }

	/**
	 * Injects the library view configuration
	 *
	 * @param $controller Tx_SavLibraryPlus_Controller_AbstractController the controller
	 *
	 * @return  array
	 */
  public function injectLibraryViewConfiguration(&$libraryViewConfiguration) {
    $this->libraryViewConfiguration = $libraryViewConfiguration;
  }  
  
	/**
	 * Gets the controller
	 *
	 * @param none
	 *
	 * @return Tx_SavLibraryPlus_Controller_AbstractController
	 */
  public function getController() {
    return $this->controller;
  }

  /**
	 * Returns true if the view is a new view
	 *
	 * @param none
	 *
	 * @return boolean
	 */
	public function isNewView() {
		return $this->isNewView;
	}  
  
	/**
	 * Sets the library view configuration
	 *
	 * @param string $viewType The view type
	 *
	 * @return none
	 */
  public function setLibraryViewConfiguration($viewType) {
    // Sets the view type 	
  	$this->viewType = $viewType;
  	
    // Gets the library configuration manager
    $this->libraryConfigurationManager = $this->getController()->getLibraryConfigurationManager();
  
    // Gets the view identifier
    $this->viewIdentifier =  $this->libraryConfigurationManager->getViewIdentifier($viewType);
  
    // Gets the view configuration
    $this->libraryViewConfiguration =  $this->libraryConfigurationManager->getViewConfiguration($this->viewIdentifier);
  }

	/**
	 * Sets the template file
	 *
	 * @param string $templateFile
	 *
	 * @return none
	 */
  public function setTemplateFile($templateFile) {
  	$this->templateFile = $templateFile;
  }
     				
	/**
	 * Sets the partial root path
	 *
	 * @param string $partialRootPath
	 *
	 * @return none
	 */
  public function setPartialRootPath($partialRootPath) {
  	$this->partialRootPath = $partialRootPath;
  }

	/**
	 * Sets the layout root path
	 *
	 * @param string $layoutRootPath
	 *
	 * @return none
	 */
  public function setLayoutRootPath($layoutRootPath) {
  	$this->layoutRootPath = $layoutRootPath;
  }
  
	/**
	 * Gets the view type
	 *
	 * @param none
	 *
	 * @return string
	 */
  public function getViewType() {
  	return $this->viewType;
  }    
  
	/**
	 * Gets the item view directory
	 *
	 * @param none
	 *
	 * @return string
	 */
  public function getItemViewerDirectory() {
  	return $this->itemViewerDirectory;
  }  
  
	/**
	 * Sets the active folder key
	 *
	 * @param $libraryViewConfiguration array The library view configuration
	 *
	 * @return none
	 */
  public function setActiveFolderKey() {
    // Gets the active folder key
    $this->activeFolderKey = $this->getController()->getUriManager()->getFolderKey();
    if ($this->activeFolderKey === NULL) {
      reset($this->libraryViewConfiguration);
      $this->activeFolderKey = key($this->libraryViewConfiguration);
    }
    if (empty($this->libraryViewConfiguration[$this->activeFolderKey])) {
      $this->activeFolderKey = Tx_SavLibraryPlus_Controller_AbstractController::cryptTag('0');
    }
  }

	/**
	 * Gets the active folder key
	 *
	 * @param none
	 *
	 * @return string The active folder key
	 */
  public function getActiveFolderKey() {
    return $this->activeFolderKey;
  }
  
	/**
	 * Gets the active folder
	 *
	 * @param none
	 *
	 * @return array The active folder
	 */
  public function getActiveFolder() {
    return $this->libraryViewConfiguration[$this->activeFolderKey];
  }

	/**
	 * Gets the active folder field
	 *
	 * @param $fieldName string The field name
	 *
	 * @return array The active folder field
	 */
  public function getActiveFolderField($fieldName) {
    return $this->libraryViewConfiguration[$this->activeFolderKey][$fieldName];
  }

	/**
	 * Gets the active folder title
	 *
	 * @param none
	 *
	 * @return string The active folder title
	 */
  public function getActiveFolderTitle() {
    $titleField = $this->getActiveFolderField('title');
    return $titleField['config']['field'];
  }
  
	/**
	 * Adds the folders configuration to the view configuration
	 *
	 * @param none
	 *
	 * @return array The folders configuration
	 */
  public function getFoldersConfiguration() {
    // Adds the folders configuration
    foreach($this->libraryViewConfiguration as $folderKey => $folder) {
      if ($folderKey != Tx_SavLibraryPlus_Controller_AbstractController::cryptTag('0')) {
        $foldersConfiguration[$folderKey]['label'] = $folder['config']['label'];
      }
    }
    return $foldersConfiguration;
  }
  
	/**
	 * Sets the jpGraph counter
	 *
	 * @param integer $jpGraphCounter The jpGraphCounter
	 *
	 * @return none
	 */
  public function setJpGraphCounter($jpGraphCounter) {	
    $this->jpGraphCounter = $jpGraphCounter;
  }
  
  	/**
	 * Gets the jPGraph counter
	 *
	 * @param none
	 *
	 * @return integer
	 */
  public function getJpGraphCounter() {
    return $this->jpGraphCounter;
  }

	/**
	 * Adds a configuration for a given key
	 *
	 * @param $key string The key
	 * @param $configuration array The configuration to add
	 *
	 * @return none
	 */
  public function addToViewConfiguration($key, $configuration) {
    $this->viewConfiguration = array_merge_recursive($this->viewConfiguration, array($key => $configuration));
  }

	/**
	 * Gets a field from the general configuration
	 *
	 * @param $field string The field
	 *
	 * @return mixed
	 */
  public function getFieldFromGeneralViewConfiguration($field) {
    return $this->viewConfiguration['general'][$field];
  }

	/**
	 * Renders a view
	 *
	 * @param none
	 *
	 * @return string the rendered view
	 */
  public function renderView() {
  	
  	// Sets the view configuration files from the page Typoscript Configuration if any
  	$this->getController()->getPageTypoScriptConfigurationManager()->setViewConfigurationFilesFromPageTypoScriptConfiguration();

    // Creates the view
    $view = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');
    $view->setTemplatePathAndFilename($this->getFileName($this->templateFile));
    $view->setLayoutRootPath($this->getDirectoryName($this->layoutRootPath));
    $view->setPartialRootPath($this->getDirectoryName($this->partialRootPath));
    
    // Adds the short form name to the general configuration
    $this->addToViewConfiguration('general',
      array(
        'shortFormName' => Tx_SavLibraryPlus_Controller_AbstractController::getShortFormName(),
        'contentIdentifier' => $this->getController()->getExtensionConfigurationManager()->getContentIdentifier(), 
      )
    );    
  
    // Assigns the view configuration
    $view->assign('configuration', $this->viewConfiguration);
    
		// Renders the view
		return $view->render();    
  }

	/**
	 * Renders an item
	 *
	 * @param string $fieldKey The field key
	 *
	 * @return string the rendered item
	 */
  public function renderItem($fieldKey) {

    if (array_key_exists ($fieldKey, $this->folderFieldsConfiguration) === true) {
      $itemConfiguration = $this->folderFieldsConfiguration[$fieldKey];
      // The item configuration should not be empty.
    	if(empty($itemConfiguration)) {
    		// It occurs when ###fieldName### is used and "fieldName" is not in the main table
    		Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectFieldKey');
    		return '';
    	}  

			// Changes the item viewer directory to Default if the attribute edit is set to zero
      $itemViewerDirectory = ($itemConfiguration['edit'] === '0' ? 'Default' : $this->getItemViewerDirectory());
      
      // Creates the item viewer
      $className = 'Tx_SavLibraryPlus_ItemViewers_' . $itemViewerDirectory . '_' . $itemConfiguration['fieldType'] . 'ItemViewer';
      $itemViewer = t3lib_div::makeInstance($className);
      $itemViewer->injectController($this->getController());
      $itemViewer->injectItemConfiguration($itemConfiguration);
      
      // Renders the item
      return $itemViewer->render();
    } else {
      return '';
    }
  }

	/**
	 * Gets a file name
	 *
	 * @param string $fileName The file name
	 *
	 * @return string the TYPO3 file name
	 */
  protected function getFileName($fileName) {
		if (!strcmp(substr($fileName, 0, 4), 'EXT:')) {
			$newFileName = '';
			list($extensionKey, $script) = explode('/', substr($fileName, 4), 2);
			if ($extensionKey && t3lib_extMgm::isLoaded($extensionKey)) {
				$extensionPath = t3lib_extMgm::extPath($extensionKey);
				$newFileName = substr($extensionPath, strlen(PATH_site)) . $script;
			}
			if (!@is_file(PATH_site . $newFileName)) {
				throw new Tx_SavLibraryPlus_Exception('The file "' . htmlspecialchars(PATH_site . $newFileName) . '" does not exist');
			} else {
				return $newFileName;
			}
		}
  }

	/**
	 * Gets a directory name
	 *
	 * @param string $directoryName The directory name
	 *
	 * @return string the TYPO3 directory name
	 */
  protected function getDirectoryName($directoryName) {
		if (!strcmp(substr($directoryName, 0, 4), 'EXT:')) {
			$newDirectoryName = '';
			list($extensionKey, $script) = explode('/', substr($directoryName, 4), 2);
			if ($extensionKey && t3lib_extMgm::isLoaded($extensionKey)) {
				$extensionPath = t3lib_extMgm::extPath($extensionKey);
				$newDirectoryName = substr($extensionPath, strlen(PATH_site)) . $script;
			}
			if (!@is_dir(PATH_site . $newDirectoryName)) {
				throw new Tx_SavLibraryPlus_Exception('The directory "' . htmlspecialchars(PATH_site . $newDirectoryName) . '" does not exist');
			} else {
				return $newDirectoryName;
			}
		}
  }

	/**
	 * Processes the title field of a view.
	 * It replaces localization and field tags by their values
	 *
	 * @param $title string The title to process
	 *
	 * @return string The processed title
	 */
  public function processTitle($title) {
  	
  	// The title is not processed in a new view
  	if ($this->isNewView()) {
  		return '';
  	}

    // Checks if the title contains html tags
    if (preg_match('/<[^>]+>/', $title)) {
      $this->addToViewConfiguration('general',
        array(
          'titleNeedsFormat' => 1,
        )
      );
    }

    // Processes localization tags
    $title = $this->getController()->getQuerier()->parseLocalizationTags($title);

    // Processes field tags
    $title = $this->getController()->getQuerier()->parseFieldTags($title);

    return $title;
  }

  /**
   * Initializes the rich text editor
   *
   * @param $richTextEditorIsInitialized boolean Flag
   *
   * @return none
   */
  public function initializeRichTextEditor($richTextEditorIsInitialized = true) {
    $this->richTextEditorIsInitialized = $richTextEditorIsInitialized;
  }

  /**
   * Returns true if the each tech editor is initialized
   *
   * @param none
   *
   * @return boolean
   */
  public function isRichTextEditorInitialized() {
    return $this->richTextEditorIsInitialized;
  }  
  
  /**
   * Gets the view configuration from the page TypoScript configuration
   *
   * @param none
   *
   * @return string The javaScript Header
   */
  protected function getViewConfigurationFromPageTypoScriptConfiguration() {
		// Gets the page TypoScript configuration
    $pageTypoScriptConfiguration = $GLOBALS['TSFE']->getPagesTSconfig();
    if (is_array($pageTypoScriptConfiguration) === false) {
    	return;
    }
   
    // Gets the plugin TypoScript configuration
    $extensionConfigurationManager = $this->getController()->getExtensionConfigurationManager();
    $pluginTypoScriptConfiguration = $pageTypoScriptConfiguration[$extensionConfigurationManager->getTSconfigPluginName() . '.'];  
    if (is_array($pluginTypoScriptConfiguration) === false) {
    	return;
    }

    // Gets the plugin TypoScript configuration
    $formTypoScriptConfiguration = $pluginTypoScriptConfiguration[$this->getController()->getFormConfigurationManager()->getFormTitle() . '.']; 
    if (is_array($formTypoScriptConfiguration) === false) {
    	return;
    }    
    
    // Gets the view TypoScript configuration    
    $viewTypoScriptConfiguration = $formTypoScriptConfiguration[t3lib_div::lcfirst($this->viewType) . '.'];
    if (is_array($viewTypoScriptConfiguration) === false) {
    	return;
    }   
        
    // Processes the view configuration
    $viewConfigurations = $viewTypoScriptConfiguration['configuration.'];
    if (is_array($viewConfigurations)) {
    	foreach($viewConfigurations as $viewConfigurationKey => $viewConfiguration) {
    		switch($viewConfigurationKey) {
    			case 'templateFile':
    			case 'partialRootPath':
    			case 'layoutRootPath':
    				$this->$viewConfigurationKey = $viewConfiguration;
    				break;
    		}
    	}
    }

  }  
  
}
?>
