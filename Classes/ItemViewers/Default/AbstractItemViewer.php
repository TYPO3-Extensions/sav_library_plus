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
 * This abstract class for an itemViewer.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
abstract class Tx_SavLibraryPlus_ItemViewers_Default_AbstractItemViewer {

  // Constant for HTML Output
  const EOL = "\n";                      // End of line for HTML output
  const TAB = "\t";                      // Tabulation
  const SPACE = ' ';                     // Space
  const DEFAULT_ITEM_VIEWER = 0;
  const EDIT_ITEM_VIEWER = 1;

  /**
   * The allowed function names
   * 
   * @var array
   */
  static protected $allowedFunctionNames = array('makeItemLink', 'makeNewWindowLink', 'makeDateFormat', 'makeEmailLink', 'makeUrlLink', 'makeLink');
  
  /**
   * The controller
   * 
   * @var Tx_SavLibraryPlus_Controller_Controller
   */
  private $controller;

	/**
	 * @var integer
	 */  
  protected $itemViewerType = self::DEFAULT_ITEM_VIEWER;
  
	/**
	 * @var array
	 */
  protected $itemConfiguration;

	/**
	 * Injects the controller
	 *
	 * @param Tx_SavLibraryPlus_Controller_AbstractController $controller
	 *
	 * @return  none
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
	 * Injects the item configuration
	 *
	 * @param array $itemConfiguration
	 *
	 * @return none
	 */
  public function injectItemConfiguration(&$itemConfiguration) {
    $this->itemConfiguration = $itemConfiguration;
  }
  
	/**
	 * Checks if the item is an edit item viewer
	 *
	 * @param none
	 *
	 * @return boolean
	 */
  public function isEditItemViewer() {
    return ($this->itemViewerType == self::EDIT_ITEM_VIEWER);
  } 
  
	/**
	 * Gets the item configuration for a given key
	 *
	 * @param string $key The key
	 *
	 * @return mixed the item configuration
	 */
  public function getItemConfiguration($key) {
    return $this->itemConfiguration[$key];
  }

	/**
	 * Sets the item configuration for a given key
	 *
	 * @param string $key The key
	 * @param string $value The value
	 *
	 * @return none
	 */
  public function setItemConfiguration($key, $value) {
    $this->itemConfiguration[$key] = $value;
  }

	/**
	 * Returns true if the item configuration for a given key is not set
	 *
	 * @param string $key The key
	 *
	 * @return boolean
	 */
  public function itemConfigurationNotSet($key) {
    return isset($this->itemConfiguration[$key]) ? false : true;
  }
  
	/**
	 * Gets the crypted full field name
	 *
	 * @param none
	 *
	 * @return string The crypted full field name
	 */
  public function getCryptedFullFieldName() {
    return Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($this->getItemConfiguration('tableName') . '.' . $this->getItemConfiguration('fieldName'));
  }  
  
	/**
	 * Renders an item
	 *
	 * @param none
	 *
	 * @return string the rendered item
	 */
  public function render() { 
    // Renders the item
    $content = $this->renderItem();

    // Applies a function if not in edit mode and if any
    if ($this->isEditItemViewer() === false) {
      // Checks if a function should be applied
      $functionName = $this->getItemConfiguration('func');
			if (empty($functionName) === false) {
	      if (in_array($functionName, self::$allowedFunctionNames)) {
	      	// Adds the function letf and right content if any.
	        if (empty($content)) {
	        	$content = $this->getItemConfiguration('funcaddleftifnull') . $content . $this->getItemConfiguration('funcaddrighttifnull');
	        } else {
	        	$content = $this->getItemConfiguration('funcaddleftifnotnull') . $content . $this->getItemConfiguration('funcaddrighttifnotnull');
	        }	    
	        // Calls the function  	
	        $content = $this->$functionName($content);
	      } else {
	      	Tx_SavLibraryPlus_Controller_FlashMessages::addError('error.unknownFunction',array($functionName));
	      }
			}
    }
    
    $content = $this->getLeftValue() . $content . $this->getRightValue();

    // Applies a TypoScript StdWrap to the item, if any
    $stdWrapItem = $this->getItemConfiguration('stdwrapitem');
    if (empty($stdWrapItem) === false) {
    	$TSparser = t3lib_div::makeInstance('t3lib_TSparser');
    	$TSparser->parse($stdWrapItem);
    	$contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();
    	$content = $contentObject->stdWrap($content, $TSparser->setup);
    }
    
    return $content;
  }

