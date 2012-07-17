<?php
/***************************************************************
 *  Copyright notice
 *
*  (c) 2011 Laurent Foulloy <yolf.typo3@orange.fr>
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
 * Field configurataion manager.
 *
 * @package SavLibraryPlus
 * @subpackage Managers
 * @author Laurent Foulloy <yolf.typo3@orange.fr>
 * @version $ID:$
 */
 
class Tx_SavLibraryPlus_Managers_FieldConfigurationManager {

  /**
   * Pattern for the cutter
   *
   **/
  const CUT_IF_PATTERN = '/
    (?:
      (?:
        \s+
        (?P<connector>[\|&]|or|and)
        \s+
      )?
      (?P<expression>
        (?:\#{3})?
        (?P<lhs>(?:(?:\w+\.)+)?\w+)
        \s*(?P<operator>=|!=)\s*
        (?P<rhs>\w+|\#{3}user\#{3}|\#{3}cruser\#{3})
        (?:\#{3})?
      )
    )
  /x';

  /**
   * The table name
   *
   * @var string
   **/
  protected $tableName;

  /**
   * The controller
   *
   * @var Tx_SavLibraryPlus_Controller_Controller
   */
  protected $controller;
  
  /**
   * The field configuration from the Kickstarter
   *     
   * @var array
   **/
  protected $kickstarterFieldConfiguration;
  
  /**
   * Flac for the cutter
   *     
   * @var boolean
   **/
  protected $cutFlag;
  
  /**
   * Flag telling that the fusion of fields is in progress
   *     
   * @var boolean
   **/
  protected $fusionInProgress = FALSE;

  /**
   * Flag telling that the fusion of fields is pending
   *     
   * @var boolean
   **/
  protected $fusionBeginPending = FALSE;

  /**
   * The local querier
   *
   * @var Tx_SavLibraryPlus_Queriers_AbstractQuerier
   */
  protected $querier = NULL;
  
	/**
	 * Injects the controller
	 * 
	 * @param Tx_SavLibraryPlus_Controller_AbstractController $controller
	 * 
	 * @return none
	 */
  public function injectController($controller) {
    $this->controller = $controller;
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
	 * Injects the local querier
	 * 
	 * @param Tx_SavLibraryPlus_Queriers_AbstractQuerier $querier
	 * 
	 * @return none
	 */
  public function injectQuerier($querier) {
    $this->querier = $querier;
  }   
  
	/**
	 * Gets the querier
	 * 
	 * @param none
	 * 
	 * @return Tx_SavLibraryPlus_Queriers_AbstractQuerier
	 */
  public function getQuerier() {
  	if ($this->querier === NULL) {
    	return $this->getController()->getQuerier();
  	} else {
  		return $this->querier;
  	}
  }  

	/**
	 * Injects the kickstarter field configuration
	 * 
	 * @param array $kickstarterFieldConfiguration
	 * 
	 * @return none
	 */
  public function injectKickstarterFieldConfiguration(&$kickstarterFieldConfiguration) {
    $this->kickstarterFieldConfiguration = $kickstarterFieldConfiguration;
    $this->setFullFieldName();
  }

  /**
	 * Builds the full field name
	 *
	 * @param string $fieldName
	 *
	 * @return string
	 */
  public function buildFullFieldName($fieldName) {
  	
  	$fieldNameParts = explode('.', $fieldName);
  	if(count($fieldNameParts) == 1) {
  		// The tableName is assumed by default
  		$fieldName = $this->kickstarterFieldConfiguration['tableName'] . '.' . $fieldName;
  	}
  	return $fieldName;
  }  
  
	/**
	 * Sets the full field name
	 *
	 * @param none
	 *
	 * @return none
	 */
	public function setFullFieldName() {
		$this->fullFieldName = $this->buildFullFieldName($this->kickstarterFieldConfiguration['fieldName']);
  }
  
	/**
	 * Gets the full field name
	 * 
	 * @param none
	 *
	 * @return string
	 */
	public function getFullFieldName() {
		return $this->fullFieldName;
  }

	/**
	 * Gets the table name
	 *
	 * @param none
	 *
	 * @return string
	 */
	public function getTableName() {
		return $this->kickstarterFieldConfiguration['tableName'];
  }
  
	/**
	 * Gets the fieldName name
	 * 
	 * @param none
	 *
	 * @return string
	 */
	public function getFieldName() {
		return $this->kickstarterFieldConfiguration['fieldName'];
  }

	/**
	 * Builds the fields configuration for a folder.
	 *
	 * @param $folder array (the folder)
	 *
	 * @return array
	 */
  public function getFolderFieldsConfiguration($folder, $flatten = false) {

    $folderFieldsConfiguration = array();
    
    foreach ($folder['fields'] as $fieldId => $kickstarterFieldConfiguration) {

      // Injects the kickstarter configuration
      $this->injectKickstarterFieldConfiguration($kickstarterFieldConfiguration['config']);

      // Builds full field name
      $fullFieldName = $this->getFullFieldName();

      // Gets the configuration
      $fieldConfiguration = $this->getFieldConfiguration();

      // If it is a subform, gets the configuration for each subform field
      if (isset($fieldConfiguration['subform']) && $flatten === true) {
        foreach ($fieldConfiguration['subform'] as $subformFolderKey => $subformFolder) {
          $subfromFolderFieldsConfiguration = $this->getFolderFieldsConfiguration($subformFolder);
          foreach ($subfromFolderFieldsConfiguration as $subfromFolderFieldConfigurationKey => $subfromFolderFieldConfiguration) {
            $subfromFolderFieldsConfiguration[$subfromFolderFieldConfigurationKey ]['parentTableName'] = $fieldConfiguration['tableName'];
            $subfromFolderFieldsConfiguration[$subfromFolderFieldConfigurationKey ]['parentFieldName'] = $fieldConfiguration['fieldName'];
          }
          $fieldConfiguration['subform'] = $subfromFolderFieldsConfiguration;
        }
      } 

      $folderFieldsConfiguration = array_merge($folderFieldsConfiguration, array(
        $fieldId => $fieldConfiguration
        )
      );

    }
    return $folderFieldsConfiguration;
  }
  
	/**
	 * Builds the configuration for the selected field.
	 *
	 * @param none
	 *
	 * @return array
	 */
	public function getFieldConfiguration() {

    // Sets table name and field name
    $tableName = $this->kickstarterFieldConfiguration['tableName'];
    $fieldName = $this->kickstarterFieldConfiguration['fieldName'];

    // Intializes the field configuration
    $fieldConfiguration = array();

    // Adds the tca config field
    $fieldConfiguration = array_merge($fieldConfiguration, Tx_SavLibraryPlus_Managers_TcaConfigurationManager::getTcaConfigField($tableName, $fieldName));

    // Adds external tca configuration for existing tables, if any
    $externalTcaConfiguration = $this->getController()->getLibraryConfigurationManager()->getExternalTcaConfiguration();
    if (is_array($externalTcaConfiguration[$tableName]) && is_array($externalTcaConfiguration[$tableName][$fieldName])) {
    	$fieldConfiguration = array_merge($fieldConfiguration, $externalTcaConfiguration[$tableName][$fieldName]['config']);    	
    }
       
    // Adds the configuration from the kickstarter
    $fieldConfiguration = array_merge($fieldConfiguration, $this->kickstarterFieldConfiguration);
    
    // Adds the configuration from the page TypoScript configuration
    $fullFieldName = $tableName . '.' . $fieldName;
    $viewConfigurationFieldFromPageTypoScriptConfiguration = $this->getController()->getPageTypoScriptConfigurationManager()->getViewConfigurationFieldFromPageTypoScriptConfiguration($fullFieldName);
    if(is_array($viewConfigurationFieldFromPageTypoScriptConfiguration)) {
    	$fieldConfiguration = array_merge($fieldConfiguration, $viewConfigurationFieldFromPageTypoScriptConfiguration);
    }
 
    // Adds the label
    $label = Tx_SavLibraryPlus_Managers_TcaConfigurationManager::getTcaFieldLabel($tableName, $fieldName);
    $fieldConfiguration['label'] = $label;

    // Adds the value
    $fieldConfiguration['value'] = $this->getValue();

    // Adds the required attribute
    $fieldConfiguration['required'] = $fieldConfiguration['required'] || preg_match('/required/', $fieldConfiguration['eval'])>0;
   
    // Adds special attributes 
    $querier = $this->getQuerier();
    if (empty($querier) === false) {
    	// Adds the uid
      $fieldConfiguration['uid'] = $querier->getFieldValueFromCurrentRow('uid');
      // Adds field-based attributes
      $fieldBasedAttribute = $fieldConfiguration['fieldlink'];
      if (empty($fieldBasedAttribute) === false) {
      	$fieldConfiguration['link'] = $querier->getFieldValueFromCurrentRow($querier->buildFullFieldName($fieldBasedAttribute));
      }
      $fieldBasedAttribute = $fieldConfiguration['fieldmessage'];
      if (empty($fieldBasedAttribute) === false) {
      	$fieldConfiguration['message'] = $querier->getFieldValueFromCurrentRow($querier->buildFullFieldName($fieldBasedAttribute));
      }      
    }
    
    // Adds the default class label
    $fieldConfiguration['classLabel'] = $this->getClassLabel();

    // Adds the style for the label if any
    if ($this->kickstarterFieldConfiguration['stylelabel']) {
    	$fieldConfiguration['styleLabel'] = $this->kickstarterFieldConfiguration['stylelabel'];
    }
        
    // Adds the default class value
    $fieldConfiguration['classValue'] = $this->getClassValue();
    
    // Adds the style for the value if any
    if ($this->kickstarterFieldConfiguration['stylevalue']) {
    	$fieldConfiguration['styleValue'] = $this->kickstarterFieldConfiguration['stylevalue'];
    }
    
    // Adds the default class Field
    $fieldConfiguration['classField'] = $this->getClassField();

    // Adds the default class Item
    $fieldConfiguration['classItem'] = $this->getClassItem();
    
    // Adds the cutters (fusion and field)
    $this->setCutFlag();
    $fieldConfiguration['cutDivItemBegin'] = $this->getCutDivItemBegin();
    $fieldConfiguration['cutDivItemInner'] = $this->getCutDivItemInner();
    $fieldConfiguration['cutDivItemEnd'] = $this->getCutDivItemEnd();
    $fieldConfiguration['cutLabel'] = $this->getCutLabel();
    
    // Gets the value from the TypoScript stdwrap property, if any
    if ($this->kickstarterFieldConfiguration['stdwrapvalue']) {
    	$fieldConfiguration['value'] = $this->getValueFromTypoScriptStdwrap($fieldConfiguration['value']);
    }
    
	  // Gets the value from a TypoScript object, if any
    if ($this->kickstarterFieldConfiguration['tsobject']) {
    	$fieldConfiguration['value'] = $this->getValueFromTypoScriptObject();
    }
        
    // Adds the item wrapper if the viewer exists
    $viewer = $this->getController()->getViewer();
    if (empty($viewer) === false) {
      $fieldConfiguration['wrapItem'] = $querier->parseLocalizationTags($this->kickstarterFieldConfiguration['wrapitem']);
      $fieldConfiguration['wrapItem'] = $querier->parseFieldTags($this->kickstarterFieldConfiguration['wrapitem']);
    }

    return $fieldConfiguration;
  }

	/**
	 * Builds the value content.
	 *
	 * @param none
	 *
	 * @return string
	 */
	protected function getValue() { 
	  // Gets the value
    $querier = $this->getQuerier();
    if (empty($querier) === false) {
    	// Checks if an alias attribute is set
    	if (empty($this->kickstarterFieldConfiguration['alias']) === false) {
    		$fieldName = $this->buildFullFieldName($this->kickstarterFieldConfiguration['alias']);
    	} elseif ($querier->fieldExistsInCurrentRow($this->kickstarterFieldConfiguration['fieldName'])) { 
    		$fieldName = $this->kickstarterFieldConfiguration['fieldName'];
    	} else {
    		$fieldName = $this->getFullFieldName();
    	}
  	
    	$viewerCondition = $this->getController()->getviewer() !== NULL && $this->getController()->getViewer()->isNewView() === false;
 			if($this->kickstarterFieldConfiguration['reqvalue'] && $viewerCondition === true) {
				$value = $this->getValueFromRequest();   	
 			} else {
      	$value = $querier->getFieldValueFromCurrentRow($fieldName);
 			}
    }
   
    return $value;    
	} 

	/**
	 * Builds the value content.
	 *
	 * @param mixed $value
	 *
	 * @return string
	 */
	protected function getValueFromTypoScriptStdwrap($value) {

    // The value is wrapped using the stdWrap TypoScript	  
    $querier = $this->getQuerier();      	
    if (empty($querier) === false) {
      $configuration = $querier->parseLocalizationTags($this->kickstarterFieldConfiguration['stdwrapvalue']);
      $configuration = $querier->parseFieldTags($configuration);
    } else {
    	$configuration = $this->kickstarterFieldConfiguration['stdwrapvalue'];
    }

    $TSparser = t3lib_div::makeInstance('t3lib_TSparser');
    $TSparser->parse($configuration);
    $contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();
    $value = $contentObject->stdWrap($value, $TSparser->setup);
    
    return $value;         
  } 
       
	/**
	 * Builds the value content.
	 *
	 * @param none
	 *
	 * @return string
	 */
	protected function getValueFromTypoScriptObject() {

		// Checks if the typoscript properties exist
		if (empty($this->kickstarterFieldConfiguration['tsproperties'])) {
			Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.noAttributeInField', array('tsProperties', $this->kickstarterFieldConfiguration['fieldName']));
			return '';
		}

    // The value is generated from TypoScript
    $querier = $this->getQuerier();    
		if (empty($querier) === false) {
      $configuration = $querier->parseLocalizationTags($this->kickstarterFieldConfiguration['tsproperties']);
      $configuration = $querier->parseFieldTags($configuration);
    } else {
    	$configuration = $this->kickstarterFieldConfiguration['tsproperties'];
    }  
    $TSparser = t3lib_div::makeInstance('t3lib_TSparser');
    $TSparser->parse($configuration);

    $contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();        
    $value = $contentObject->cObjGetSingle($this->kickstarterFieldConfiguration['tsobject'], $TSparser->setup);

    return $value;
  }

  /**
	 * Builds the value content from a request.
	 *
	 * @param none
	 *
	 * @return string
	 */
	protected function getValueFromRequest() {  

		// Gets the querier
    $querier = $this->getController()->getQuerier();
    
    // Gets the query
    $query = $this->kickstarterFieldConfiguration['reqvalue']; 
        
    // Processes localization tags
    $query = $querier->parseLocalizationTags($query);
    $query = $querier->parseFieldTags($query);
    
    // Checks if the query is a select query
    if (!$querier->isSelectQuery($query)) {
    	Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.onlySelectQueryAllowed', array($this->kickstarterFieldConfiguration['fieldName']));
    	return '';
    }
		// Executes the query
		$resource = $GLOBALS['TYPO3_DB']->sql_query($query);
		if ($resource === false) {
    	Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.incorrectQueryInReqValue', array($this->kickstarterFieldConfiguration['fieldName']));
		}

		// Sets the separator
  	$separator = $this->kickstarterFieldConfiguration['separator'];
  	if (empty($separator)) {
  		$separator = '<br />';
  	}

  	// Processes the rows
  	$value = '';
  	while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resource)) {
			// Checks if the field value is in the row
      if (array_key_exists('value', $row)) {
        $value .= ($value ? $separator : '') . $row['value'];
      } else {
      	Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.aliasValueMissingInReqValue', array($this->kickstarterFieldConfiguration['fieldName']));
				return '';
    	}
		}	
		return $value;
	}	
	
	/**
	 * Builds the class for the label.
	 *
	 * @param none
	 *
	 * @return string
	 */
	protected function getClassLabel() {
    if (empty($this->kickstarterFieldConfiguration['classlabel'])) {
      return 'label';
    } else {
      return 'label ' . $this->kickstarterFieldConfiguration['classlabel'];
    }
	}

	/**
	 * Builds the class for the value.
	 *
	 * @param none
	 *
	 * @return string
	 */
	protected function getClassValue() {

    if (empty($this->kickstarterFieldConfiguration['classvalue'])) {
      $class = 'value';
    } else {
      $class =  'value ' . $this->kickstarterFieldConfiguration['classvalue'];
    }

    return $class;
	}

	/**
	 * Builds the class for the field.
	 *
	 * @param none
	 *
	 * @return string
	 */
	protected function getClassField() {

    // Adds subform if the type is a RelationManyToManyAsSubform
    if ($this->kickstarterFieldConfiguration['fieldType'] == 'RelationManyToManyAsSubform') {
      $class .= ' subform';
    } elseif (empty($this->kickstarterFieldConfiguration['classfield'])) {
      $class = 'field';
    } else {
      $class = 'field ' .$this->kickstarterFieldConfiguration['classfield'];
    }
    
    return $class;
	}

	/**
	 * Builds the class for the item.
	 *
	 * @param none
	 *
	 * @return string
	 */
	protected function getClassItem() {

    if (empty($this->kickstarterFieldConfiguration['classitem'])) {
      $class = 'item';
    } else {
      $class =  'item ' . $this->kickstarterFieldConfiguration['classitem'];
    }

    return $class;
	}
  
	/**
	 * <DIV class="label"> cutter: checks if the label must be cut
	 * Returns true if the <DIV> must be cut.
	 *
	 * @param none
	 *
	 * @return boolean
	 */
	protected function getCutLabel() {
    // Cuts the label if the type is a RelationManyToManyAsSubform an cutLabel is not equal to zero
    if ($this->kickstarterFieldConfiguration['fieldType'] == 'RelationManyToManyAsSubform') {
      $cut = true;
    } elseif ($this->kickstarterFieldConfiguration['cutlabel']) {
      $cut = true;
    } else {
      $cut = false;
    }

    return $cut;
	}

	/**
	 * <DIV class="item"> cutter: checks if the beginning of the <DIV> must be cut
	 * Returns true if the <DIV> must be cut.
	 *
	 * @param none
	 *
	 * @return boolean
	 */
	protected function getCutDivItemBegin() {
    $fusionBegin = ($this->kickstarterFieldConfiguration['fusion'] == 'begin') ;
    
    if ($fusionBegin) {
        $this->fusionBeginPending = TRUE;
    }

    $cut = (
      ($this->fusionInProgress && !$fusionBegin) ||
      ($this->getCutFlag() && !$this->fusionInProgress)
    );
    
    if ($this->fusionBeginPending && !$cut) {
      $this->fusionInProgress = TRUE;
      $this->fusionBeginPending = FALSE;
    }

    return $cut;
	}

	/**
	 * <DIV class="item"> cutter: checks if the endt of the <DIV> must be cut
	 * Returns true if the <DIV> must be cut.
	 *
	 * @param none
	 *
	 * @return boolean
	 */
	protected function getCutDivItemEnd() {
    $fusionEnd = ($this->kickstarterFieldConfiguration['fusion'] == 'end');

    $cut = (
      ($this->fusionInProgress && !$fusionEnd) ||
      ($this->getCutFlag() && !$this->fusionInProgress)
    );
    if ($fusionEnd) {
      $this->fusionInProgress = FALSE;
    }
    return $cut;
	}

	/**
	 * <DIV class="item"> cutter: checks if the inner content of the <DIV> must be cut
	 * Returns true if the <DIV> must be cut.
	 *
	 * @param none
	 *
	 * @return boolean
	 */
	protected function getCutDivItemInner() {
    $cut = (
      $this->getCutFlag()
    );
    return $cut;
	}

	/**
	 * Gets the cut flag. If true the content must be cut.
	 *
	 * @return boolean
	 */
	protected function getCutFlag() {
    return $this->cutFlag;
  }

	/**
	 * Sets the cut flag
	 *
	 * @param none
	 *
	 * @return none
	 */
	protected function setCutFlag() {
    $this->cutFlag = $this->cutIfEmpty() | $this->cutIf();
  }

	/**
	 * Content cutter: checks if the content is empty
	 * Returns true if the content must be cut.
	 *
	 * @param none
	 *
	 * @return boolean
	 */
	protected function cutIfEmpty() {

    if ($this->kickstarterFieldConfiguration['cutifnull'] || $this->kickstarterFieldConfiguration['cutifempty']) {
      $value = $this->getValue();    
      return empty($value);
    } else {
      return FALSE;
    }
	}

	/**
	 * Content cutter: checks if the content is empty
	 * Returns true if the content must be cut.
	 *
	 * @param none
	 *
	 * @return boolean
	 */
	public function cutIf() {

    if ($this->kickstarterFieldConfiguration['cutif']) {
    	return $this->processFieldCondition($this->kickstarterFieldConfiguration['cutif']);
    } else {
      return FALSE;
    }
	}

	/**
	 * Processes a field condition
	 *
	 * @param string $fieldCondition
	 *
	 * @return boolean True if the field condition is satisfied
	 */
	public function processFieldCondition($fieldCondition) {

		// Initializes the result
		$result = NULL;
		
		// Gets the querier
    $querier = $this->getController()->getQuerier();
    	
    // Matchs the pattern
    preg_match_all(self::CUT_IF_PATTERN, $fieldCondition, $matches);
     
    // Processes the expressions
    foreach($matches['expression'] as $matchKey => $match) {
      // Processes the left hand side
      $lhs = $matches['lhs'][$matchKey];
      switch ($lhs) {
				case 'group':
					$isGroupCondition = true;
          if (empty($querier) === false) {
      			$lhsValue = $querier->getFieldValueFromCurrentRow($querier->buildFullFieldName('usergroup'));
    			} else {
    				return false;
    			}
          break;
        case 'usergroup':
          $isGroupCondition = true;
  		    $lhsValue = $GLOBALS['TSFE']->fe_user->user['usergroup'];
          break;
        default:
					// Gets the value        	
          if (empty($querier) === false) {
      			$lhsValue = $querier->getFieldValueFromCurrentRow($querier->buildFullFieldName($lhs));
    			} else {
    				return false;
    			}
      }
      // Processes the right hand side
      $rhs = $matches['rhs'][$matchKey];
 
      // Processes special markers
      switch ($rhs) {
        case 'EMPTY':
        	$condition = empty($lhsValue);
        	break;
        case '###user###':
        	$condition = ($lhsValue == $GLOBALS['TSFE']->fe_user->user['uid']);
        	break;
        case '###cruser###':
        	$viewer = $this->getController()->getViewer();
        	// Skips the condition if it is a new view since cruser_id will be set when saved
         	if (empty($viewer) === false && $viewer->isNewView() === true) {
      			continue;
    			} else {
    				$condition = ($lhsValue == $GLOBALS['TSFE']->fe_user->user['uid']);
    			}
        	break; 
        default:
        	if ($isGroupCondition !== true) {
         		$rhsValue = $rhs;       			
        	} else {
            $rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
              /* SELECT */	'uid',
        			/* FROM   */	'fe_groups',
        	 		/* WHERE  */	'title="' . $rhs . '"'
  		      ); 
  		      $rhsValue = $rows[0]['uid'];        			
        	}
        	break;       		
      }
    
      // Processes the condition
      switch ($matches['operator'][$matchKey]) {
        case '=':
          if ($isGroupCondition !== true) {
            $condition = ($lhsValue == $rhsValue);
          } else {
          	$condition = (in_array($rhsValue, explode(',', $lhsValue)) === true);
          }
          break;
        case '!=':
          if ($isGroupCondition !== true) {
            $condition = ($lhsValue != $rhsValue);
          } else {
          	$condition = (in_array($rhsValue, explode(',', $lhsValue)) === false);
          }          	
          break;
      }

      // Processes the connector
      $connector = $matches['connector'][$matchKey];
      
      switch ($connector) {
        case '|':
        case 'or':
          $result = ($result === NULL ? $condition : $cut || $condition);
          break;
        case '&':
        case 'and':
          $result = ($result === NULL ? $condition : $cut && $condition);
          break;
        case '':
          $result = $condition;
          break;
       }
     }
   
     return $result;
	}
	
	
}
