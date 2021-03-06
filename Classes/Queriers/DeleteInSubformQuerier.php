<?php
namespace SAV\SavLibraryPlus\Queriers;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * DeleteInSubform Querier.
 * 
 * @package SavLibraryPlus
 * @version $ID:$
 */
 
class DeleteInSubformQuerier extends AbstractQuerier {

	/**
   * Executes the query
   *
   * @param none
   *
   * @return none
   */
  protected function executeQuery() {
  
    // Checks if the user is authenticated
    if($this->getController()->getUserManager()->userIsAuthenticated() === FALSE) {
      \SAV\SavLibraryPlus\Controller\FlashMessages::addError('fatal.notAuthenticated');
      return;
    }

    // Gets the subform field key
    $subformFieldKey = \SAV\SavLibraryPlus\Managers\UriManager::getSubformFieldKey();
    
    // Gets the kickstarter configuration for the subform field key
    $viewIdentifier = $this->getController()->getLibraryConfigurationManager()->getViewIdentifier('EditView');    
    $viewConfiguration = $this->getController()->getLibraryConfigurationManager()->getViewConfiguration($viewIdentifier);
    $kickstarterFieldConfiguration = $this->getController()->getLibraryConfigurationManager()->searchFieldConfiguration($viewConfiguration, $subformFieldKey);

    // Creates the field configuration manager
    $fieldConfigurationManager = GeneralUtility::makeInstance('SAV\\SavLibraryPlus\\Managers\\FieldConfigurationManager');
    $fieldConfigurationManager->injectController($this->getController());
    $fieldConfigurationManager->injectKickstarterFieldConfiguration($kickstarterFieldConfiguration);
    $fieldConfiguration = $fieldConfigurationManager->getFieldConfiguration();

    // Gets the subform foreign uid
    $subformUidForeign = \SAV\SavLibraryPlus\Managers\UriManager::getSubformUidForeign();

    // Updates the deleted flag in the foreign table
    $this->setDeletedField($fieldConfiguration['foreign_table'], $subformUidForeign);

    if (empty($fieldConfiguration['norelation'])) {

	    // Gets the subform local uid
	    $subformUidLocal = \SAV\SavLibraryPlus\Managers\UriManager::getSubformUidLocal();

	    // Deletes the record in the relation
	    $this->deleteRecordsInRelationManyToMany($fieldConfiguration['MM'], $subformUidForeign, 'uid_foreign');
    
   	 	// Reorders the sorting field
    	$this->reorderSortingInRelationManyToMany($fieldConfiguration['MM'], $subformUidLocal);
    	
    	// Gets the rows count
    	$rowsCount = $this->getRowsCountInRelationManyToMany($fieldConfiguration['MM'], $subformUidLocal);
      
      // Updates the count in the table
      $this->updateFields($fieldConfiguration['tableName'], array($fieldConfiguration['fieldName'] => $rowsCount), $subformUidLocal);	
    } else {
   		$rowsCount = $this->getRowsCountInTable($fieldConfiguration['foreign_table']);
    }
        
    // Updates the page in subform value if needed
    $pageInSubform = \SAV\SavLibraryPlus\Managers\SessionManager::getSubformFieldFromSession($subformFieldKey, 'pageInSubform');
    $pageInSubform = ($pageInSubform ? $pageInSubform : 0);
      
		if ($pageInSubform >0 && $rowsCount <= $pageInSubform*$fieldConfiguration['maxsubformitems']) {
	    $pageInSubform = \SAV\SavLibraryPlus\Managers\SessionManager::getSubformFieldFromSession($subformFieldKey, 'pageInSubform');
	    \SAV\SavLibraryPlus\Managers\SessionManager::setSubformFieldFromSession($subformFieldKey, 'pageInSubform', $pageInSubform - 1);		
		}
	}

}
?>
