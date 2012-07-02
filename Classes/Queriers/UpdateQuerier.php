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
 
class Tx_SavLibraryPlus_Queriers_UpdateQuerier extends Tx_SavLibraryPlus_Queriers_AbstractQuerier {

	/**
	 * The POST variables
	 * 
	 * @var array
	 */  
  protected static $postVariables;  
  
	/**
	 * If true, the value is not updated nor inserted
	 * 
	 * @var boolean
	 */  
  public static $doNotAddValueToUpdateOrInsert = false;

	/**
	 * If true, the no data are updated or inserted
	 * 
	 * @var boolean
	 */  
  public static $doNotUpdateOrInsert = false;  
  
  /**
   * The field configuration
   * 
   * @var array
   */
  protected $fieldConfiguration;
      
	/**
	 * The post processing list
	 * 
	 * @var array
	 */  
  protected $postProcessingList;
  
	/**
	 * Searches recursively a configuration if an aray, given à key
	 *  
	 * @param array $arrayToSearchIn
	 * @param string $key
	 * @return array or false
	 */  
  public function searchConfiguration($arrayToSearchIn, $key) {
    foreach ($arrayToSearchIn as $itemKey => $item) {
      if ($itemKey == $key) {
        return $item;
      } elseif (isset($item['subform'])) {
        $configuration = $this->searchConfiguration($item['subform'], $key);
        if ($configuration != false) {
          return $configuration;
        }
      }
    }
    return false;
  }

	/**
	 * Gets an attribute in the field configuration
	 *
	 * @param string $attributeKey
	 *
	 * @return mixed
	 */
	protected function getFieldConfigurationAttribute($attributeKey) {
		return $this->fieldConfiguration[$attributeKey];
	}  

  /**
   * Executes the query
   *
   * @param none
   *
   * @return none
   */
  protected function executeQuery() {

    // Checks if the user is authenticated
    if($this->getController()->getUserManager()->userIsAuthenticated() === false) {
      Tx_SavLibraryPlus_Controller_FlashMessages::addError('fatal.notAuthenticated');
      return false;
    }
		
    // Gets the library configuration manager
    $libraryConfigurationManager = $this->getController()->getLibraryConfigurationManager();

    // Gets the view configuration
    $viewConfiguration = $libraryConfigurationManager->getViewConfiguration(Tx_SavLibraryPlus_Managers_UriManager::getViewId());
    
    // Gets the active folder key
    $activeFolderKey = Tx_SavLibraryPlus_Managers_UriManager::getFolderKey();
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
    $this->postVariables = $this->getController()->getUriManager()->getPostVariables();
    unset($this->postVariables['formAction']);

		// Processes the regular fields. Explode the key to get the table and field names
		$variablesToUpdate = array();
		foreach($this->postVariables as $postVariableKey => $postVariable) {
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
        if ($this->verifier($value)) {
          $value = $this->preProcessor($value);
        }
        
        // Adds the variables
        if (self::$doNotAddValueToUpdateOrInsert === false) {
		      $variablesToUpdateOrInsert[$tableName][$uid][$fieldName] = $value;
        }
      }
		}

