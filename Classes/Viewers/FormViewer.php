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
 * Default Form Viewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_Viewers_FormViewer extends Tx_SavLibraryPlus_Viewers_AbstractViewer {

  /**
   * Item viewer directory
   *
   * @var string
   */
  protected $itemViewerDirectory = 'Edit';
  
  /**
   * Edit mode flag
   *
   * @var boolean
   */
  protected $inEditMode = false;
  
  /**
   * The template file
   *
   * @var string
   */
  protected $templateFile = 'Form.html';
  
  /**
   * The query configuration manager
   *
   * @var Tx_SavLibraryPlus_Managers_QueryConfigurationManager
   */
  protected $queryConfigurationManager;
  
   /**
   * The current processed row
   *
   * @var array
   */
  protected $row;
  
  /**
   * Renders the view
   *
   * @param none
   *
   * @return string The rendered view
   */
  public function render() {

    // Adds the javaScript file
    $GLOBALS['TSFE']->additionalHeaderData[Tx_SavLibraryPlus_Controller_AbstractController::LIBRARY_NAME] =
        '<script type="text/javascript" src="' .
        t3lib_extMgm::siteRelPath(Tx_SavLibraryPlus_Controller_AbstractController::LIBRARY_NAME) .
        'Resources/Private/JavaScript/sav_library_plus.js"></script>';  	
  	
    // Gets the library configuration manager
    $libraryConfigurationManager = $this->getController()->getLibraryConfigurationManager();

    // Sets the library view configuration
    $this->setLibraryViewConfiguration('FormView');

    // Sets the active folder Key
    $this->setActiveFolderKey();

    // Creates the template configuration manager
    $templateConfigurationManager = t3lib_div::makeInstance('Tx_SavLibraryPlus_Managers_TemplateConfigurationManager');
    $templateConfigurationManager->injectTemplateConfiguration($libraryConfigurationManager->getFormViewTemplateConfiguration());

    // Creates the field configuration manager
    $this->createFieldConfigurationManager();
    
    // Gets the item template
    $itemTemplate = $templateConfigurationManager->getItemTemplate();

    // Processes the rows
    $rows = $this->getController()->getQuerier()->getRows();

    $fields = array();
    foreach ($rows as $rowKey => $row) {

      $this->getController()->getQuerier()->setCurrentRowId($rowKey);
      
    	// Gets the fields configuration for the folder
    	$this->folderFieldsConfiguration = $this->getFieldConfigurationManager()->getFolderFieldsConfiguration($this->getActiveFolder());

      $listItemConfiguration = array(
        'template' => $this->parseItemTemplate($itemTemplate),
        'uid' => $row['uid'],
      );

      $fields[] = $listItemConfiguration;
    }
    
    // Adds the fields configuration
    $this->addToViewConfiguration('fields', $fields);
    
    // Adds information to the view configuration
    $this->addToViewConfiguration('general',
      array(
        'extensionKey' => $this->getController()->getExtensionConfigurationManager()->getExtensionKey(),
        'helpPage' => $this->getController()->getExtensionConfigurationManager()->getHelpPageForListView(),
        'addPrintIcon' => $this->getActiveFolderField('addPrintIcon'),
        'formName' => Tx_SavLibraryPlus_Controller_AbstractController::getFormName(),   
      	'uid' => Tx_SavLibraryPlus_Managers_UriManager::getUid(),
        'title' => $this->processTitle($this->parseTitle($this->getActiveFolderTitle())),
      )
    );

    return $this->renderView();
  }

  /**
   * Parses the item template
   *
   * @param $itemTemplate string The item template
   *
   * @return string The parsed item template
   */
  protected function parseItemTemplate($itemTemplate) {

    // Parses the field marker	
		$itemTemplate = $this->parseFieldSpecialTags($itemTemplate);
    
  	// Adds the required flag if needed
		$itemTemplate = $this->addRequiredFlag($itemTemplate);
		
    // Parses the buttons if any
		$itemTemplate = $this->parseButtonSpecialTags($itemTemplate);  

    // Parses localization tags
    $itemTemplate = $this->getController()->getQuerier()->parseLocalizationTags($itemTemplate, false);  	
    
		// Processes the rendering of the item
		$itemTemplate = $this->parseRenderTags($itemTemplate);
   
  	return $itemTemplate;
  }

  /**
	 * Parses the ###field[]### markers
	 *
	 * @param string $template
	 *
	 * @return string 
	 */
	protected function parseFieldSpecialTags($template) {  
    // Processes the field marker
    preg_match_all('/###field\[([^\],]+)(,?)([^\]]*)\]###/', $template, $matches);  

    foreach($matches[0] as $matchKey => $match) {

    	// Gets the crypted full field name
      $fullFieldName =  $this->getController()->getQuerier()->buildFullFieldName($matches[1][$matchKey]);  
      $cryptedFullFieldName = Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($fullFieldName);

      // Removes the field if not in admin mode
      if ($this->folderFieldsConfiguration[$cryptedFullFieldName]['addeditifadmin']) {
      	$template = str_replace($matches[0][$matchKey], '', $template);
      	continue;
      }
    	
      // Processes the field
			if ($matches[2][$matchKey]) {
				// Checks if required is needed
				if ($this->folderFieldsConfiguration[$cryptedFullFieldName]['required']) {
      		$required = 'Required';
      	} else {
      		$required = '';
      	}
				
				$replacementString = 
              '<div class="column1">$$$label' . $required . '[' . $matches[3][$matchKey] . ']$$$</div>' .
                '<div class="column2">###renderSaved[' . $matches[1][$matchKey] . ']###</div>' .
                '<div class="column3">###renderEdit[' . $matches[1][$matchKey] . ']###</div>';				
			} else {
				$replacementString = '###renderEdit[' . $matches[1][$matchKey] . ']###';				
			}
			$template = str_replace($matches[0][$matchKey], $replacementString, $template);				
    }
    return $template;  	  
	}
	  
	/**
	 * Parses ###button[]### markers
	 *
	 * @param string $template
	 *
	 * @return string 
	 */
	protected function parseButtonSpecialTags($template) {  
    // Processes the buttons if needed
    preg_match_all('/###button\[([^\]]+)\]###/', $template, $matches);

    foreach($matches[0] as $matchKey => $match) {
      $functionName = $matches[1][$matchKey] . 'Button';
      if (method_exists($this, $functionName)) {
        $template = str_replace($matches[0][$matchKey], $this->$functionName(), $template);
      }
    }  
    return $template;
	}

	/**
	 * Parses ###render[]###, ###renderEdit[]###, ###renderValidation[]### markers
	 *
	 * @param string $template
	 *
	 * @return string 
	 */
	protected function parseRenderTags($template) {  
    // Processes the render marker	
    preg_match_all('/###render(Edit|Saved|Validation)?\[([^#]+)\]###/', $template, $matches);

    foreach($matches[0] as $matchKey => $match) {
   	
      // Builds the crypted full field name
      $fullFieldName =  $this->getController()->getQuerier()->buildFullFieldName($matches[2][$matchKey]);
      $cryptedFullFieldName = Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($fullFieldName);
      
      if (empty($this->folderFieldsConfiguration[$cryptedFullFieldName])) {
        Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.unknownFieldName', array($fullFieldName));
      	continue;
      }

      // Adds the item name
      $uid = $this->getController()->getQuerier()->getFieldValueFromCurrentRow('uid');
      $itemName = Tx_SavLibraryPlus_Controller_AbstractController::getFormName() . '[' . $cryptedFullFieldName . '][' . intval($uid) . ']';
      $this->folderFieldsConfiguration[$cryptedFullFieldName]['itemName'] = $itemName;
      
      // Sets the default rendering
			$this->folderFieldsConfiguration[$cryptedFullFieldName]['edit'] = '0';

			switch($matches[1][$matchKey]) {
				case 'Edit':
					$this->folderFieldsConfiguration[$cryptedFullFieldName]['edit'] = '1';
					$replacementString = $this->renderItem($cryptedFullFieldName);
					break;
				case 'Saved':
					$this->folderFieldsConfiguration[$cryptedFullFieldName]['edit'] = '0';
					$previousValue = $this->folderFieldsConfiguration[$cryptedFullFieldName]['value'];
					$this->folderFieldsConfiguration[$cryptedFullFieldName]['value'] = $this->getController()->getQuerier()->getFieldValueFromSavedRow($fullFieldName);
					$replacementString = $this->renderItem($cryptedFullFieldName);
					$this->folderFieldsConfiguration[$cryptedFullFieldName]['value'] = $previousValue;
					break;									
				case '':
					$this->folderFieldsConfiguration[$cryptedFullFieldName]['edit'] = '0';
					$replacementString = $this->renderItem($cryptedFullFieldName);
					break;
				case 'Validation':
      		// Adds the hidden element for validation
      		$checkboxName = Tx_SavLibraryPlus_Controller_AbstractController::getFormName() . '[validation][' . $cryptedFullFieldName . ']';
    			$hiddenElement = Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputHiddenElement(
      			array(
        			Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $checkboxName),
        			Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', '0'),
      			)
    			);

    			// Adds the checkbox element
    			$checkboxElement = Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputCheckBoxElement(
      			array(
        			Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('name', $checkboxName),
        			Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', '1'),
        			Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttributeIfNotNull('checked', $this->folderFieldsConfiguration[$cryptedFullFieldName]['checkedinupdateformadmin']),
      			)
    			);	
					$replacementString = $hiddenElement . $checkboxElement;
					break;    												
			} 
      
      // Renders the item
      $template = str_replace($matches[0][$matchKey], $replacementString , $template);
    }
		
    return $template;
	}	
	
	/**
	 * Adds the required flag
	 *
	 * @param string $template
	 *
	 * @return string 
	 */
	protected function addRequiredFlag($template) {
    preg_match_all('/\$\$\$label(Required)?\[([^\]]+)\]\$\$\$/', $template, $matches);

    foreach($matches[0] as $matchKey => $match) {
    	// Checks if labelRequired is set
    	if ($matches[1][$matchKey]) {
      	$template = str_replace($matches[0][$matchKey], str_replace('labelRequired', 'label', $matches[0][$matchKey]) . Tx_SavLibraryPlus_Utility_HtmlElements::htmlSpanElement(
           array(
             Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'required'),
           ),
           Tx_SavLibraryPlus_Controller_FlashMessages::translate('formView.required')
				), $template);    		
    	}	else {
      // Builds the crypted full field name
      $fullFieldName =  $this->getController()->getQuerier()->buildFullFieldName($matches[2][$matchKey]);
      $cryptedFullFieldName = Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($fullFieldName);
     
      if ($this->folderFieldsConfiguration[$cryptedFullFieldName]['required']) {
      	$template = str_replace($matches[0][$matchKey], $matches[0][$matchKey] . Tx_SavLibraryPlus_Utility_HtmlElements::htmlSpanElement(
            array(
              Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'required'),
            ),
            Tx_SavLibraryPlus_Controller_FlashMessages::translate('formView.required')
          ), $template);
      } 
    	}
    }
    return $template;
	} 	
    	
	
  /**
   * Parses the item template
   *
   * @param string $title The title
   *
   * @return string The parsed title
   */
  protected function parseTitle($title) {
    return $title;
  }

	/**
	 * Generates a Submit Form button
	 *
	 * @return string (Submit Form button)
	 */
	protected function submitButton() {
	
    $content = Tx_SavLibraryPlus_Utility_HtmlElements::htmlInputSubmitElement(
      array(
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('class', 'submitButton'),      
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('value', Tx_SavLibraryPlus_Controller_FlashMessages::translate('button.submit')),
        Tx_SavLibraryPlus_Utility_HtmlElements::htmlAddAttribute('onclick', 'update();'),       
      )
    );
    
		return $content;
  }
  
}
?>
