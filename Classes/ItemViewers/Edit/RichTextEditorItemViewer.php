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
 * Edit Textarea item Viewer.
 * 
 *
 * @package SavLibraryPlus
 * @version $ID:$
 */
class Tx_SavLibraryPlus_ItemViewers_Edit_RichTextEditorItemViewer extends Tx_SavLibraryPlus_ItemViewers_Edit_AbstractItemViewer {

	public $RTEwindows = array();
	public $formName = '';
	public $docLarge = 0;
	public $RTEcounter = 1;
	public $additionalJS_initial = '';		// Initial JavaScript to be printed before the form (should be in head, but cannot due to IE6 timing bug)
	public $additionalJS_pre = array();	  // Additional JavaScript to be printed before the form
	public $additionalJS_post = array();	  // Additional JavaScript to be printed after the form
	public $additionalJS_submit = array();	// Additional JavaScript to be executed on submit


  /**
   * Renders the item.
   *
   * @param none
   *
   * @return string
   */
  protected function renderItem() {
  
    $htmlArray = array();

    $richTextEditor = t3lib_div::makeInstance('tx_rtehtmlarea_pi2');
    
    // Sets the page typoScript configuration
		$pageTypoScriptConfiguration = t3lib_BEfunc::getPagesTSconfig($GLOBALS['TSFE']->id);
		$typoScriptConfiguration = array_merge(
      $pageTypoScriptConfiguration['RTE.']['default.']['FE.'],
      array(
        'rteResize' => 1,
        'showStatusBar' => 0,
      )
    );

    // Sets the configuration
		$configuration = array(
      'richtext' => 1,
      'rte_transform' => array(
        'parameters' => array('flag=rte_enabled', 'mode=ts_css'),
      ),
    );

    // Sets the properties
		$properties = array(
      'itemFormElName' => $this->getItemConfiguration('itemName'),
      'itemFormElValue' => html_entity_decode($this->getItemConfiguration('value'), ENT_QUOTES, $GLOBALS['TSFE']->renderCharset),
    );

    // Gets the ritch text editor
		$content = $richTextEditor->drawRTE(
      $this,
      $this->getItemConfiguration('tableName'),
      $this->getItemConfiguration('fieldName'),
      $row = array(),
      $properties,
      $configuration,
      $typoScriptConfiguration,
      'text',
      '',
      $GLOBALS['TSFE']->id
    );

		// Removes the hidden field
		$content = preg_replace('/<input type="hidden"[^>]*>/', '', $content);

    // Adds onchange
		$content = preg_replace('/<textarea ([^>]*)>/',
      '<textarea $1' . ' onchange="document.changed=1;">'	,
      $content
    );
    $htmlArray[] = $content;

    // Adds the javaScript after the textarea tag
    $htmlArray[] = '<script type="text/javascript">';
    $htmlArray[] = $this->additionalJS_post[0];
		$htmlArray[] = '</script>';

    // Adds the prepend javaScript to additional header
    if ($this->getController()->getViewer()->isRichTextEditorInitialized() === false) {
      // Adds the initial javascript
      $GLOBALS['TSFE']->additionalHeaderData['RichTextEditor'] = $this->additionalJS_initial;

      // Adds the additional javaScript
      $javaScript = array();
      $javaScript[] = '<script type="text/javascript">';
      $javaScript[] = $this->additionalJS_pre['rtehtmlarea-loadJScode'];
		  $javaScript[] = '</script>';
      $GLOBALS['TSFE']->additionalHeaderData['RichTextEditor'] .= implode('', $javaScript);
      
      //Initializes the rich text editor
      $this->getController()->getViewer()->initializeRichTextEditor();
    }

    // Adds the javaScript for the rich text editor update
    $editorNumber = preg_replace('/[^a-zA-Z0-9_:.-]/', '_', $properties['itemFormElName']) . '_' . $this->RTEcounter;
    Tx_SavLibraryPlus_Managers_AdditionalHeaderManager::addJavaScript('checkIfRteChanged', 'checkIfRteChanged(\'' . $editorNumber . '\');');
    Tx_SavLibraryPlus_Managers_AdditionalHeaderManager::addJavaScript('rteUpdate', $this->additionalJS_submit[0]);

    return $this->arrayToHTML($htmlArray);
  }

}

?>
