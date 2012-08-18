<?php/****************************************************************  Copyright notice**  (c) 2011 Laurent Foulloy <yolf.typo3@orange.fr>*  All rights reserved**  This script is part of the TYPO3 project. The TYPO3 project is*  free software; you can redistribute it and/or modify*  it under the terms of the GNU General Public License as published by*  the Free Software Foundation; either version 2 of the License, or*  (at your option) any later version.**  The GNU General Public License can be found at*  http://www.gnu.org/copyleft/gpl.html.**  This script is distributed in the hope that it will be useful,*  but WITHOUT ANY WARRANTY; without even the implied warranty of*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the*  GNU General Public License for more details.**  This copyright notice MUST APPEAR in all copies of the script!***************************************************************//** * Controller * * @package SavLibraryPlus * @version $ID:$ */class Tx_SavLibraryPlus_Controller_Controller extends Tx_SavLibraryPlus_Controller_AbstractController {   	/**	 * Common code for change page in subform actions	 *	 * @param none	 *	 * @none	 */  protected function changePageInSubform() {    $subformFieldKey = Tx_SavLibraryPlus_Managers_UriManager::getSubformFieldKey();    Tx_SavLibraryPlus_Managers_SessionManager::setSubformFieldFromSession($subformFieldKey, 'pageInSubform', Tx_SavLibraryPlus_Managers_UriManager::getPageInSubform());  } 	/**	 * Renders change page in subform action	 *	 * @param none	 *	 * @return string	 */  protected function changePageInSubformAction() {    $this->changePageInSubform();    return $this->renderForm('single');  } 	/**	 * Renders change page in subform action	 *	 * @param none	 *	 * @return string	 */  protected function changePageInSubformInEditModeAction() {    $this->changePageInSubform();    return $this->renderForm('edit');  } 	/**	 * Renders the Close action	 *	 * @param none	 *	 * @return string	 */  protected function closeAction() {    Tx_SavLibraryPlus_Managers_SessionManager::clearSubformFromSession();    return $this->renderForm('list');  }   	/**	 * Renders the Close in edit mode action	 *	 * @param none	 *	 * @return string	 */  protected function closeInEditModeAction() {    Tx_SavLibraryPlus_Managers_SessionManager::clearSubformFromSession();      return $this->renderForm('listInEditMode');  } 	/**	 * Renders the Delete action	 *	 * @param none	 *	 * @return string	 */  protected function deleteAction() {    $querierClassName = 'Tx_SavLibraryPlus_Queriers_DeleteQuerier';    $this->querier = t3lib_div::makeInstance($querierClassName);    $this->querier->injectController($this);    $this->querier->injectQueryConfiguration();    $this->querier->processQuery();    return $this->renderForm('listInEditMode');  } 	/**	 * Renders the Delete action	 *	 * @param none	 *	 * @return string	 */  protected function deleteInSubformAction() {    $querierClassName = 'Tx_SavLibraryPlus_Queriers_DeleteInSubformQuerier';    $this->querier = t3lib_div::makeInstance($querierClassName);    $this->querier->injectController($this);    $this->querier->injectQueryConfiguration();    $this->querier->processQuery();    // Renders the form in edit mode    return $this->renderForm('edit');  }     	/**	 * Renders the down action	 *	 * @param none	 *	 * @return string	 */  protected function downInSubformAction() {    $querierClassName = 'Tx_SavLibraryPlus_Queriers_DownInSubformQuerier';    $this->querier = t3lib_div::makeInstance($querierClassName);    $this->querier->injectController($this);    $this->querier->injectQueryConfiguration();    $this->querier->processQuery();    // Renders the form in edit mode    return $this->renderForm('edit');  }   	/**	 * Renders the Edit action	 *	 * @param none	 *	 * @return string	 */  protected function editAction() {    Tx_SavLibraryPlus_Managers_SessionManager::clearSubformFromSession();    return $this->renderForm('edit');  } 	/**	 * Renders the Export action	 *	 * @param none	 *	 * @return string	 */  protected function exportAction() {    return $this->renderForm('export');  }   	/**	 * Renders the Export Submit action	 *	 * @param none	 *	 * @return string	 */  protected function exportSubmitAction() {  	// Sets the post variables  	    $uriManager = $this->getUriManager();    $uriManager->setPostVariables();  			// Gets the form action		$formAction = $uriManager->getFormActionFromPostVariables();		if (isset($formAction['exportLoadConfiguration'])) {    	return $this->renderForm('exportLoadConfiguration');									} elseif (isset($formAction['exportSaveConfiguration'])) {			return $this->renderForm('exportSaveConfiguration');		} elseif (isset($formAction['exportDeleteConfiguration'])) {			return $this->renderForm('exportDeleteConfiguration');		} elseif (isset($formAction['exportToggleDisplay'])) {			return $this->renderForm('exportToggleDisplay');		} elseif (isset($formAction['exportExecute'])) {			return $this->renderForm('exportExecute');		} else {    	return $this->renderForm('export');		}  }     	/**	 * Common code for the first page actions	 *	 * @param none	 *	 * @none	 */  protected function firstPage() {    $compressedParameters = Tx_SavLibraryPlus_Managers_UriManager::getCompressedParameters();    $compressedParameters = self::changeCompressedParameters($compressedParameters, 'page', 0 );    Tx_SavLibraryPlus_Managers_UriManager::setCompressedParameters($compressedParameters);  } 	/**	 * Renders the first page action	 *	 * @param none	 *	 * @return string	 */  protected function firstPageAction() {    $this->firstPage();    return $this->renderForm('list');  } 	/**	 * Renders the first page in edit mode action	 *	 * @param none	 *	 * @return string	 */  protected function firstPageInEditModeAction() {    $this->firstPage();    return $this->renderForm('listInEditMode');  }   	/**	 * Common code for the first page in subform actions	 *	 * @param none	 *	 * @none	 */  protected function firstPageInSubform() {    $subformFieldKey = Tx_SavLibraryPlus_Managers_UriManager::getSubformFieldKey();    Tx_SavLibraryPlus_Managers_SessionManager::setSubformFieldFromSession($subformFieldKey, 'pageInSubform', 0);  } 	/**	 * Renders the first page in subform action	 *	 * @param none	 *	 * @return string	 */  protected function firstPageInSubformAction() {    $this->firstPageInSubform();    return $this->renderForm('single');  } 	/**	 * Renders the first page in subform action	 *	 * @param none	 *	 * @return string	 */  protected function firstPageInSubformInEditModeAction() {    $this->firstPageInSubform();    return $this->renderForm('edit');  } 	/**	 * Renders the form action	 *	 * @param none	 *	 * @return string	 */  protected function formAction() {    return $this->renderForm('form');  } 	/**	 * Renders the form admin action	 *	 * @param none	 *	 * @return string	 */  protected function formAdminAction() {    return $this->renderForm('formAdmin');  }     	/**	 * Common code for the last page actions	 *	 * @param none	 *	 * @none	 */  protected function lastPage() {    // Creates a querier to get the total rows count    $querier = t3lib_div::makeInstance('Tx_SavLibraryPlus_Queriers_ListSelectQuerier');    $querier->injectController($this);    $querier->injectQueryConfiguration();    $querier->processTotalRowsCountQuery();    $lastPage = floor(($querier->getTotalRowsCount() - 1) / $this->getExtensionConfigurationManager()->getMaxItems());    $compressedParameters = Tx_SavLibraryPlus_Managers_UriManager::getCompressedParameters();    $compressedParameters = self::changeCompressedParameters($compressedParameters, 'page', $lastPage );    Tx_SavLibraryPlus_Managers_UriManager::setCompressedParameters($compressedParameters);  }   	/**	 * Renders the last page action	 *	 * @param none	 *	 * @return string	 */  protected function lastPageAction() {    $this->lastPage();    return $this->renderForm('list');  } 	/**	 * Renders the last page in edit mode action	 *	 * @param none	 *	 * @return string	 */  protected function lastPageInEditModeAction() {    $this->lastPage();    return $this->renderForm('listInEditMode');  } 	/**	 * Common code for the last page in subform actions	 *	 * @param none	 *	 * @none	 */  protected function lastPageInSubform($view) {    // Gets the subform field key    $subformFieldKey = Tx_SavLibraryPlus_Managers_UriManager::getSubformFieldKey();    // Gets the view identifier    $viewIdentifier =  $this->getLibraryConfigurationManager()->getViewIdentifier($view);      // Gets the view configuration    $libraryViewConfiguration =  $this->getLibraryConfigurationManager()->getViewConfiguration($viewIdentifier);        // Gets the kickstarter configuration for the subform field key    $kickstarterFieldConfiguration = $this->getLibraryConfigurationManager()->searchFieldConfiguration($libraryViewConfiguration, $subformFieldKey);    // Gets the field configuration    $fieldConfigurationManager = t3lib_div::makeInstance('Tx_SavLibraryPlus_Managers_FieldConfigurationManager');    $fieldConfigurationManager->injectController($this);    $fieldConfigurationManager->injectKickstarterFieldConfiguration($kickstarterFieldConfiguration);    $fieldConfiguration = $fieldConfigurationManager->getFieldConfiguration();    // Adds the uidLocal and the page in the subform    $fieldConfiguration['uidLocal'] = Tx_SavLibraryPlus_Managers_UriManager::getSubformUidLocal();    // Builds the querier for the total rows count    $querierClassName = 'Tx_SavLibraryPlus_Queriers_ForeignTableSelectQuerier';    $querier = t3lib_div::makeInstance($querierClassName);    $querier->injectController($this);    $querier-> buildQueryConfigurationForTrueManyToManyRelation($fieldConfiguration);    $querier->injectQueryConfiguration();    $querier->processTotalRowsCountQuery();    // Changes the page in subform    $lastPage = floor(($querier->getTotalRowsCount() - 1) / $fieldConfiguration['maxsubformitems']);    Tx_SavLibraryPlus_Managers_SessionManager::setSubformFieldFromSession($subformFieldKey, 'pageInSubform', $lastPage);  } 	/**	 * Renders the last page in subform action	 *	 * @param none	 *	 * @return string	 */  protected function lastPageInSubformAction() {    $this->lastPageInSubform('singleView');    return $this->renderForm('single');  } 	/**	 * Renders the last page in subform in edit mode action	 *	 * @param none	 *	 * @return string	 */  protected function lastPageInSubformInEditModeAction() {    $this->lastPageInSubform('editView');    return $this->renderForm('edit');  }   	/**	 * Renders the List action	 *	 * @param none	 *	 * @return string	 */  protected function listAction() {    return $this->renderForm('list');  } 	/**	 * Renders the List action in edit mode	 *	 * @param none	 *	 * @return string	 */  protected function listInEditModeAction() {    return $this->renderForm('listInEditMode');  } 	/**	 * Common code for the next page actions	 *	 * @param none	 *	 * @return none	 */  protected function nextPage() {    $compressedParameters = Tx_SavLibraryPlus_Managers_UriManager::getCompressedParameters();    $compressedParameters = self::changeCompressedParameters($compressedParameters, 'page', Tx_SavLibraryPlus_Managers_UriManager::getPage() + 1 );    Tx_SavLibraryPlus_Managers_UriManager::setCompressedParameters($compressedParameters);  } 	/**	 * Renders the next page action	 *	 * @param none	 *	 * @return string	 */  protected function nextPageAction() {    $this->nextPage();    return $this->renderForm('list');  } 	/**	 * Renders the next page action in edit mode	 *	 * @param none	 *	 * @return string	 */  protected function nextPageInEditModeAction() {    $this->nextPage();    return $this->renderForm('listInEditMode');  }   	/**	 * Common code for the next page in subform actions	 *	 * @param none	 *	 * @return none	 */  protected function nextPageInSubform() {    $subformFieldKey = Tx_SavLibraryPlus_Managers_UriManager::getSubformFieldKey();    $pageInSubform = Tx_SavLibraryPlus_Managers_SessionManager::getSubformFieldFromSession($subformFieldKey, 'pageInSubform');    Tx_SavLibraryPlus_Managers_SessionManager::setSubformFieldFromSession($subformFieldKey, 'pageInSubform', $pageInSubform + 1);  } 	/**	 * Renders the next page in subform action	 *	 * @param none	 *	 * @return string	 */  protected function nextPageinSubformAction() {    $this->nextPageInSubform();    return $this->renderForm('single');  } 	/**	 * Renders the next page in subform in edit mode action	 *	 * @param none	 *	 * @return string	 */  protected function nextPageinSubformInEditModeAction() {    $this->nextPageInSubform();    return $this->renderForm('edit');  }   	/**	 * Renders the new action	 *	 * @param none	 *	 * @return string	 */  protected function newAction() {    return $this->renderForm('new');  } 	/**	 * Renders the new action	 *	 * @param none	 *	 * @return string	 */  protected function newInSubformAction() {    return $this->renderForm('newInSubform');  } 	/**	 * Renders the noDisplay action	 *	 * @param none	 *	 * @return string	 */  protected function noDisplayAction() {    return '';  }   	/**	 * Common code for the previous page actions	 *	 * @param none	 *	 * @return none	 */  protected function previousPage() {    $compressedParameters = Tx_SavLibraryPlus_Managers_UriManager::getCompressedParameters();    $compressedParameters = self::changeCompressedParameters($compressedParameters, 'page', Tx_SavLibraryPlus_Managers_UriManager::getPage() - 1 );    Tx_SavLibraryPlus_Managers_UriManager::setCompressedParameters($compressedParameters);  }   	/**	 * Renders the previous page action	 *	 * @param none	 *	 * @return string	 */  protected function previousPageAction() {    $this->previousPage();    return $this->renderForm('list');  } 	/**	 * Renders the previous page action in edit mode	 *	 * @param none	 *	 * @return string	 */  protected function previousPageInEditModeAction() {    $this->previousPage();    return $this->renderForm('listInEditMode');  }   	/**	 * Common code for the previous page in subform actions	 *	 * @param none	 *	 * @return string	 */  protected function previousPageInSubform() {    $subformFieldKey = Tx_SavLibraryPlus_Managers_UriManager::getSubformFieldKey();    $pageInSubform =Tx_SavLibraryPlus_Managers_SessionManager::getSubformFieldFromSession($subformFieldKey, 'pageInSubform');    Tx_SavLibraryPlus_Managers_SessionManager::setSubformFieldFromSession($subformFieldKey, 'pageInSubform', $pageInSubform - 1);  } 	/**	 * Renders the previous page in subform action	 *	 * @param none	 *	 * @return string	 */  protected function previousPageInSubformAction() {    $this->previousPageInSubform();    return $this->renderForm('single');  } 	/**	 * Renders the previous page in subform in edit mode action	 *	 * @param none	 *	 * @return string	 */  protected function previousPageInSubformInEditModeAction() {    $this->previousPageInSubform();    return $this->renderForm('edit');  }   	/**	 * Renders the save action	 *	 * @param none	 *	 * @return string	 */  protected function saveAction() {  	// Sets the post variables  	    $uriManager = $this->getUriManager();    $uriManager->setPostVariables();      $querierClassName = 'Tx_SavLibraryPlus_Queriers_UpdateQuerier';    $this->querier = t3lib_div::makeInstance($querierClassName);    $this->querier->injectController($this);    $this->querier->injectQueryConfiguration();    // Processes the query and renders the edit form in case of errors    if ($this->querier->processQuery() === false) {			return $this->renderForm('edit');    }        // Gets the form action    $formAction = $uriManager->getFormActionFromPostVariables();    if (isset($formAction['saveAndShow'])) {      return $this->renderForm('single');    } elseif (isset($formAction['saveAndClose'])) {      return $this->renderForm('listInEditMode');    } elseif (isset($formAction['saveAndNew'])) {      return $this->renderForm('new');          } elseif (isset($formAction['saveAndNewInSubform'])) {    	// Changes the form action    	$compressedParameters = Tx_SavLibraryPlus_Managers_UriManager::getCompressedParameters();    	$compressedParameters = self::changeCompressedParameters($compressedParameters, 'formAction', 'newInSubform');    	    	// Gets the compressed string    	$compressedString = key($formAction['saveAndNewInSubform']);    	$uncompressedParameters = self::uncompressParameters($compressedString);    	// Changes the parameters    	foreach ($uncompressedParameters as $parameterKey => $parameter) {    		$compressedParameters = self::changeCompressedParameters($compressedParameters, $parameterKey, $parameter);    	}    	Tx_SavLibraryPlus_Managers_UriManager::setCompressedParameters($compressedParameters);    	    	      return $this->renderForm('newInSubform');          } else {      return $this->renderForm('edit');    }  } 	/**	 * Renders the save form action	 *	 * @param none	 *	 * @return string	 */  protected function saveFormAction() {  	// Sets the post variables    $uriManager = $this->getUriManager();    $uriManager->setPostVariables();      $querierClassName = 'Tx_SavLibraryPlus_Queriers_FormUpdateQuerier';    $this->querier = t3lib_div::makeInstance($querierClassName);    $this->querier->injectController($this);    $this->querier->injectQueryConfiguration();    $this->querier->processQuery();   	return $this->renderForm('form');  }   	/**	 * Renders the save form action	 *	 * @param none	 *	 * @return string	 */  protected function saveFormAdminAction() {  	// Sets the post variables    $uriManager = $this->getUriManager();    $uriManager->setPostVariables();      $querierClassName = 'Tx_SavLibraryPlus_Queriers_FormAdminUpdateQuerier';    $this->querier = t3lib_div::makeInstance($querierClassName);    $this->querier->injectController($this);    $this->querier->injectQueryConfiguration();    $this->querier->processQuery();   	return $this->renderForm('formAdmin');  }     	/**	 * Renders the single action	 *	 * @param none	 *	 * @return string	 */  protected function singleAction() {    Tx_SavLibraryPlus_Managers_SessionManager::clearSubformFromSession();    return $this->renderForm('single');  } 	/**	 * Renders the up action	 *	 * @param none	 *	 * @return string	 */  protected function upInSubformAction() {    $querierClassName = 'Tx_SavLibraryPlus_Queriers_UpInSubformQuerier';    $this->querier = t3lib_div::makeInstance($querierClassName);    $this->querier->injectController($this);    $this->querier->injectQueryConfiguration();    $this->querier->processQuery();        // Renders the form in edit mode    return $this->renderForm('edit');  }  }?>