	/**
	 * Builds the right value content.
	 *
	 * @param none
	 *
	 * @return string
	 */
	protected function getRightValue() {
		$content = '';
		
		// Gets the value
		$value = $this->getItemConfiguration('value');
		
		// Gets the right part
    if (empty($value)) {
      $content = $this->getItemConfiguration('addrightifnull');
    } else {
      $content = $this->getItemConfiguration('addrightifnotnull');
    }
        
    // Evaluates the function if necessary
    $functionName = $this->getItemConfiguration('funcright');
  	if (empty($functionName) === false) {
  		$this->setItemConfiguration('funcspecial', 'right');
  		if (in_array($functionName, self::$allowedFunctionNames)) {
				$content = $this->$functionName($content);
  		}
    }		
		
    if (empty($content) === false) {
    	$content = $this->getController()->getQuerier()->parseLocalizationTags($content);
    	$content = $this->getController()->getQuerier()->parseFieldTags($content);
    }

    return $content;
  }  
  
	/**
	 * Builds the left value content.
	 *
	 * @param none
	 *
	 * @return string
	 */
	protected function getLeftValue() {
		$content = '';
		
		// Gets the value
		$value = $this->getItemConfiguration('value');
		
		// Gets the left part
    if (empty($value)) {
      $content = $this->getItemConfiguration('addleftifnull');
    } else {
      $content = $this->getItemConfiguration('addleftifnotnull');
    }
        
    // Evaluates the function if necessary
    $functionName = $this->getItemConfiguration('funcleft');
  	if (empty($functionName) === false) {
  		$this->setItemConfiguration('funcspecial', 'left');
  		if (in_array($functionName, self::$allowedFunctionNames)) {
				$content = $this->$functionName($content);
  		}
    }		
		
    if (empty($content) === false) {
    	$content = $this->getController()->getQuerier()->parseLocalizationTags($content);
    	$content = $this->getController()->getQuerier()->parseFieldTags($content);
    }

    return $content;
  }  
  
	/**
	 * Transforms an array of HTML code into HTML code
	 *
	 * @return  string
	 */
  protected function arrayToHTML($htmlArray, $noHTMLprefix = false) {

    if ($noHTMLprefix) {
		  return implode('', $htmlArray);
    } else {
		  return implode(self::EOL . self::SPACE, $htmlArray);
    }
  }

	/**
	 * Creates an item link
	 *
	 * @param string $value Value to display
	 *
	 * @return string The link
	 */
	protected function makeItemLink($value) {

    // Gets the funcspecial attribute
	  $special = $this->getItemConfiguration('funcspecial');
 
	  // Gets the formAction
	  if ($this->getItemConfiguration('updateform' . $special) || $this->getItemConfiguration('formadmin' . $special)) {
	  	$formAction = 'formAdmin';
	  } elseif ($this->getItemConfiguration('inputform' . $special) || $this->getItemConfiguration('edit' . $special)) {
	  	$formAction = 'edit';
	  } else {
	  	$formAction = 'single';
	  }

	  // Builds the parameters
    $formParameters = array(
      'formAction' => $formAction,
      'uid' => $this->getController()->getQuerier()->getFieldValueFromCurrentRow('uid'),
    );

    // Adds parameter to access to a folder tab (page is an alias)
    if ($this->getItemConfiguration('page' . $special)) {
      $formParameters['folderKey'] =
        Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($this->getItemConfiguration('page' . $special));
    }
    if ($this->getItemConfiguration('foldertab' . $special)) {
      $formParameters['folderKey'] =
        Tx_SavLibraryPlus_Controller_AbstractController::cryptTag($this->getItemConfiguration('foldertab' . $special));
    }

    // Sets the cache hash flag
 		$cacheHash = (Tx_SavLibraryPlus_Managers_ExtensionConfigurationManager::isCacheHashRequired() ? 1 : 0);
 		
 		// Adds no_cache if required
 		$additionalParameters = (Tx_SavLibraryPlus_Managers_UriManager::hasNoCacheParameter() ? array('no_cache' => 1) : array());
  
    return $this->getController()->buildLinkToPage($value, $formParameters, $cacheHash, $additionalParameters);
	}

