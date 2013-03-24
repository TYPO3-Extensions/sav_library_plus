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
 * Default Export Execute Select Querier.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */

class Tx_SavLibraryPlus_Queriers_ExportExecuteSelectQuerier extends Tx_SavLibraryPlus_Queriers_ExportSelectQuerier {

  /**
   * The xml reference array
   *
   * @var array
   */	
	protected $xmlReferenceArray = array();
  	
  /**
   * The reference counter
   *
   * @var integer
   */	
	protected $referenceCounter = 0;
	
  /**
   * The  output file handle
   *
   * @var integer
   */	
	protected $outputFileHandle;

  /**
   * The previous marker array
   *
   * @var array
   */	  
  protected $previousMarkerArray = array();
  		
  /**
   * Executes the query
   *
   * @param none
   *
   * @return none
   */
  protected function executeQuery() {
  	
    // Executes the select query to get the field names
		$this->resource = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			/* SELECT   */	$this->buildSelectClause(),
			/* FROM     */	$this->buildFromClause(),
 			/* WHERE    */	$this->buildWhereClause(),
			/* GROUP BY */	$this->buildGroupByClause(),
			/* ORDER BY */  $this->buildOrderByClause(),
			/* LIMIT    */  $this->buildLimitClause()
		);
		 
    // Exports the data in CSV
    if (count($this->getController()->getUriManager()->getPostVariablesItem('fields')) > 0) {
    	$exportStatus = $this->exportDataInCsv();
    }
  	
  	// Gets the post variables
  	$postVariables = $this->getController()->getUriManager()->getPostVariables();

  	// Sets the export configuration
  	$this->exportConfiguration = $postVariables; 
  	