		// Checks if error exists
		if (self::$doNotUpdateOrInsert === true) {
			Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.dataNotSaved'); 			    		
		}	else {	
			// No error, inserts or updates the data
	    if (empty($variablesToUpdateOrInsert) === false) {
	  		foreach ($variablesToUpdateOrInsert as $tableName => $variableToUpdateOrInsert) {
	        if (empty($tableName) === false){
	    		  foreach ($variableToUpdateOrInsert as $uid => $fields) {
	            if ($uid > 0) {
	              // Updates the fields
	              $this->updateFields($tableName, $fields, $uid);
	            } else {
	              // Inserts the fields
	              $this->insertFields($tableName, $fields);
	            }
	          }
	        }
	      }
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

	/**
	 * Pre-processor which calls the method according to the type
	 *
	 * @param mixed $value Value to be pre-processed
	 *
	 * @return mixed
	 */
  protected function preProcessor($value) {

    // Builds the method name
    $fieldType = $this->getFieldConfigurationAttribute('fieldType');
    if ($fieldType == 'ShowOnly') {
    	$renderType = $this->getFieldConfigurationAttribute('renderType');
    	$fieldType = (empty($renderType) ? 'String' : $renderType);
    }
    $preProcessorMethod = 'preProcessorFor' . $fieldType;
 
    // Calls the methods if it exists
    if (method_exists($this, $preProcessorMethod)) {
      $newValue =  $this->$preProcessorMethod($value);
    } else {
      $newValue = $value;
    }
    
		// Checks if a required field is not empty
		if ($this->isRequired() && empty($newValue)) {
			self::$doNotUpdateOrInsert = true;
			Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.fieldRequired', array($this->fieldConfiguration['label']));		
		}  
		    
    // Sets a post-processor for the email if any 
    if ($this->getFieldConfigurationAttribute('mail')) {
    	// Sets a post processor
    	$this->postProcessingList[] = array(
      	'method' => 'postProcessorToSendEmail',
      	'value' => $value,
      	'fieldConfiguration' => $this->fieldConfiguration
    	);
    	
    	// Gets the row before processing
    	$this->rows['before'] = $this->getCurrentRowInEditView();
    }

    // Sets a post-processor for the rtf if any 
    if ($this->getFieldConfigurationAttribute('generatertf')) {
    	// Sets a post processor
    	$this->postProcessingList[] = array(
      	'method' => 'postProcessorToGenerateRTF',
      	'value' => $value,
      	'fieldConfiguration' => $this->fieldConfiguration
    	);
    }

    // Sets a post-processor for query attribute if any
    if ($this->getFieldConfigurationAttribute('query')) {
    	// Sets a post processor
    	$this->postProcessingList[] = array(
      	'method' => 'postProcessorToExecuteQuery',
      	'value' => $value,
      	'fieldConfiguration' => $this->fieldConfiguration
    	);
    }
    
    return $newValue;
  }

	/**
	 * Pre-processor for Checkboxes
	 *
	 * @param mixed $value Value to be pre-processed
	 *
	 * @return mixed
	 */
  protected function preProcessorForCheckboxes($value) {
    $power = 1;
  	$newValue = 0;
  	foreach($value as $checked) {
      if ($checked) {
        $newValue += $power;
      }
      $power = $power <<1;
    }
    return $newValue;
  }

	/**
	 * Pre-processor for Date
	 *
	 * @param mixed $value Value to be pre-processed
	 *
	 * @return mixed
	 */
  protected function preProcessorForDate($value) {
    return $this->date2timestamp($value);
  }

	/**
	 * Pre-processor for DateTime
	 *
	 * @param mixed $value Value to be pre-processed
	 *
	 * @return mixed
	 */
  protected function preProcessorForDateTime($value) {
    return $this->date2timestamp($value);
  }

	/**
	 * Pre-processor for Files
	 *
	 * @param mixed $value Value to be pre-processed
	 *
	 * @return mixed
	 */
  protected function preProcessorForFiles($value) {

    // Gets the uploaded files
    $uploadedFiles = $this->uploadFiles();
    
    // Builds the new value
    foreach ($value as $itemKey => $item) {
      if (isset($uploadedFiles[$itemKey])) {
        $newValue[$itemKey] = $uploadedFiles[$itemKey];
      } else {
        $newValue[$itemKey] = $item;
      }
    }
    
    return implode(',', $newValue);
  }
  
	/**
	 * Pre-processor for RelationManyToManyAsDoubleSelectorbox
	 *
	 * @param mixed $value Value to be pre-processed
	 *
	 * @return mixed
	 */
  protected function preProcessorForRelationManyToManyAsDoubleSelectorbox($value) {

    if($this->getFieldConfigurationAttribute('MM')) {

      if ($this->getFieldConfigurationAttribute('uid') > 0) {
        //True MM
        // Deletes existing fields in the MM table
        $this->deleteRecordsInRelationManyToMany($this->getFieldConfigurationAttribute('MM'), $this->getFieldConfigurationAttribute('uid'));

        // Inserts the new fields
        foreach($value as $itemKey => $item) {
          $this->insertFieldsInRelationManyToMany($this->getFieldConfigurationAttribute('MM'), array(
            'uid_local' => $this->getFieldConfigurationAttribute('uid'),
            'uid_foreign' => $item,
            'sorting' => $itemKey +1 // The order of the selector is assumed
            )
          );
        }
      } else {
        $this->postProcessingList[] = array(
          'method' => 'postProcessorForRelationManyToManyAsDoubleSelectorbox',
          'value' => $value,
          'fieldConfiguration' => $this->fieldConfiguration
        );

      }
      // The value is replaced by the number of relations
      $value = count($value);
    } else {
      // Comma list
      $value = implode(',', $value);
    }
    return $value;
  }

	/**
	 * Pre-processor for RelationManyToManyAsSubform
	 *
	 * @param mixed $value Value to be pre-processed
	 *
	 * @return mixed
	 */
  protected function preProcessorForRelationManyToManyAsSubform($value) {

    // Sets a post processor
    $this->postProcessingList[] = array(
      'method' => 'postProcessorForRelationManyToManyAsSubform',
      'value' => $value,
      'fieldConfiguration' => $this->fieldConfiguration
    );

    return $value;
  }

	/**
	 * Post-processor for RelationManyToManyAsDoubleSelectorbox
	 *
	 * @param mixed $value Value to be pre-processed
	 *
	 * @return mixed
	 */
  protected function postProcessorForRelationManyToManyAsDoubleSelectorbox($value) {

	  // Gets the last inserted iud
	  $tableName = $this->getFieldConfigurationAttribute('tableName');
		$uid = $this->newInsertedUid[$tableName];
    
		// Deletes existing fields in the MM table
	  $this->deleteRecordsInRelationManyToMany($this->getFieldConfigurationAttribute('MM'), $uid);

	  // Inserts the new fields
	  foreach($value as $itemKey => $item) {
	    $this->insertFieldsInRelationManyToMany($this->getFieldConfigurationAttribute('MM'), array(
	      'uid_local' => $uid,
	      'uid_foreign' => $item,
	      'sorting' => $itemKey +1 // The order of the selector is assumed
	      )
	    );
		}
  }

	/**
	 * Post-processor for RelationManyToManyAsSubform
	 *
	 * @param mixed $value Value to be pre-processed
	 *
	 * @return boolean
	 */
  protected function postProcessorForRelationManyToManyAsSubform($value) {

    // Checks if a new record was inserted in the foreign table
    $foreignTableName = $this->getFieldConfigurationAttribute('foreign_table');
    if (isset($this->newInsertedUid[$foreignTableName])) {
      // Sets the uid_foreign field with the inserted record
      $uidForeign = $this->newInsertedUid[$foreignTableName];
      
      $uid = $this->getFieldConfigurationAttribute('uid');
      if (empty($uid)) {
        // Sets the uid_local field with the inserted record in source table
        $sourceTableName = $this->getFieldConfigurationAttribute('tableName');
        $uidLocal = $this->newInsertedUid[$sourceTableName];
      } else {
        // Sets the uid_local field with the uid
        $uidLocal = $this->getFieldConfigurationAttribute('uid');
      }
      
      $noRelation = $this->getFieldConfigurationAttribute('norelation');
			if (empty($noRelation)) {      

				// Insert the new relation in the MM table
				$rowsCount = $this->getRowsCountInRelationManyToMany($this->getFieldConfigurationAttribute('MM'), $uidLocal);
	      $this->insertFieldsInRelationManyToMany($this->getFieldConfigurationAttribute('MM'), array(
	        'uid_local' => $uidLocal,
	        'uid_foreign' => $uidForeign,
	        'sorting' => $rowsCount + 1 
	        )
	      );
			}
			
      // Sets the count
      $itemCount =  $rowsCount + 1;
  	  $this->resource = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
        /* TABLE   */	$this->getFieldConfigurationAttribute('tableName'),
  		  /* WHERE   */ 'uid='.intval($uidLocal),
  		  /* FIELDS  */	array($this->getFieldConfigurationAttribute('fieldName') => $itemCount)
  	  );
    } 
    
    return true;
  }
  
  /**
	 * Post-processor for sending email.
	 *
	 * @param mixed $value
	 *
	 * @return boolean 
	 */
  protected function postProcessorToSendEmail($value) {
  	
  	// Gets the key of the email button if it was hit
		$formAction = $this->getController()->getUriManager()->getFormActionFromPostVariables();
    if (isset($formAction['saveAndSendMail'])) {
      $sendMailFieldKey = key($formAction['saveAndSendMail']);
    } 
 
		// Checks if the mail can be sent
		$mailCanBeSent = false;
		if ($this->getFieldConfigurationAttribute('mailauto')) {
			// Mail is sent if a field has changed
			// Gets the current row in the edit view after insert or update
			$this->rows['after'] = $this->getCurrentRowInEditView();
			foreach($this->rows['after'] as $fieldKey => $field) {
				if ($field != $this->rows['before'][$fieldKey]) {
					$mailCanBeSent = true;
				}
			}		
		} elseif($this->getFieldConfigurationAttribute('mailalways')) {
			$mailCanBeSent = true;				
		}	elseif (empty($value) && $sendMailFieldKey == $this->getFieldConfigurationAttribute('cryptedFullFieldName')) {
			// A checkbox with an email button was hit
			$mailCanBeSent = true;		
		} 
		
		// Processes additional conditions
		$mailIfFieldSetTo =  $this->getFieldConfigurationAttribute('mailiffieldsetto');
		if (empty($mailIfFieldSetTo) === false) {
			$tableName = $this->getFieldConfigurationAttribute('tableName');
			$fieldName = $this->getFieldConfigurationAttribute('fieldName');
			$mailIfFieldSetToArray = explode(',', $mailIfFieldSetTo);
			$fullFieldName = $tableName . '.' . $fieldName;
			if (empty($this->rows['before'][$fullFieldName]) && in_array($value, $mailIfFieldSetToArray)) {
				$mailCanBeSent = true;	
			} else {
				$mailCanBeSent = false;						
			}	
		}
		
		$fieldForCheckMail = $this->getFieldConfigurationAttribute('fieldforcheckmail');
		$fullFieldName = $this->buildFullFieldName($fieldForCheckMail);
		if (empty($this->rows['before'][$fullFieldName])) {
			$mailCanBeSent = false;					
		}
		
		// Send the email
		if ($mailCanBeSent === true) {
			$mailSuccesFlag = $this->sendEmail();

			// Updates the fields if it is a checkbox with an email button
			if ($mailSuccesFlag && $sendMailFieldKey == $this->getFieldConfigurationAttribute('cryptedFullFieldName')) {
				$tableName = $this->getFieldConfigurationAttribute('tableName');
				$fields = array($this->getFieldConfigurationAttribute('fieldName') => $mailSuccesFlag);
				$uid = $this->getFieldConfigurationAttribute('uid');
				$this->updateFields($tableName, $fields, $uid);
			}
		}

		return false;
  }

  /**
	 * Post-processor for generating email.
	 *
	 * @param mixed $value
	 *
	 * @return boolean 
	 */
  protected function postProcessorToGenerateRtf($value) {
  	
  	// Gets the key of the generate rtf button if it was hit
		$formAction = $this->getController()->getUriManager()->getFormActionFromPostVariables();	
    if (isset($formAction['saveAndGenerateRtf'])) {
      $generateRtfFieldKey = key($formAction['saveAndGenerateRtf']);
    } 
	
		if ($generateRtfFieldKey == $this->getFieldConfigurationAttribute('cryptedFullFieldName')) {
			
	  	// Creates the querier
	    $querierClassName = 'Tx_SavLibraryPlus_Queriers_EditSelectQuerier';
	    $querier = t3lib_div::makeInstance($querierClassName);
	    $querier->injectController($this->getController());
	    $querier->injectQueryConfiguration();  
	    $querier->processQuery();			
	
			// Gets the template
			$templateRtf = $querier->parseFieldTags($this->getFieldConfigurationAttribute('templatertf'));	
			if (empty($templateRtf)) {
				return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectRTFTemplateFileConfig');
			}

			// Checks the rtf extension
			$pathParts = pathinfo($templateRtf);
			if ($pathParts['extension'] != 'rtf') {
				return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectRTFTemplateFileExtension');			
			}
			
			// Reads the file template
			$file = @file_get_contents(PATH_site . $templateRtf);
			if (empty($file)) {
				return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectRTFTemplateFileName');			
			}
			
			// Parses the file
			$file = $querier->parseFieldTags($file);
			
			// Gets the file name for saving the file	
			$saveFileRtf = $querier->parseFieldTags($this->getFieldConfigurationAttribute('savefilertf'));
		
			// Creates the directories if necessary
		  $pathParts = pathinfo($saveFileRtf);
    	$directories = explode('/', $pathParts['dirname']);
    	$path = PATH_site;
    	foreach($directories as $directory) { 
        $path .= $directory;
    		if (!is_dir($path)) {
          if(!mkdir($path)) {
						return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.mkdirIncorrect');          	
          }
    		}
    		$path .= '/';
    	}	

    	// Saves the file
      file_put_contents($path . $pathParts['basename'], $file);

      // Updates the record
			$tableName = $this->getFieldConfigurationAttribute('tableName');
			$fields = array($this->getFieldConfigurationAttribute('fieldName') => $pathParts['basename']);
			$uid = $this->getFieldConfigurationAttribute('uid');
			$this->updateFields($tableName, $fields, $uid);      	
		}
		return true;
  }  

  /**
	 * Post-processor for excuting query.
	 *
	 * @param mixed $value
	 *
	 * @return boolean 
	 */
  protected function postProcessorToExecuteQuery($value) {

  	$extensionConfigurationManager = $this->getController()->getExtensionConfigurationManager();

  	// Checks if query are allowed
  	if (!$extensionConfigurationManager->getAllowQueryProperty()) {
  		return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.queryPropertyNotAllowed');
  	}

  	// Gets the content object
  	$contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();
  	
  	// Gets the queryOnValue attribute
  	$queryOnValueAttribute = $this->getFieldConfigurationAttribute('queryonvalue');
  	if (empty($queryOnValueAttribute) || $queryOnValueAttribute == $value) {
  		$markers = this;
  		 
  		// Sets the markers
  		$markers = $this->buildSpecialMarkers();
  		$markers = array_merge($markers, array('###uidItem###' => $this->getFieldConfigurationAttribute('uid'))); 		
      $markers = array_merge($markers, array('###value###' => $value));

  		// Gets the queryForeach attribute      
  		$queryForeachAttribute = $this->getFieldConfigurationAttribute('queryforeach');  		 
  		if (empty($queryForeachAttribute) === false) {
  			$foreachCryptedFieldName = Tx_SavLibraryPlus_Controller_AbstractController::cryptag($this->buildFullFieldName($queryForeachAttribute));
        $foreachValues = explode(',', current($this->postVariables[$foreachCryptedFieldName]));

  		  foreach($foreachValues as $foreachValue) {
					$markers['###' . $queryForeachAttribute . '###'] = $foreachValue;
          $temporaryQueryStrings = $contentObject->substituteMarkerArrayCached($this->getFieldConfigurationAttribute('query'), $markers, array(), array());
          $queryStrings = explode(';', $temporaryQueryStrings);
          foreach($queryStrings as $queryString) {
            $resource = $GLOBALS['TYPO3_DB']->sql_query($queryString);
            if ($GLOBALS['TYPO3_DB']->sql_error($resource)) {
            	Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectQueryInQueryProperty');
              break;
            }
          }
        }          
  		} else {
      	$temporaryQueryStrings = $contentObject->substituteMarkerArrayCached($this->getFieldConfigurationAttribute('query'), $markers, array(), array());
        $queryStrings = explode(';', $temporaryQueryStrings);
        foreach($queryStrings as $queryString) {
          $resource = $GLOBALS['TYPO3_DB']->sql_query($queryString);            
          if ($GLOBALS['TYPO3_DB']->sql_error($resource)) {
          	Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectQueryInQueryProperty');
            break;
          }
        }
  		}		
  	}
  	return true;
  }    
  
	/**
	 * Verifier which calls the method according to the type
	 *
	 * @param mixed $value Value to be verified
	 *
	 * @return boolean
	 */
  protected function verifier($value) {
    $fieldType = $this->getFieldConfigurationAttribute('fieldType');
    $verifierMethod = 'verifierFor' . $fieldType;

    if (method_exists($this,$verifierMethod)) {
      return $this->$verifierMethod($value);
    } else {
      return true;
    }
  }
  
	/**
	 * Verifier for Integer
	 *
	 * @param mixed $value Value to be pre-processed
	 *
	 * @return mixed
	 */
  protected function verifierForInteger($value) {
    if (preg_match('/^[-]?\d+$/', $value) == 0) {
      self::$doNotAddValueToUpdateOrInsert = true;
      return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.isNotValidInteger', array($value));
    } else {
      return true;
    }
  }

	/**
	 * Returns true if a field is required
	 * 
	 * @param none
	 * 
	 * @return boolean
	 */
  protected function isRequired() {
  	return ($this->fieldConfiguration['required'] || preg_match('/required/', $this->fieldConfiguration['eval'])>0);	
  }

	/**
	 * Inserts fields in a table
	 *
	 * @param $tableName string Table name
	 * @param $fields array Fields to insert
	 *
	 * @return none
	 */
  protected function insertFields($tableName, $fields) {

    // Inserts the fields in the storage page if any or in the current page by default
    $storagePage = $this->getController()->getExtensionConfigurationManager()->getStoragePage();
    $fields = array_merge($fields,
      array('pid' => ($storagePage ? $storagePage : $GLOBALS['TSFE']->id))
    );
    
    // Processes the insert query and sets the uid
    $newInsertedUid = parent::insertFields($tableName,$fields);
    if($tableName == $this->getQueryConfigurationManager()->getMainTable()) {
      Tx_SavLibraryPlus_Managers_UriManager::setCompressedParameters(
        Tx_SavLibraryPlus_Controller_AbstractController::changeCompressedParameters(
          Tx_SavLibraryPlus_Managers_UriManager::getCompressedParameters(), 'uid', $newInsertedUid
        )
      );
    }
    $this->newInsertedUid[$tableName] = $newInsertedUid;
  }
  
  /**
	 * Gets the current row in edit view
	 *
	 * @param $date string (date to convert)
	 *
	 * @return integer (timestamp)
	 */
	public function getCurrentRowInEditView() {
 	  // Creates the querier
	  $querierClassName = 'Tx_SavLibraryPlus_Queriers_EditSelectQuerier';
	  $querier = t3lib_div::makeInstance($querierClassName);
	  $querier->injectController($this->getController());
	  $querier->injectQueryConfiguration();  
	  $querier->processQuery();	
	  $rows = $querier->getRows();
	  	 
	  return $rows[0];
	}
  
	/**
	 * Converts a date into timestamp
	 *
	 * @param $date string (date to convert)
	 *
	 * @return integer (timestamp)
	 */
	public function date2timestamp($date) {

		// Provides a default format
    if (!$this->getFieldConfigurationAttribute('format')) {
      $format = ($this->getFieldConfigurationAttribute('eval') == 'date' ? '%d/%m/%Y' : '%d/%m/%Y %H:%M');
    } else {
      $format = $this->getFieldConfigurationAttribute('format');
    }

		// Variable array
		$var = array(
		  'd' => array('type' => 'day', 'pattern' => '([0-9]{2})'),
		  'e' => array('type' => 'day', 'pattern' => '([ 0-9][0-9])'),
		  'H' => array('type' => 'hour', 'pattern' => '([0-9]{2})'),
		  'I' => array('type' => 'hour', 'pattern' => '([0-9]{2})'),
		  'm' => array('type' => 'month', 'pattern' => '([0-9]{2})'),
		  'M' => array('type' => 'minute', 'pattern' => '([0-9]{2})'),
		  'S' => array('type' => 'second', 'pattern' => '([0-9]{2})'),
		  'Y' => array('type' => 'year', 'pattern' => '([0-9]{4})'),
		  'y' => array('type' => 'year_without_century', 'pattern' => '([0-9]{2})'),
		  );

		// Intialises the variables
		foreach ($var as $key => $val) {
			$$val = 0;
		}

    // Builds the expression to match the string according to the format
    preg_match_all('/%([deHImMSYy])([^%]*)/', $format, $matchesFormat);
    $exp = '/';
    foreach ($matchesFormat[1] as $key => $match) {
      $exp .= $var[$matchesFormat[1][$key]]['pattern'] .
        '(?:' . str_replace('/', '\/', $matchesFormat[2][$key]) . ')';
    }
    $exp .= '/';

		$out = 0;
    if ($date) {

      if(!preg_match($exp, $date, $matchesDate)) {
        Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectDateFormat');
        self::$doNotAddValueToUpdateOrInsert = true;
  			return $date;
      } else {
        unset($matchesDate[0]);
        foreach($matchesDate as $key => $match) {
          $res[$matchesFormat[1][$key-1]] = $match;
        }
      }

		  // Sets the variables
		  foreach($res as $key => $val) {
        if(array_key_exists($key, $var)) {
          $$var[$key]['type'] = $val;
			  } else {
          Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectDateOption');
          self::$doNotAddValueToUpdateOrInsert = true;
          return '';
        }
		  }

		  // Deals with year without century
		  if ($year_without_century && !$year) {
			  $year = 2000 + $year_without_century;
		  }

		  $out = mktime($hour, $minute, $second, $month, $day, $year);
    }

    return $out;
	}

	/**
	 * Uploads files.
	 *
	 * @param none
	 *
	 * @return array The uploaded files
	 */
  protected function uploadFiles() {
    $uploadedFiles = array();

    // Gets the file array
    $formName = Tx_SavLibraryPlus_Controller_AbstractController::getFormName();
    $files = $GLOBALS['_FILES'][$formName];

    // Gets the crypted full field name
    $cryptedFullFieldName = $this->getFieldConfigurationAttribute('cryptedFullFieldName');

    // If upload folder does not exist, creates it
    $uploadFolder = $this->getFieldConfigurationAttribute('uploadfolder');
    $uploadFolder .= ($this->getFieldConfigurationAttribute('addToUploadFolder') ? '/' . $this->getFieldConfigurationAttribute('addToUploadFolder') : '');

    $error = t3lib_div::mkdir_deep(PATH_site, $uploadFolder);
    if ($error) {
      self::$doNotAddValueToUpdateOrInsert = true;
      return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.cannotCreateDirectoryInUpload', array($uploadFolder));
    }
      
    // Processes the file array
    foreach($files['name'][$cryptedFullFieldName] as $uid => $field) {
      foreach($field as $fileNameKey => $fileName) {
        // Skips the file if there is no file name
        if (empty($fileName)) {
          continue;
        }

        // Checks the size
        if ($files['size'][$cryptedFullFieldName][$uid][$fileNameKey] > $this->getFieldConfigurationAttribute('max_size') * 1024) {
          self::$doNotAddValueToUpdateOrInsert = true;
          return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.maxFileSizeExceededInUpload');
        }

        // Checks the extension
        $path_parts = pathinfo($files['name'][$cryptedFullFieldName][$uid][$fileNameKey]);
        $fileExtension = $path_parts['extension'];
        $allowed = $this->getFieldConfigurationAttribute('allowed');        
        if ($allowed && in_array($fileExtension, explode(',', $allowed)) === FALSE) {
          self::$doNotAddValueToUpdateOrInsert = true;
          return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.forbiddenFileTypeInUpload', array($fileExtension));
        }
        
        if (empty($allowed) && in_array($fileExtension, explode(',', $this->getFieldConfigurationAttribute('disallowed'))) === TRUE) {   
          self::$doNotAddValueToUpdateOrInsert = true;
          return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.forbiddenFileTypeInUpload', array($fileExtension));
        }

        // Uploads the file
    		if (move_uploaded_file($files['tmp_name'][$cryptedFullFieldName][$uid][$fileNameKey], $uploadFolder . '/' . $files['name'][$cryptedFullFieldName][$uid][$fileNameKey]) === FALSE) {
          self::$doNotAddValueToUpdateOrInsert = true;
          return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.uploadAborted');
    		}
    		$uploadedFiles[$fileNameKey] = $files['name'][$cryptedFullFieldName][$uid][$fileNameKey];
  		}
    }
    
    return $uploadedFiles;
  }

	/**
	 * Sends an email.
	 *
   * @param none
 	 *
	 * @return boolean True if sent successfully
	 */
  public function sendEmail() {

  	// Calls the querier
    $querierClassName = 'Tx_SavLibraryPlus_Queriers_EditSelectQuerier';
    $querier = t3lib_div::makeInstance($querierClassName);
    $querier->injectController($this->getController());
    $querier->injectQueryConfiguration();  
    $querier->processQuery();
  	
  	// Gets the content object
  	$contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();

  	// Processes  the email sender
  	$mailSender = $this->getFieldConfigurationAttribute('mailsender');
  	if (empty($mailSender)) {
  		$mailSender = '###user_email###';
  	}
    $mailSender = $contentObject->substituteMarkerArrayCached(
      $mailSender,
      array('###user_email###' => $GLOBALS['TSFE']->fe_user->user['email']),
      array(),
      array()
    );

    // Processes the mail receiver
    $mailReceiverFromQuery = $this->getFieldConfigurationAttribute('mailreceiverfromquery');
    
    if (empty($mailReceiverFromQuery) === false) {
    	$mailReceiverFromQuery = $querier->parseLocalizationTags($mailReceiverFromQuery); 
    	$mailReceiverFromQuery = $querier->parseFieldTags($mailReceiverFromQuery); 

      // Checks if the query is a SELECT query and for errors
      if ($this->isSelectQuery($mailReceiverFromQuery) === false) {
        return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.onlySelectQueryAllowed', array($this->getFieldConfigurationAttribute('fieldName')));            
      } elseif (!($resource = $GLOBALS['TYPO3_DB']->sql_query($mailReceiverFromQuery))) {
        return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectQueryInContent', array($this->getFieldConfigurationAttribute('fieldName')));
      } 
     	// Processes the query
      $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resource);
      $mailReceiver = $row['email'];      
    } elseif ($this->getFieldConfigurationAttribute('mailreceiverfromfield')) {
    	$mailReceiver = $querier->getFieldValueFromCurrentRow($querier->buildFullFieldName($this->getFieldConfigurationAttribute('mailreceiverfromfield')));
    } elseif ($this->getFieldConfigurationAttribute('mailreceiver')) {
      $mailReceiver = $this->getFieldConfigurationAttribute('mailreceiver');
    } else {
       return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.noEmailReceiver');    	
    }
		
    // Checks if a language configuration is set for the message
    $mailMessageLanguageFromField = $this->getFieldConfigurationAttribute('mailmessagelanguagefromfield');
    if (empty($mailMessageLanguageFromField) === false) {
    	$mailMessageLanguage = $querier->getFieldValueFromCurrentRow($querier->buildFullFieldName($mailMessageLanguageFromField));
    } else {
    	$mailMessageLanguage = $this->getFieldConfigurationAttribute('mailmessagelanguage');
    }

    // Changes the language key
    if (empty($mailMessageLanguage) === false) {
      // Saves the current language key
      $languageKey = $GLOBALS['TSFE']->config['config']['language'];
      // Sets the new language key
      $GLOBALS['TSFE']->config['config']['language'] = $mailMessageLanguage;
    }
   
		// Gets the message and the subject for the mail
		$mailMessage = $this->getFieldConfigurationAttribute('mailmessage');
		$mailSubject = $this->getFieldConfigurationAttribute('mailsubject');
				
    // Replaces the field tags in the message and the subject, i.e. tags defined as ###tag###
    // This first pass is used to parse either the content or tags used in localization tags
		$mailMessage = $querier->parseFieldTags($mailMessage);
		$mailSubject = $querier->parseFieldTags($mailSubject);
       
    // Replaces localization tags in the message and the subject, i.e tags defined as $$$tag$$$ from the locallang.xml file.
		$mailMessage = $querier->parseLocalizationTags($mailMessage);
		$mailSubject = $querier->parseLocalizationTags($mailSubject);
		
		// Replaces the field tags in the message and the subject, i.e. tags defined as ###tag###
		$mailMessage = $querier->parseFieldTags($mailMessage);
		$mailSubject = $querier->parseFieldTags($mailSubject);
				
    // Resets the language key
    if (empty($mailMessageLanguage) === false) {
      $GLOBALS['TSFE']->config['config']['language'] = $languageKey;
    }
    
    // Encodes the mail subject
    $mailSubject = mb_encode_mimeheader($mailSubject, 'iso-8859-1', 'Q');
    
    // Adds html tags 
    if (mb_check_encoding($mailMessage, 'utf-8')) {
    	$mailMessage = utf8_decode($mailMessage);
    }
    $mailMessage = '<head><base href="' . t3lib_div::getIndpEnv('TYPO3_SITE_URL') . '" /></head><html>' . nl2br($mailMessage) . '</html>';

    // Builds the header
    $charset = mb_detect_encoding($mailMessage);
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: ' . $mailSender . "\r\n";
    $headers .= 'Reply-To: ' . $mailSender . "\r\n";
    $headers .= 'Return-Path: ' . $mailSender . "\r\n";
    $mailCarbonCopy = $this->getFieldConfigurationAttribute('mailcc');
    if (empty($mailCarbonCopy) === false) {
      $headers .= 'Cc: ' . $mailCarbonCopy . "\r\n";
    }

    // Sends the email
    if (!ini_get('safe_mode')) {
			// If safe mode is on, the fifth parameter to mail is not allowed,
      // so the fix wont work on unix with safe_mode=On
      return @mail($mailReceiver, $mailSubject, $mailMessage, $headers, '-f'.$mailSender);
    } else {
      return @mail($mailReceiver, $mailSubject, $mailMessage, $headers);
    }
  }

}
?>