	/**
	 * Create an internal link
	 *
	 * @param $value string (value to display)
	 *
	 * @return string (link)
	 */	
	protected function makeLink($value) {

    // Gets the funcspecial attribute
	  $special = $this->getItemConfiguration('funcspecial');
 	  		
		// Gets the folder
		$folder = ($this->getItemConfiguration('folder' . $special) ?  $this->getItemConfiguration('folder' . $special) : '.');

		// Gets the message and processes it
    $message = ($this->getItemConfiguration('message' . $special) ?  $this->getItemConfiguration('message' . $special) : $value);
    $message = $this->getController()->getQuerier()->parseLocalizationTags($message);
    $message = $this->getController()->getQuerier()->parseFieldTags($message);

    // Builds the parameter attribute
    if (empty($message) === false) {
    	if ($this->getItemConfiguration('setuid' . $special)) {
    		$parameter = $this->getItemConfiguration('setuid' . $special);
    	} elseif ($this->getItemConfiguration('valueisuid' . $special)) {
    		$parameter = $value;
    	} else {
    		$parameter = $folder . '/' . rawurlencode($value);
    	}
    } else {
    	$parameter = '';
    }
		
    // Builds the typoScript configuration
		$typoScriptConfiguration = array(
      'parameter'  => $parameter, 
      'target'  => $this->getItemConfiguration('target' . $special),
      'ATagParams' => ($this->getItemConfiguration('class' . $special) ? 'class="' . $this->getItemConfiguration('class' . $special) . '" ' : ''),
    );    

    // Gets the content object
    $contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();
   
  	return $contentObject->typolink($message, $typoScriptConfiguration);
	}	
	
	/**
	 * Creates a link and open in a new window
	 *
	 * @param string $value
	 *
	 * @return string (link)
	 */
	protected function makeNewWindowLink($value) {
	
    // Gets the funcspecial attribute
 	  $special = $this->getItemConfiguration('funcspecial');

    // Gets the message and processes it
    $message = ($this->getItemConfiguration('message' . $special) ? $this->getItemConfiguration('message' . $special) : $value);
    $message = $this->getController()->getQuerier()->parseLocalizationTags($message);
    $message = $this->getController()->getQuerier()->parseFieldTags($message);
    
    // Gets the window url
    $windowUrl = $this->getItemConfiguration('windowurl' . $special);

    // Gets the window text
    $windowText = $this->getItemConfiguration('windowtext' . $special);

    // Gets the window style
    $windowBodyStyle = ($this->getItemConfiguration('windowbodystyle' . $special) ? ' style="' . $this->getItemConfiguration('windowbodystyle' . $special) . '"' : '');

    // Builds the typoScript configuration
		$typoScriptConfiguration = array(
		  'bodyTag' => '<body' . $windowBodyStyle . '>' . ($windowText ? $windowText . '<br />' : ''),
      'enable'  => 1,
      'JSwindow'  => 1,
      'wrap' => '<a href="javascript:close();"> | </a>',
      'JSwindow.' => array(
          'newWindow'  => 1,
          'expand' => '20,' . ($windowText ? '40' : '20'),
      ),
    );

    // Gets the content object
    $contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();

    return $contentObject->imageLinkWrap($message, $windowUrl, $typoScriptConfiguration);
	}

	/**
	 * Creates an email link
	 *
	 * @param string $value
	 *
	 * @return string (link)
	 */
	protected function makeEmailLink($value) {

		// Gets the funcspecial attribute
 	  $special = $this->getItemConfiguration('funcspecial');
 	  
    // Gets the message and processes it
    $message = ($this->getItemConfiguration('message' . $special) ? $this->getItemConfiguration('message' . $special) : $value);
    $message = $this->getController()->getQuerier()->parseLocalizationTags($message);
    $message = $this->getController()->getQuerier()->parseFieldTags($message);
    
		$typoScriptConfiguration = array(
      'parameter' => ($this->getItemConfiguration('link') ? $this->getItemConfiguration('link') : $value),
    );    
    
    // Gets the content object
    $contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();
    
  	return $contentObject->typolink($message, $typoScriptConfiguration);
	}	