  	// Creates the file link, if needed
   	if (is_string($exportStatus)) {
    	// Builds a link to the file
    	$extensionConfigurationManager = $this->getController()->getExtensionConfigurationManager();
    	$typoScriptConfiguration = array(
      	'parameter' => $this->getTemporaryFilesPath(true) . $exportStatus,
      	'extTarget'  => '_blank',   
    	);
    	$message = Tx_SavLibraryPlus_Controller_FlashMessages::translate('general.clickHereToDowload');
    	$this->exportConfiguration['fileLink'] = $extensionConfigurationManager->getExtensionContentObject()->typoLink($message, $typoScriptConfiguration);
    }
  	
  }

  /**
   * Builds the WHERE BY Clause.
   *
   * @param none
   *
   * @return string
   */ 
  protected function buildWhereClause() {

  	// Gets the extension configuration manager
  	$extensionConfigurationManager = $this->getController()->getExtensionConfigurationManager();

  	// Initializes the WHERE clause
    $whereClause = $this->getController()->getUriManager()->getPostVariablesItem('whereClause');
    if (empty($whereClause)) {
    	$whereClause = parent::buildWhereClause(); 
    }
  	    
    // Adds the enable fields conditions for the main table
    $mainTable = $this->queryConfigurationManager->getMainTable();
    $whereClause .= $extensionConfigurationManager->getExtensionContentObject()->enableFields($mainTable);
    
		// Adds the allowed pages condition
    $whereClause .= $this->getAllowedPages($mainTable);
        
    return $whereClause;
  }  
  
   /**
   * Builds the ORDER BY Clause.
   *
   * @param none
   *
   * @return string
   */ 
  protected function buildOrderByClause() {
  	
  	$orderByClause = $this->getController()->getUriManager()->getPostVariablesItem('orderByClause');
  	if (empty($orderByClause)) {
  		$orderByClause = parent::buildOrderByClause();
  	} 
  	
		return $orderByClause;
  } 

  /**
   * Builds the GROUP BY Clause.
   *
   * @param none
   *
   * @return string
   */ 
  protected function buildGroupByClause() {
  	$groupByClause = $this->getController()->getUriManager()->getPostVariablesItem('groupByClause');
  	$exportMM = $this->getController()->getUriManager()->getPostVariablesItem('exportMM');
  	if (empty($groupByClause) && empty($exportMM)) {
  		$groupByClause = parent::buildGroupByClause();
  	}
  	
		return $groupByClause;
  } 
  
   /**
   * Builds the LIMIT BY Clause.
   *
   * @param none
   *
   * @return string
   */ 
  protected function buildLimitClause() {
		return '';
  }    
	
  /**
   * Processes the query
   *
   * @param none
   *
   * @return none
   */
  protected function exportDataInCsv() {  	
		// Gets the extension key
  	$extensionKey = $this->getController()->getExtensionConfigurationManager()->getExtensionKey();
  	
    // Creates the directory in typo3temp if it does not exist
    if (!is_dir('typo3temp/' . $extensionKey)) {
      mkdir('typo3temp/' . $extensionKey);
    }
      
    // Gets the path for the files
		$filePath = $this->getTemporaryFilesPath();
	
    // Checks if a XML file is set
    $xmlFile = $this->getController()->getUriManager()->getPostVariablesItem('xmlFile'); 
    if (empty($xmlFile) === false) {
    	if ($this->processXmlFile($xmlFile) === false) {
    		return false;
    	}
    }
    
    // Sets the output file
    $outputFileName = Tx_SavLibraryPlus_Controller_AbstractController::getFormName() . date('_Y_m_d_H_i') . '.csv';
    t3lib_div::unlink_tempfile($outputFileName);
    
    // Opens the output file
    $this->outputFileHandle = fopen($filePath . $outputFileName, 'ab');
    if ($this->outputFileHandle === false) {
    	return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.fileOpenError', array($outputFileName));
    }
		  
    // Exports the field names if requested and there is no XML file
    $exportFieldNames = $this->getController()->getUriManager()->getPostVariablesItem('exportFieldNames');
    if (empty($exportFieldNames) === false && empty($xmlFile)) {
  		$values = array();
      $orderedFieldList = explode(';', preg_replace('/[\n\r]/', '', $this->getController()->getUriManager()->getPostVariablesItem('orderedFieldList')));     
      $fields = $this->getController()->getUriManager()->getPostVariablesItem('fields');
      $fieldNames = array_merge($orderedFieldList, array_diff(array_keys($fields), $orderedFieldList));
    	foreach($fieldNames as $fieldNameKey => $fieldName) {
        if ($fields[$fieldName]['selected'] || $fields[$fieldName]['render']) {
          $values[] = $fieldName;
        }
      }
      fwrite($this->outputFileHandle, $this->csvValues($values, ';') . chr(10));
    }    
   
  	// Processes the rows
    $counter = 0;
    while ($this->rows[0] = $this->getRowWithFullFieldNames($counter++)) {
      // Processes the row
      $markers = $this->processRow();   

      // Checks if a XML file is set
      if (empty($xmlFile)) {
         // Writes the content to the output file
        fwrite($this->outputFileHandle, $this->csvValues($markers, ';') . chr(10));
      } else {	
         if ($this->processXmlReferenceArray($this->rows[0], $markers) === false) {
            return false;
         }
      } 
       
      // The current row is kept for post processing
      $previousRow = $this->rows[0];     
    } 

    // Post-processes the XML file if any
    if (empty($xmlFile) === false) {
      // Processes last markers
      if($this->postprocessXmlReferenceArray($previousRow, $markers) === false) {
        return false;
      }
    }  

		// Checks if a XLST file is set
		$xsltFile = $this->getController()->getUriManager()->getPostVariablesItem('xsltFile');      
		if (empty($xsltFile) === false) {
			if ($this->processXsltFile($outputFileName) === false) {
				return false;
      }
    } elseif (empty($xmlFile) === false) {
			// Gets the xml file name from the last item in the reference array
      end($this->xmlReferenceArray);
      if (key($this->xmlReferenceArray)) {
        fclose($this->outputFileHandle);
        $xmlfileName = key($this->xmlReferenceArray) . '.xml';
        
        // Copies and deletes the temp file
        copy($filePath . $xmlfileName, $filePath . $outputFileName);
        unlink($filePath . $xmlfileName);
      } else {
        fclose($this->outputFileHandle);

        $xmlfileName = $xmlFile;
        $xmlfilePath = PATH_site;
        // Copies the file
        $errors['copy'] = copy($xmlfilePath . $xmlfileName, $filePath . $outputFileName);
      }
    } else {
    	fclose($this->outputFileHandle); 	
    }
        
    clearstatcache();
		t3lib_div::fixPermissions($filePath . $outputFileName);

		// Checks if an Exec command exists, if allowed
		if ($this->getController()->getExtensionConfigurationManager()->getAllowExec()) {			
			$exec = $this->getController()->getUriManager()->getPostVariablesItem('exec');
      if (empty($exec) === false) {
        // Replaces some tags
        $cmd = str_replace('###FILE###', $filePath . $outputFileName, $exec);
        $cmd = str_replace('###SITEPATH###', dirname(PATH_thisScript), $cmd);

        // Processes the command if not in safe mode
        if (!ini_get('safe_mode')) {
          $cmd = escapeshellcmd($cmd);
        }
            
        // Special processing for white spaces in windows directories      
        $cmd = preg_replace('/\/(\w+(?:\s+\w+)+)/', '/"$1"', $cmd);

        // Executes the command
        exec ($cmd);
        return true;
       }
    }	
		
    return $outputFileName;  	
  }  
  
  /**
	 * Processes the xslt file
	 *
	 * @param	string $fileName
	 *
	 * @return boolean Returns false if an error occured, true otherwise
	 */
  protected function processXsltFile($fileName) {
  
		// Gets the xslt file
		$xsltFile = $this->getController()->getUriManager()->getPostVariablesItem('xsltFile');
		
		if (file_exists($xsltFile)) {
          
    	// Gets the xml file name from the last item in the reference array
      end($this->xmlReferenceArray);
      $xmlfileName = key($this->xmlReferenceArray) . '.xml';
    
    	// Gets the path for the files
			$filePath = $this->getTemporaryFilesPath();      

      // Loads the XML source
      $xml = new DOMDocument;
      libxml_use_internal_errors(true);
      if (@$xml->load($filePath . $xmlfileName) === false) {   
      	$extensionConfigurationManager = $this->getController()->getExtensionConfigurationManager();   
      	$typoScriptConfiguration['parameter'] = 'typo3temp/' . $extensionConfigurationManager->getExtensionKey() . '/' . $xmlfileName;
        $typoScriptConfiguration['target'] = '_blank';
        Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectXmlProducedFile', 
        	array($extensionConfigurationManager->getExtensionContentObject()->typoLink(
        		Tx_SavLibraryPlus_Controller_FlashMessages::translate('error.xmlErrorFile'), $typoScriptConfiguration)
          )  
        );

        // Gets the errors
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
          Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.xmlError', array($error->message, $error->line));
        } 
        libxml_clear_errors();
        return false;
      }

      // Loads the xslt file
      $xsl = new DOMDocument;
      if (@$xsl->load($xsltFile) === false) {
				Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectXsltFile', array($xsltFile));
        return false;
      }

      // Configures the transformer
      $proc = new XSLTProcessor;
      $proc->importStyleSheet($xsl); // attach the xsl rules

      // Writes the result directly
      fclose($this->outputFileHandle);
      $bytes = @$proc->transformToURI($xml, 'file://' . $filePath . $fileName);
			if ($bytes === false) {
				Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectXsltResult');
        return false;
      }
            
      // Deletes the xml file
      unlink($filePath . $xmlfileName);
      return true;
    } else {
			Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.fileDoesNotExist', array($xsltFile));
      return false;
    } 	
  }
  
  /**
	 * Gets the path of temporary files
	 *
	 * @param	boolean Optional, if true returns the relative path
	 *
	 * @return string The path
	 */
  protected function getTemporaryFilesPath($relativePath = false) {
  	
  	// Sets the path site
  	$pathSite = ($relativePath === false ? PATH_site : '');
  	
		// Gets the extension key
  	$extensionKey = $this->getController()->getExtensionConfigurationManager()->getExtensionKey();
      
    // Sets the path for the files
		$path = $pathSite . 'typo3temp/' . $extensionKey . '/';

		return $path;
  } 

 	/**
	 * Processes a row
	 *
	 * @param	none
	 *
	 * @return	array The markers
	 */
  protected function processRow() {
    // Initializes the markers array
    $markers = array();
 	
		// Gets the field names
    $orderedFieldList = explode(';', preg_replace('/[\n\r]/', '', $this->getController()->getUriManager()->getPostVariablesItem('orderedFieldList')));     
		$fields = $this->getController()->getUriManager()->getPostVariablesItem('fields');
    $fieldNames = array_merge($orderedFieldList, array_diff(array_keys($fields), $orderedFieldList));
    
    // Gets the fields configuration
    $fieldsConfiguration = explode(';', preg_replace('/[\n\r]/', '', $this->getController()->getUriManager()->getPostVariablesItem('fieldsConfiguration')));
    
		$additionalFieldsConfiguration = array();
		foreach ($fieldsConfiguration as $fieldConfiguration) {
			if (empty($fieldConfiguration) === false) {
				preg_match('/(\w+\.\w+)\.([^=]+)\s*=\s*(.*)/', $fieldConfiguration, $matches);
				$additionalFieldsConfiguration[$matches[1]][trim(strtolower($matches[2]))] = $matches[3];
			}			
		}

    foreach ($fieldNames as $fieldNameKey => $fieldName) {    	
    	// Checks if the field is selected
    	if ($fields[$fieldName]['selected']) {   		
    		// Sets the marker according to the rendering mode
    		if (empty($fields[$fieldName]['render'])) {
    			// Raw rendering : the value is taken from the row
    			$markers['###' . $fieldName . '###'] = $this->getFieldValueFromCurrentRow($fieldName);
    		} else {
    			// Renders the field based on the TCA configuration as it would be rendered in a single view 			
    			$basicFieldConfiguration = $this->getController()->getLibraryConfigurationManager()->searchBasicFieldConfiguration(Tx_SavLibraryPlus_Controller_Controller::cryptTag($fieldName));    		 
    		
    			// Adds the basic configuration, if found, to the TCA 
    			if (is_array($basicFieldConfiguration)) {
    				$fieldConfiguration = array_merge(Tx_SavLibraryPlus_Managers_TcaConfigurationManager::getTcaConfigFieldFromFullFieldName($fieldName), $basicFieldConfiguration);
    			} else {
    				// Builds the basic configuration from the TCA
    				$fieldConfiguration = Tx_SavLibraryPlus_Managers_TcaConfigurationManager::buildBasicConfigurationFromTCA($fieldName);
    			}
    		  // Adds the additional field configuration
    		  if (is_array($additionalFieldsConfiguration[$fieldName])) {
			    	$fieldConfiguration = array_merge($fieldConfiguration, $additionalFieldsConfiguration[$fieldName]);
    		  }
       		
					// Checks if the fieldType is set		
					if (isset($fieldConfiguration['fieldType'])) {
						// Adds the value to the field configuration
						$fieldConfiguration['value'] = $this->getFieldValueFromCurrentRow($fieldName);
						
						// Calls the item viewer
	      		$className = 'Tx_SavLibraryPlus_ItemViewers_Default_' . $fieldConfiguration['fieldType'] . 'ItemViewer';
	      		$itemViewer = t3lib_div::makeInstance($className);
	      		$itemViewer->injectController($this->getController());
	      		$itemViewer->injectItemConfiguration($fieldConfiguration);        	
	      		$markers['###' . $fieldName . '###'] = $itemViewer->render();   
					} else {
						// Raw rendering
    				$markers['###' . $fieldName . '###'] = $this->getFieldValueFromCurrentRow($fieldName);		
					}   			    			
    		}
    	}
    }

    return $markers;
  }

   
 	/**
	 * Processes the XML file
	 *
	 * @param	$row array		row of data
	 * @param $markerArray  array of markers
	 *
	 * @return	boolean		true if OK
	 */
	protected function processXmlReferenceArray($row, $markerArray) {
		// Gets the content object
		$contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();

    // Special processing
    foreach ($markerArray as $key => $value) {
      // Replaces &nbsp; by a space
      $markerArray[$key] = str_replace('&nbsp;', ' ', $markerArray[$key]);
    
      // Replaces & by &amp;
      $markerArray[$key] = str_replace('& ', '&amp; ', $markerArray[$key]);

      // Suppresses empty tags
      $markerArray[$key] = preg_replace('/<[^\/>][^>]*><\/[^>]+>/', '', $markerArray[$key]);
    }

    // Sets the file Path
    $filePath = $this->getTemporaryFilesPath();
    
    // Checks if a replaceDistinct id has changed
    foreach ($this->xmlReferenceArray as $key => $value) {
      switch ($value['type']) {
        case 'replacedistinct':
          if ($row[$value['id']] != $value['fieldValue']) {
            if (!is_null($value['fieldValue'])) {
              // Sets all the previous replaceDistinct ids to "changed"
              $this->recursiveChangeField($key, 'changed', true);
            }
            $this->xmlReferenceArray[$key]['fieldValue'] = $row[$value['id']];
          } 
          break;
      }
    }

    // Processes the replaceDistinct and cutter parts
    foreach ($this->xmlReferenceArray as $key => $value) {

      switch ($value['type']) {
        case 'emptyifsameasprevious':
          // Parses the template with the known markers
          $buffer = ($this->isInUtf8() ? $value['template'] : utf8_decode($value['template']));
          $buffer = $contentObject->substituteMarkerArrayCached(
            $buffer,
            $markerArray,
            array(),
            array()
          );
          // Keeps the value in the XML reference array
          $this->xmlReferenceArray[$key]['fieldValue'] = $buffer;

          break;
        case 'replacedistinct':
          if ($value['changed']) {
            // Parses the template with the previous known markers
            $buffer = ($this->isInUtf8() ? $value['template'] : utf8_decode($value['template']));
            $buffer = $contentObject->substituteMarkerArrayCached(
              $buffer,
              $this->previousMarkerArray,
              array(),
              array()
            );

            $fileName = $key . '.xml';
            if(!$this->replaceReferenceMarkers($filePath, $fileName, $buffer)) {
              return false;
            }
            
            $this->recursiveChangeField($key, 'changed', false);
            $this->unlinkReplaceAlways($filePath, $key);          
          } 
          break;
        case 'cutifnull':
        case 'cutifempty':
        case 'cutifnotnull':
        case 'cutifnotempty':
        case 'cutifequal':
        case 'cutifnotequal':
        case 'cutifgreater':
        case 'cutifless':
        case 'cutifgreaterequal':
        case 'cutiflessequal':

          // Sets the file name
          $fileName = $key . '.xml';

          // Sets the field value
          $value['fieldValue'] = $row[$value['id']];
          
          // The processing of the cutters depends on their place with respect to the replaceAlways attribute
          $isChildOfReplaceAlways = $this->isChildOfReplaceAlways($key);
          if ($isChildOfReplaceAlways) {
            $value['changed'] = true;
            $fieldValue = $value['fieldValue'];
            $marker = $markerArray;
          } else {
            $fieldValue = $value['previousFieldValue'];
            $marker = $this->previousMarkerArray;
          }
          
          // Sets the condition
          switch ($value['type']) {
            case 'cutifnull':
            case 'cutifempty':
              $condition = empty($fieldValue);
              break;
            case 'cutifnotnull':
            case 'cutifnotempty':
              $condition = !empty($fieldValue);
              break;
            case 'cutifequal':
              $condition = ($fieldValue == $value['value']);
              break;
            case 'cutifnotequal':
              $condition = ($fieldValue != $value['value']);
              break;
            case 'cutifgreater':
              $condition = ($fieldValue > $value['value']);
              break;
            case 'cutifless':
              $condition = ($fieldValue > $value['value']);
              break;
            case 'cutifgreaterequal':
              $condition = ($fieldValue >= $value['value']);
              break;
            case 'cutiflessequal':
              $condition = ($fieldValue <= $value['value']);
              break;
          }

          // Checks if the field must be replaced
          if ($value['changed'] && !$condition) {

            // Replaces markers in the template
            $buffer = ($this->isInUtf8() ? $value['template'] : utf8_decode($value['template']));
            $buffer = $contentObject->substituteMarkerArrayCached(
                $buffer,
                $marker,
                array(),
                array()
            );

            if(!$this->replaceReferenceMarkers($filePath, $fileName, $buffer)) {
              return false;
            }

            if (!$isChildOfReplaceAlways) {
              $this->recursiveChangeField($key, 'changed', false);
            }

          } else {
            // The field is cut
            $buffer = '';
            if(!$this->replaceReferenceMarkers($filePath, $fileName, $buffer)) {
              return false;
            }
          }

          // Updates the previous fied value
          $this->xmlReferenceArray[$key]['previousFieldValue'] = $value['fieldValue'];

          break;
      }
    }
    
    // Processes the replaceAlways part
    foreach ($this->xmlReferenceArray as $key => $value) {
      switch ($value['type']) {
        case 'replacealways':

		      $fileName = $key . '.xml';

          // Replaces markers in the template
          $buffer = ($this->isInUtf8() ? $value['template'] : utf8_decode($value['template']));
          $buffer = $contentObject->substituteMarkerArrayCached(
            $buffer,
            $markerArray,
            array(),
            array()
          );

          if(!$this->replaceReferenceMarkers($filePath, $fileName, $buffer)) {
            return false;
          }
          break;
      }
    }

    // Keeps the marker array
    $this->previousMarkerArray = $markerArray;

    return true;
  }

 	/**
	 * Process the last markers in the XML file
	 *
	 * @param	$row array		row of data
	 * @param $markerArray  array of markers
	 *
	 * @return	boolean		true if OK
	 */
	protected function postprocessXmlReferenceArray($row, $markerArray) {
		// Gets the content object
		$contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();
		
    // Marks all references as changed
    $replaceDistinct = FALSE;
    foreach($this->xmlReferenceArray as $key => $value) {
      $this->xmlReferenceArray[$key]['changed'] = true;
      switch ($value['type']) {
        case 'replacedistinct':
          $replaceDistinct = TRUE;
          break;
      }
    }
    
    // Processes all the references one more time
    if ($replaceDistinct) {
      if (!$this->processXmlReferenceArray($row, $markerArray)) {
        return false;
      }
    }

    // Sets the file Path
    $filePath = $this->getTemporaryFilesPath();

    // Converts to utf8 only for replaceLast
    $utf8Encode = false;
    $altPattern =  '';

    //Post-processing
    foreach($this->xmlReferenceArray as $key => $value) {
      switch ($value['type']) {
        case 'replacelast':
          $utf8Encode = !$this->isInUtf8();
          $altPattern = '/(?s)(.*)(###)(REF_[^#]+)(###)(.*)/';
        case 'replacelastbutone':

          // Parses the template with the previous known markers
          $buffer = ($this->isInUtf8() ? $value['template'] : utf8_decode($value['template']));
          $buffer = $contentObject->substituteMarkerArrayCached(
            $buffer,
            $this->previousMarkerArray,
            array(),
            array()
          );

          $fileName = $key . '.xml';

          if(!$this->replaceReferenceMarkers($filePath, $fileName, $buffer, $utf8Encode, $altPattern)) {
            return false;
          }
          break;
      }
    }

    return true;
  }
  
  
 	/**
	 * Changes a given field value for all the child of a node
	 *
	 * @param	$keySearch string key
	 * @param	$setField string field to change
	 * @param	$setvalue mixed value for the field
	 *
	 * @return	none
	 */
  protected function recursiveChangeField($keySearch, $setField, $setValue) {
    $this->xmlReferenceArray[$keySearch][$setField] = $setValue;
    foreach ($this->xmlReferenceArray as $key => $value) {
      if($this->xmlReferenceArray[$key]['parent'] == $keySearch) {
        $this->recursiveChangeField($key, $setField, $setValue);
      }
    }
  }
  
 	/**
	 * Unlinks the file associated with a replaceAlways item
	 *
	 * @param	$filePath string	file path
	 * @param	$keySearch string key
	 *
	 * @return	none
	 */
   protected function unlinkReplaceAlways($filePath, $keySearch) {
    foreach ($this->xmlReferenceArray as $key => $value) {
      if ($this->xmlReferenceArray[$key]['parent'] == $keySearch) {
        if ($this->xmlReferenceArray[$key]['type'] != 'replacealways') {
          $this->unlinkReplaceAlways($filePath, $key);
        } elseif (file_exists($filePath . $key . '.xml')) {
          unlink($filePath . $key . '.xml');
        }
      }
    }
  }

 	/**
	 * Checks if the key is a child of a replaceAlways item
	 *
	 * @param	$keySearch string key
	 *
	 * @return	boolean		true if OK
	 */
  protected function isChildOfReplaceAlways($keySearch) {
    $parent = $this->xmlReferenceArray[$keySearch]['parent'];
    while ($parent != NULL) {
      if($this->xmlReferenceArray[$parent]['type'] == 'replacealways') {
        return true;
      } else {
        $parent = $this->xmlReferenceArray[$parent]['parent'];
      }
    }
    return false;
  }

 	/**
	 * Replaces the reference markers
	 *
	 * @param	$filePath string	file path
	 * @param $fileName string file name
	 * @param $template string template containing the markers
	 * @param $mode string mode for the file writing
	 *
	 * @return	boolean		true if OK
	 */
  protected function replaceReferenceMarkers($filePath, $fileName, $template, $utf8Encode = false, $altPattern = '') {

  	// Gets the querier
  	$querier = $this->getController()->getQuerier();

    // Sets the pattern	
    $pattern = '/(?s)(.*?)(<[^>]+>)###(REF_[^#]+)###(<\/[^>]+>)/';
    $pattern = ($altPattern ? $altPattern : $pattern);

    if (preg_match_all($pattern, $template, $matches)) {

  		if ($fileHandle = fopen($filePath . $fileName, 'a')) {
        foreach($matches[0] as $matchKey => $match) {

          // Replaces markers in the template
          $buffer = $matches[1][$matchKey];
          $buffer = ($utf8Encode ? utf8_encode($buffer): $buffer);
          $buffer = $querier->parseConstantTags($buffer);
          $buffer = $querier->parseLocalizationTags($buffer);
          
          fwrite($fileHandle, $buffer);
       
          $fileNameRef = $matches[3][$matchKey] . '.xml';
          if (file_exists($filePath . $fileNameRef)) {
            if ($fileHandleRef = fopen($filePath . $fileNameRef,'r')) {
              while($buffer = fread($fileHandleRef, 2048)) {
                $buffer = ($utf8Encode ? utf8_encode($buffer): $buffer);
                fwrite($fileHandle, $buffer);
              }
              fclose($fileHandleRef);
              unlink($filePath . $fileNameRef);
            } else {
              return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.fileOpenError', array($fileName));
            }
          } else {
            // Error, the file does not exist
            return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.fileDoesNotExist', array($fileNameRef));
          }
          
          // Removes the matched srng from the template
          $template = str_replace($matches[0][$matchKey], '', $template);
        }
      
        // Writes the remaining template
        $template = ($utf8Encode ? utf8_encode($template): $template);        
        $template = $querier->parseConstantTags($template);
        $template = $querier->parseLocalizationTags($template);
        
        fwrite($fileHandle, $template);
        fclose($fileHandle);          
      } else {
        // Error, the file cannot be opened
        return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.fileOpenError', array($fileName));
      }
    } else {
      // No REF_ marker, creates the reference file with the template  
  		if ($fileHandle = fopen($filePath . $fileName, 'a')) {

        // Replaces the localization markers
        $buffer = $template;

        // Checks if there exists SPECIAL_REF markers
        if (preg_match_all('/(<[^>]+>)###SPECIAL_(REF_[^#]+)###(<\/[^>]+>)/', $buffer, $matches)) {
          foreach($matches[0] as $matchKey => $match) {

            // First item, replaces the marker by the field value and sets xmlFusion to true
            if (is_null($this->xmlReferenceArray[$matches[2][$matchKey]]['previousFieldValue'])) {
              $buffer = str_replace($match, $this->xmlReferenceArray[$matches[2][$matchKey]]['fieldValue'], $buffer);
              $this->xmlFusion = true;
            } else {
              // Next items
              if ($this->xmlFusion == true) {
                // Fusion with the previous item is set, just removes the marker
                $buffer = preg_replace('/(<[^>]+>)###SPECIAL_(REF_[^#]+)###(<\/[^>]+>)/', '$1$3', $buffer);
              } else {
                // Fusion is not set, uses the previous field value to replace the marker
                $buffer = str_replace($match, $this->xmlReferenceArray[$matches[2][$matchKey]]['previousFieldValue'], $buffer);
                $this->xmlFusion = true;
              }
              // Checks if current and previous fields are the different to clear the fusion
              if ($this->xmlReferenceArray[$matches[2][$matchKey]]['fieldValue'] != $this->xmlReferenceArray[$matches[2][$matchKey]]['previousFieldValue']) {
                $this->xmlFusion = false;
              }
            }
            $this->xmlReferenceArray[$matches[2][$matchKey]]['previousFieldValue']  = $this->xmlReferenceArray[$matches[2][$matchKey]]['fieldValue'];
          }
        }

        $buffer = ($utf8Encode ? utf8_encode($buffer): $buffer);
        $buffer = $querier->parseConstantTags($buffer);
        $buffer = $querier->parseLocalizationTags($buffer);        

        fwrite($fileHandle, $buffer);
        fclose($fileHandle);
      } else {
        return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.fileOpenError', array($fileName));
      }
    }
    return true;
  }

  /**
	 * Processes a XML file
	 *
	 * @param	string $fileName
	 *
	 * @return	boolean
	 */
  protected function processXmlFile($fileName) {
  	
  	// Checks if the file exists
    if (file_exists($fileName) === false) {
			return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.fileDoesNotExist', array($fileName));
    }	
    
    // Loads and processes the xml file
    $xml = @simplexml_load_file($fileName);
    if ($xml === false) {
      return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectXmlFile', array($postVariables['xmlFile']));
    }
    

    if (!$this->processXmlTree($xml)) {
      return false;
    }

    // Sets the parent field
    foreach ($this->xmlReferenceArray as $referenceKey => $reference) {
      if(preg_match_all('/###(REF_[^#]+)###/', $reference['template'], $matches)) {
        foreach ($matches[0] as $matchKey => $match) {
          $this->xmlReferenceArray[$matches[1][$matchKey]]['parent'] = $referenceKey;
        }
      }
    }    

    // Clears all reference files
    foreach ($this->xmlReferenceArray as $referenceKey => $reference) {
		  $fileName = $this->getTemporaryFilesPath() . $referenceKey . '.xml';
		  if (file_exists($fileName)) {
        unlink($fileName);
      }
    }
  
    return true;
  }  	
 	 	
	/**
	 * Processes the XML tree
	 *
	 * @param	$element object		XML element object
	 *
	 * @return	array		Merged arrays
	 */
  protected function processXmlTree($element) {

    // Processes recursively all nodes
    foreach ($element->children() as $child) {
      if(!$this->processXmlTree($child)) {
        return false;
      }
    }

    $attributes = $element->attributes();
    if ((string) $attributes['sav_type']) {
      $reference = 'REF_' . (int)$this->referenceCounter++;

      $this->xmlReferenceArray[$reference]['type'] = strtolower((string) $attributes['sav_type']);
      $this->xmlReferenceArray[$reference]['id'] = (string) $attributes['sav_id'];
      $this->xmlReferenceArray[$reference]['value'] = (string) $attributes['sav_value'];
      $this->xmlReferenceArray[$reference]['changed'] = false;
      $this->xmlReferenceArray[$reference]['fieldValue'] = NULL;
      $this->xmlReferenceArray[$reference]['previousFieldValue'] = NULL;
      $this->xmlReferenceArray[$reference]['parent'] = NULL;

      // Checks if a reference id has to be set
      switch ($this->xmlReferenceArray[$reference]['type']) {
        case 'replacedistinct':
        case 'cutifnull':
        case 'cutifempty':
        case 'cutifnotnull':
        case 'cutifnotempty':
        case 'cutifequal':
        case 'cutifnotequal':
        case 'cutifgreater':
        case 'cutifless':
        case 'cutifgreaterequal':
        case 'cutiflessequal':
          if (!$this->xmlReferenceArray[$reference]['id']) {
            return Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.xmlIdMissing', array($this->xmlReferenceArray[$reference]['type']));;
          }
          break;
      }
      
      // Removes the repeat attributes
      unset($element[0]['sav_type']);
      unset($element[0]['sav_id']);
      unset($element[0]['sav_value']);

      // Sets the template
      $template = $element->asXML();

      // Checks if there is an xml header in the template
      if(preg_match('/^<\?xml[^>]+>/', $template, $match)) {

        // Removes the header
        $template = str_replace($match[0], '', $template);
        $this->xmlReferenceArray[$reference]['template'] = $template;
        if (!$this->xmlReferenceArray[$reference]['type']) {
          $this->xmlReferenceArray[$reference]['type'] = 'replacelastbutone';
        }

        // Sets the template with relaceLast type
        $lastReference = 'REF_' . (int)$this->referenceCounter++;
        $this->xmlReferenceArray[$lastReference]['template'] = $match[0] . '###' . $reference . '###';
        $this->xmlReferenceArray[$lastReference]['type'] = 'replacelast';
      } else {
        $this->xmlReferenceArray[$reference]['template'] = $template;
      }

      // Deletes all the children
      foreach ($element->children() as $child) {
        unset($element->$child);
      }

      // Replaces the node by the reference or a special reference
      switch ($this->xmlReferenceArray[$reference]['type']) {
        case 'emptyifsameasprevious':
          $element[0] = '###SPECIAL_' . $reference . '###';
          break;
        default:
          $element[0] = '###' . $reference . '###';
          break;
      }

    } else {

      $template = $element->asXML();
      // Checks if there is an xml header in the template
      if(preg_match('/^<\?xml[^>]+>/', $template, $match)) {
        $reference = 'REF_' . (int)$this->referenceCounter++;

        // Removes the header
        $template = str_replace($match[0], '', $template);
        $this->xmlReferenceArray[$reference]['template'] = $template;
        if (!$this->xmlReferenceArray[$reference]['type']) {
          $this->xmlReferenceArray[$reference]['type'] = 'replacelastbutone';
        }

        // Sets the template with replaceLast type
        $lastReference = 'REF_' . (int)$this->referenceCounter++;
        $this->xmlReferenceArray[$lastReference]['template'] = $match[0] . '###' . $reference . '###';
        $this->xmlReferenceArray[$lastReference]['type'] = 'replacelast';
        // Deletes all the children
        foreach ($element->children() as $child) {
          unset($element->$child);
        }
        // Replaces the node by the reference
        $element[0] = '###' . $reference . '###';
      }
    }
    return true;
  }

	/**
	 * Takes a row and returns a CSV string of the values with $delim (default is ,) and $quote (default is ") as separator chars.
	 * Usage: 5
	 *
	 * @param	array		Input array of values
	 * @param	string		Delimited, default is comman
	 * @param	string		Quote-character to wrap around the values.
	 * @return	string		A single line of CSV
	 */
	protected function csvValues($row, $delim=',', $quote='"')	{
		reset($row);
		$out = array();
		while(list(,$value)=each($row))	{
// Modification to keep multiline information		
//			list($valPart) = explode(chr(10),$value);
//			$valPart = trim($valPart);
      $valPart = $value;
			$out[]=str_replace($quote, $quote . $quote, $valPart);
		}
		$str = $quote . implode($quote . $delim . $quote, $out) . $quote;	
		return $str;
	}
  
	/**
	 * Returns true if the rendering is in utf-8.
	 *    
	 * @param	none
	 * @return	boolean
	 */
  protected function isInUtf8() {
    return ($GLOBALS['TSFE']->renderCharset == 'utf-8');  
  }                                                   

}
?>