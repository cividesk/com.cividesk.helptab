<?php
/*
 +--------------------------------------------------------------------------+
 | Copyright IT Bliss LLC (c) 2012-2013                                     |
 +--------------------------------------------------------------------------+
 | This program is free software: you can redistribute it and/or modify     |
 | it under the terms of the GNU Affero General Public License as published |
 | by the Free Software Foundation, either version 3 of the License, or     |
 | (at your option) any later version.                                      |
 |                                                                          |
 | This program is distributed in the hope that it will be useful,          |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
 | GNU Affero General Public License for more details.                      |
 |                                                                          |
 | You should have received a copy of the GNU Affero General Public License |
 | along with this program.  If not, see <http://www.gnu.org/licenses/>.    |
 +--------------------------------------------------------------------------+
*/

class CRM_Admin_Form_Setting_HelpTab extends CRM_Admin_Form_Setting {
  protected $_settings;

  function preProcess() {
    // Needs to be here as from is build before default values are set
    $this->_settings = CRM_Utils_HelpTab::getSettings();
    if (!$this->_settings) $this->_settings = array();
  }

  /**
   * Function to build the form
   *
   * @return None
   * @access public
   */
  public function buildQuickForm() {
    $this->applyFilter('__ALL__', 'trim');

    $this->add('text', "cividesk_key", ts("Cividesk key"), '', true);
  
    $this->addFormRule(array('CRM_Admin_Form_Setting_HelpTab', 'formRule'));
    
    
    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Save'),
        'isDefault' => TRUE,
      ),
      array(
        'type' => 'cancel',
        'name' => ts('Cancel'),
      ),
    ));
  }

  function setDefaultValues() {
    $defaults = $this->_settings;
    return $defaults;
  }

  static function formRule($fields) {
    $errors = array();
    return empty($errors) ? TRUE : $errors;
  }

  /**
   * Function to process the form
   *
   * @access public
   * @return None
   */
  public function postProcess(){
    // store the submitted values in an array
    $params = $this->exportValues();
    // Save all settings
    foreach ($this->_elementIndex as $key => $dontcare) {
      $prefix = reset(explode('_', $key));
      if (in_array($prefix, array('cividesk') ) ) {
        CRM_Utils_HelpTab::setSetting(CRM_Utils_Array::value($key, $params, 0), $key);
      }
    }
  } //end of function

} // end class