	/**
	 * Creates a link for an external url
	 *
	 * @param string $value (value to display)
	 *
	 * @return string (link)
	 */	
	protected function makeUrlLink($value) {
		
		// Gets the funcspecial attribute
 	  $special = $this->getItemConfiguration('funcspecial');
	  $special = $params['funcspecial'];
	  
    // Gets the message and processes it
    $message = ($this->getItemConfiguration('message' . $special) ? $this->getItemConfiguration('message' . $special) : $value);
    $message = $this->getController()->getQuerier()->parseLocalizationTags($message);
    $message = $this->getController()->getQuerier()->parseFieldTags($message);	  
	  
		$typoScriptConfiguration = array(
      'parameter' => ($this->getItemConfiguration('link') ? $this->getItemConfiguration('link') : $value),
      'extTarget'  => ($this->getItemConfiguration('exttarget') ? $this->getItemConfiguration('exttarget') : '_blank'),   
    );   
    
    // Gets the content object
    $contentObject = $this->getController()->getExtensionConfigurationManager()->getExtensionContentObject();
    
  	return $contentObject->typolink($message, $typoScriptConfiguration);
	}	
	
	/**
	 * Formats a timestamp date according to the configuration
	 *
	 * @param integer $timeStamp
	 *
	 * @return  string
	 */
	protected function makeDateFormat($timeStamp) {
	
    // Gets the funcspecial attribute
    $special = $this->getItemConfiguration('funcspecial');
    
    // Gets the format
	  $format = $this->getItemConfiguration('format' . $special);
	  if (empty($format) === true) {
      $format = ($this->getItemConfiguration('eval' . $special) == 'datetime' ? $this->getDefaultDateTimeFormat() : $this->getDefaultDateFormat());
    }

		return strftime($format, $timeStamp);
	}
  
  /**
   * Gets the default date format:
   * - From the extension TypoScript configuration if any,
   * - From the library TypoScript configuration if any,
   * - From the locale
   *
   * @param none
   *
   * @return string
   */
  public function getDefaultDateFormat() {
  	// Gets the default formats
  	$localeDefaultDateFormat = '%x';
  	$extensionDefaultDateFormat = $this->getController()->getExtensionConfigurationManager()->getDefaultDateFormat();
  	$libraryDefaultDateFormat = Tx_SavLibraryPlus_Managers_LibraryConfigurationManager::getDefaultDateFormat();

  	// Defines which format to return
  	if ($extensionDefaultDateFormat !== NULL) {
  		$defaultDateFormat = $extensionDefaultDateFormat;
  	} elseif ($libraryDefaultDateFormat !== NULL) {
  		$defaultDateFormat = $libraryDefaultDateFormat;  		
  	} else {
  		$defaultDateFormat = $localeDefaultDateFormat;
  	}
  	return $defaultDateFormat;
  } 

   /**
   * Gets the dateTime format from the library TypoScript configuration if any.
   *
   * @param none
   *
   * @return string
   */
  public function getDefaultDateTimeFormat() {
  	// Gets the default formats
  	$localeDefaultDateTimeFormat = '%c';
  	$extensionDefaultDateTimeFormat = $this->getController()->getExtensionConfigurationManager()->getDefaultDateTimeFormat();
  	$libraryDefaultDateTimeFormat = Tx_SavLibraryPlus_Managers_LibraryConfigurationManager::getDefaultDateTimeFormat();

  	// Defines which format to return
  	if ($extensionDefaultDateTimeFormat !== NULL) {
  		$defaultDateTimeFormat = $extensionDefaultDateTimeFormat;
  	} elseif ($libraryDefaultDateTimeFormat !== NULL) {
  		$defaultDateTimeFormat = $libraryDefaultDateTimeFormat;  		
  	} else {
  		$defaultDateTimeFormat = $localeDefaultDateTimeFormat;
  	}
  	return $defaultDateTimeFormat;
  } 	
	
}
?>
