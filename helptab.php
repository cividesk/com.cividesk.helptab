<?php
/*
 * Cividesk helptab module
 *
 */

require_once 'helptab.civix.php';

/**
 * Implementation of hook_civicrm_config
 */
function helptab_civicrm_config(&$config) {
  _helptab_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function helptab_civicrm_xmlMenu(&$files) {
  _helptab_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_navigationMenu
 *
 * @param $files array(string)
 */
/*
function helptab_civicrm_navigationMenu( &$params ) {
  // Add menu entry for extension administration page
  _helptab_civix_insert_navigation_menu($params, 'Administer/Customize Data and Screens', array(
    'name'       => 'HelpTab Settings',
    'url'        => 'civicrm/admin/setting/helptab',
    'permission' => 'administer CiviCRM',
  ));
}
*/
/**
 * Implementation of hook_civicrm_install
 */
function helptab_civicrm_install() {
  return _helptab_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function helptab_civicrm_uninstall() {
  return _helptab_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function helptab_civicrm_enable() {
  return _helptab_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function helptab_civicrm_disable() {
  return _helptab_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function helptab_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _helptab_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function helptab_civicrm_managed(&$entities) {
  return _helptab_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_pageRun
 */
function helptab_civicrm_pageRun(&$page) {
  // Extract the page Menu
  // Check if is_public, return
  // Use the Region API to insert:
  // var secret=kkkk
  // var version=4.4
  // Display the helptab: include the files in the head and body
    CRM_Utils_HelpTab::addResource();
}

/**
 * Implementation of hook_civicrm_pageRun
 */
function helptab_civicrm_buildForm(&$form) {
  // Extract the page Menu
  // Check if is_public, return
  // Display the helptab
    CRM_Utils_HelpTab::addResource();
}
