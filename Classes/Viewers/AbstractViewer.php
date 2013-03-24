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
 * Abstract class Viewer.
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
   * The default template root directory
   *
   * @var string
   */
  protected $defaultTemplateRootPath = 'EXT:sav_library_plus/Resources/Private/Templates/Default';  

  /**
   * The template root directory
   *
   * @var string
   */
  protected $templateRootPath;  

  /**
   * The template file
   *
   * @var string
   */
  protected $templateFile;

  /**
   * The link configuration
   *
   * @var array
   */
  protected $linkConfiguration = array();  
    
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
  protected $libraryConfigurationManager;	

  /**
   * The field configuration manager
   *
   * @var Tx_SavLibraryPlus_Managers_FieldConfigurationManager
   */
  protected $fieldConfigurationManager;	 
  
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
   * @var string
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
	 * @param Tx_SavLibraryPlus_Controller_AbstractController $controller The controller
	 *
	 * @return  array
	 */
  public function injectController($controller) {
    $this->controller = $controller;
  }

	/**
	 * Injects the library view configuration
	 *
	 * @param array $libraryViewConfiguration The library view configuration
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
	 * @return Tx_SavLibraryPlus_Controller_Controller
	 */
  public function getController() {
    return $this->controller;
  }

	/**
	 * Gets the library configuration manager
	 *
	 * @param none
	 *
	 * @return Tx_SavLibraryPlus_Managers_LibraryConfigurationManager
	 */
  public function getLibraryConfigurationManager() {
    return $this->libraryConfigurationManager;
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
	 * Gets the partial root path
	 *
	 * @param none
	 *
	 * @return string
	 */
  public function getPartialRootPath() {
  	return $this->getDirectoryName($this->partialRootPath);
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
	 * Gets the layout root path
	 *
	 * @param none
	 *
	 * @return string
	 */
  public function getLayoutRootPath() {
  	return $this->getDirectoryName($this->layoutRootPath);
  }   
  
	/**
	 * Sets the template root path
	 *
	 * @param string $templateRootPath
	 *
	 * @return none
	 */
  public function setTemplateRootPath($templateRootPath) {
  	$this->templateRootPath = $templateRootPath;
  }

	/**
	 * Gets the template root path
	 *
	 * @param none
	 *
	 * @return string
	 */
  public function getTemplateRootPath() {
  	return $this->getDirectoryName($this->templateRootPath);
  }   

	/**
	 * Gets the default template root path
	 *
	 * @param none
	 *
	 * @return string
	 */
  public function getDefaultTemplateRootPath() {
  	return $this->getDirectoryName($this->defaultTemplateRootPath);
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
	 * Gets the template file
	 *
	 * @param none
	 *
	 * @return string
	 */
  public function getTemplateFile() {
  	$templateRootPath = $this->getTemplateRootPath();

  	// Returns the template file in the template root path if it exists
  	$templateFile = $templateRootPath . '/' . $this->templateFile;
  	if (@is_file(PATH_site . $templateFile) === true) {
  		return $templateFile;
  	} else {
  		// Returns the file in the default template root path
  		$defaultTemplateRootPath = $this->getDefaultTemplateRootPath();
  		$templateFile = $defaultTemplateRootPath . '/' . $this->templateFile;  
  		if (@is_file(PATH_site . $templateFile) === true) {
  			return $templateFile;
  		} else {  
				throw new Tx_SavLibraryPlus_Exception('The file "' . htmlspecialchars(PATH_site . $templateFile) . '" does not exist');  			
  		}				
  	}
  }   

	/**
	 * Sets the link configuration
	 *
	 * @param array $linkConfiguration
	 *
	 * @return none
	 */
  public function setLinkConfiguration($linkConfiguration) {
  	$this->linkConfiguration = $linkConfiguration; 	
  }

	/**
	 * Gets the link configuration
	 *
	 * @param none
	 *
	 * @return array The link configuration
	 */
  public function getLinkConfiguration() {
  	return $this->linkConfiguration;
  } 
  
 	/**
	 * Creates the field configuration manager
	 *
	 * @param none
	 *
	 * @return none
	 */
  protected function createFieldConfigurationManager() {
    $this->fieldConfigurationManager = t3lib_div::makeInstance('Tx_SavLibraryPlus_Managers_FieldConfigurationManager');
    $this->fieldConfigurationManager->injectController($this->getController());
  }

	/**
	 * Gets the field configuration manager
	 *
	 * @param none
	 *
	 * @return Tx_SavLibraryPlus_Managers_FieldConfigurationManager
	 */
  protected function getFieldConfigurationManager() {
  	return $this->fieldConfigurationManager;
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
	 * @param none
	 *
	 * @return none
	 */
  public function setActiveFolderKey() {
    // Gets the active folder key
    $this->activeFolderKey = $this->getController()->getUriManager()->getFolderKey();

    // Uses the key of the first view configuration if the active folder key is null or there is no view configuration for the key
    if ($this->activeFolderKey === NULL || empty($this->libraryViewConfiguration[$this->activeFolderKey])) {
      reset($this->libraryViewConfiguration);
      $this->activeFolderKey = key($this->libraryViewConfiguration);      
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
      	$fieldConfigurationManager = $this->getFieldConfigurationManager();
      	$fieldConfigurationManager->injectKickstarterFieldConfiguration($folder['config']);
      	if ($fieldConfigurationManager->cutIf() === false) {
        	$foldersConfiguration[$folderKey]['label'] = $folder['config']['label'];
      	}
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
	 	
  	// Sets the view configuration files
  	$this->setViewConfigurationFilesFromTypoScriptConfiguration();

  	// Sets the link configuration
  	$this->setViewLinkConfigurationFromTypoScriptConfiguration();  	
	
    // Creates the view
    $view = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');
    
    // Sets the file template
    $view->setTemplatePathAndFilename($this->getTemplateFile());
    
    // Sets the layout and the partial root paths
    $view->setLayoutRootPath($this->getLayoutRootPath());
    $view->setPartialRootPath($this->getPartialRootPath());
    
    //Gets the link configuration
    $linkConfiguration = $this->getLinkConfiguration();
     
    // Adds the short form name to the general configuration
    $this->addToViewConfiguration('general',
      array(
        'shortFormName' => Tx_SavLibraryPlus_Controller_AbstractController::getShortFormName(),
        'contentIdentifier' => $this->getController()->getExtensionConfigurationManager()->getContentIdentifier(), 
      	'additionalParams' => Tx_SavLibraryPlus_Controller_AbstractController::convertLinkAdditionalParametersToArray($linkConfiguration['additionalParams']),
      )
    );    
  
    // Assigns the view configuration
    $view->assign('configuration', $this->viewConfiguration);
    
		// Renders the view
		return $view->render();    
  }

	/**
	 * Sets the view configuration files:
	 * - from the Page TypoScript Configuration if any
	 * - else from the extension TypoScript Configuration if any,
	 * - else from the library TypoScript Configuration if any,
	 * - else default configuration files are used.
	 *
	 * @param none
	 *
	 * @return none
	 */
  public function setViewConfigurationFilesFromTypoScriptConfiguration() {  
  	// Sets the template root path with the default
  	$this->templateRootPath = $this->defaultTemplateRootPath;  	
  	$this->getController()->getPageTypoScriptConfigurationManager()->setViewConfigurationFilesFromPageTypoScriptConfiguration();
  	$this->getController()->getExtensionConfigurationManager()->setViewConfigurationFilesFromTypoScriptConfiguration();
  	$this->getController()->getLibraryConfigurationManager()->setViewConfigurationFilesFromTypoScriptConfiguration();  
  }  

	/**
	 * Sets the link configuration:
	 * - from the Page TypoScript Configuration if any
	 * - else from the extension TypoScript Configuration if any,
	 * - else from the library TypoScript Configuration if any.
	 *
	 * @param none
	 *
	 * @return none
	 */
  public function setViewLinkConfigurationFromTypoScriptConfiguration() {  	
  	$this->getController()->getPageTypoScriptConfigurationManager()->setViewLinkConfigurationFromPageTypoScriptConfiguration();
  	$this->getController()->getExtensionConfigurationManager()->setViewLinkConfigurationFromTypoScriptConfiguration();
  	$this->getController()->getLibraryConfigurationManager()->setViewLinkConfigurationFromTypoScriptConfiguration();  
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
	 * Gets a directory name
	 *
	 * @param string $directoryName The directory name
	 *
	 * @return string the TYPO3 directory name
	 */
  public function getDirectoryName($directoryName) {
  	
  	$absoluteDirectoryName = t3lib_div::getFileAbsFileName($directoryName);
  	// Checks if the directory exists
		if (!@is_dir($absoluteDirectoryName)) {
			throw new Tx_SavLibraryPlus_Exception(Tx_SavLibraryPlus_Controller_FlashMessages::translate('error.directoryDoesNotExist', array(htmlspecialchars($cascadingStyleSheetAbsoluteFileName))));
		} else {
			return substr($absoluteDirectoryName, strlen(PATH_site));
		}  	
  }

	/**
	 * Processes the title field of a view.
	 * It replaces localization and field tags by their values
	 *
	 * @param string $title The title to process
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
	 * Processes the field.
	 *
	 * @param string $cryptedFullFieldName The crypted full field name
	 *
	 * @return none
	 */
  protected function processField($cryptedFullFieldName) { 	
    if ($this->folderFieldsConfiguration[$cryptedFullFieldName]['onlabel']) {
      $this->folderFieldsConfiguration[$cryptedFullFieldName]['label'] = $this->renderItem($cryptedFullFieldName);
      $this->folderFieldsConfiguration[$cryptedFullFieldName]['value'] = '';
    } else {
      $this->folderFieldsConfiguration[$cryptedFullFieldName]['value'] = $this->renderItem($cryptedFullFieldName);
    }
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
  
}
?>
