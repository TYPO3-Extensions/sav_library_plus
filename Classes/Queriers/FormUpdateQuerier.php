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
 * Default update Querier.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_Queriers_FormUpdateQuerier extends Tx_SavLibraryPlus_Queriers_UpdateQuerier {

  /**
   * Executes the query
   *
   * @param none
   *
   * @return none
   */
  protected function executeQuery() {

    // Gets the library configuration manager
    $libraryConfigurationManager = $this->getController()->getLibraryConfigurationManager();

    // Gets the view configuration
    $viewIdentifier =  $libraryConfigurationManager->getViewIdentifier('formView');
    $viewConfiguration = $libraryConfigurationManager->getViewConfiguration($viewIdentifier);

    // Gets the active folder key
    $activeFolderKey = $this->getController()->getUriManager()->getFolderKey();
    if($activeFolderKey === NULL) {
      reset($viewConfiguration);
      $activeFolderKey = key($viewConfiguration);
    }

    // Sets the active folder
    $activeFolder = $viewConfiguration[$activeFolderKey];

    // Creates the field configuration manager
    $fieldConfigurationManager = t3lib_div::makeInstance('Tx_SavLibraryPlus_Managers_FieldConfigurationManager');
    $fieldConfigurationManager->injectController($this->getController());
    
    // Gets the fields configuration for the folder
    $folderFieldsConfiguration = $fieldConfigurationManager->getFolderFieldsConfiguration($activeFolder, true);

    // Gets the POST variables
    $postVariables = $this->getController()->getUriManager()->getPostVariables();
    unset($postVariables['formAction']);
   
    // Gets the main table
    $mainTable = $this->getQueryConfigurationManager()->getMainTable();
		$mainTableUid = Tx_SavLibraryPlus_Managers_UriManager::getUid();
		    
		// Processes the regular fields. Explode the key to get the table and field names
		$variablesToUpdate = array();
		foreach($postVariables as $postVariableKey => $postVariable) {
		  foreach ($postVariable as $uid => $value) {

        // Sets the field configuration
        $this->fieldConfiguration = $this->searchConfiguration($folderFieldsConfiguration, $postVariableKey);
        $tableName = $this->fieldConfiguration['tableName'];
        $fieldName = $this->fieldConfiguration['fieldName'];
        $fieldType = $this->fieldConfiguration['fieldType'];
        
        // Adds the cryted full field name
        $this->fieldConfiguration['cryptedFullFieldName'] = $postVariableKey;        

        // Adds the uid to the configuration
        $this->fieldConfiguration['uid'] = $uid;

        // Makes pre-processings.
        self::$doNotAddValueToUpdateOrInsert = false;
        $value = $this->preProcessor($value);

        // Sets the processed Post variables to retrieve for error processing if any
        $fullFieldName = $tableName . '.' . $fieldName;
        $this->processedPostVariables[$fullFieldName][$uid] = array('value' => $value, 'errorCode' => self::$errorCode);
            
        // Adds the variables
        if (self::$doNotAddValueToUpdateOrInsert === false) {
		      $variablesToUpdateOrInsert[$tableName][$uid][$tableName . '.' . $fieldName] = $value;
        } 
      }
		}

		// Checks if error exists
		if (self::$doNotUpdateOrInsert === true) {
			Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.dataNotSaved');
			return; 			    		
		}		
				
		// Updates the fields if any
    if (empty($variablesToUpdateOrInsert) === false) {
    	$variableToSerialize = array();
  		foreach ($variablesToUpdateOrInsert as $tableName => $variableToUpdateOrInsert) {
        if (empty($tableName) === false){ 
        	$variableToSerialize = $variableToSerialize + $variableToUpdateOrInsert; 	
				}
      }

      // Updates the _submitted_data_ field
			$shortFormName = Tx_SavLibraryPlus_Controller_AbstractController::getShortFormName();
			$serializedVariable = serialize(array($shortFormName => array('temporary' => $variableToSerialize)));  
      $this->updateFields($mainTable, array('_submitted_data_' => $serializedVariable,'_validated_' => 0), $mainTableUid);   
			Tx_SavLibraryPlus_Controller_FlashMessages::addMessage('message.dataSaved'); 			    	
    }

    // Post-processing
    if (empty($this->postProcessingList) === false) {
      foreach($this->postProcessingList as $postProcessingItem) {
        $this->fieldConfiguration = $postProcessingItem['fieldConfiguration'];
        $method = $postProcessingItem['method'];
        $value = $postProcessingItem['value'];
        $this->$method($value);
      }
    }
   
  }

}
?>
