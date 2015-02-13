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


class CRM_Utils_HelpTab {
  CONST HELPTAB_PREFERENCES_NAME = 'HelpTab Preferences';

  private static $_singleton = NULL;

  private $_settings;
  private static $_resource_loaded = false;

  static function singleton() {
    if (!self::$_singleton) {
      self::$_singleton = new CRM_Utils_HelpTab();
    }
    return self::$_singleton;
  }

  /**
   * Construct a HelpTab
   */
  public function __construct() {
    $this->_settings = $this->getSettings();
  }

  /**
   * Returns HelpTab settings
   */
  static function getSettings() {
    return CRM_Core_BAO_Setting::getItem(CRM_Utils_HelpTab::HELPTAB_PREFERENCES_NAME);
  }

  function setSetting($value, $name) {
    CRM_Core_BAO_Setting::setItem($value, CRM_Utils_HelpTab::HELPTAB_PREFERENCES_NAME, $name);
  }

  static function addResource() {
    if ( self::is_public_page() || self::$_resource_loaded || array_key_exists('snippet', $_GET) ) {
        return;
    }
    $config = CRM_Core_Config::singleton();
    $civicrm_contex = $_GET[$config->userFrameworkURLVar];
    $settings = CRM_Utils_HelpTab::getSettings();
    $cividesk_key = CRM_Utils_Array::value( 'cividesk_key', $settings);
    $currentVer = CRM_Core_BAO_Domain::version(true);
      $civicrm_major_version = '';
    if (preg_match('/[\d]+[\.][\d]+/', $currentVer, $matches)) {
      $civicrm_major_version = $matches[0];
    }

    CRM_Core_Resources::singleton()->addScript("
       var cividesk_key = '".$cividesk_key."';
       var civicrm_version = '".$civicrm_major_version."';
       var civicrm_contex = '".$civicrm_contex."';
    ");

    CRM_Core_Resources::singleton()->addScript(file_get_contents(dirname(dirname(dirname( __FILE__ )))."/js/helptab.js"));
    CRM_Core_Resources::singleton()->addScript(file_get_contents(dirname(dirname(dirname( __FILE__ )))."/js/jquery-mousewheel.js"));
    CRM_Core_Resources::singleton()->addScript(file_get_contents(dirname(dirname(dirname( __FILE__ )))."/js/jScrollbar.jquery.js"));
    //CRM_Core_Resources::singleton()->addScriptFile('com.cividesk.helptab', "js/jquery-mousewheel.js" );
    //CRM_Core_Resources::singleton()->addScriptFile('com.cividesk.helptab', "js/jScrollbar.jquery.js" );

    CRM_Core_Resources::singleton()->addStyleFile('com.cividesk.helptab', 'css/helptab.css');
    CRM_Core_Resources::singleton()->addStyleFile('com.cividesk.helptab', 'css/jScrollbar.jquery.css');
    self::$_resource_loaded = true;

  }

  function is_public_page() {
    // Get the menu items.
    $args = explode('?', $_GET['q']);
    $path = $args[0];
    // Get the menu for above URL.
    $item = CRM_Core_Menu::get($path);

    // Check for public pages
    // If public page and civicrm public theme is set, apply civicrm public theme
    if (CRM_Utils_Array::value('is_public', $item)) {
      return true;
    }
    return false;
  }
}
