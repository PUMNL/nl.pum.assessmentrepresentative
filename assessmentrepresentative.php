<?php

require_once 'assessmentrepresentative.civix.php';

/**
 * Implements hook civicrm_post()
 * Process contact segment
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_post
 */
function assessmentrepresentative_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if ($objectName == 'ContactSegment') {
    CRM_Assessmentrepresentative_ContactSegment::post($op, $objectName, $objectId, $objectRef);
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function assessmentrepresentative_civicrm_config(&$config) {
  _assessmentrepresentative_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function assessmentrepresentative_civicrm_xmlMenu(&$files) {
  _assessmentrepresentative_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function assessmentrepresentative_civicrm_install() {
  if (_assessmentrepresentative_checkContactSegmentInstalled() == FALSE) {
    throw new Exception(ts('Could not install extension nl.pum.assessmentrepresentative,
      required extension org.civicoop.contactsegment not installed or disabled'));
  }
  _assessmentrepresentative_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function assessmentrepresentative_civicrm_uninstall() {
  _assessmentrepresentative_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function assessmentrepresentative_civicrm_enable() {
  if (_assessmentrepresentative_checkContactSegmentInstalled() == FALSE) {
    throw new Exception(ts('Could not enable extension nl.pum.assessmentrepresentative,
      required extension org.civicoop.contactsegment not installed or disabled'));
  }
  _assessmentrepresentative_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function assessmentrepresentative_civicrm_disable() {
  _assessmentrepresentative_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function assessmentrepresentative_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _assessmentrepresentative_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function assessmentrepresentative_civicrm_managed(&$entities) {
  _assessmentrepresentative_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * @param array $caseTypes
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function assessmentrepresentative_civicrm_caseTypes(&$caseTypes) {
  _assessmentrepresentative_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function assessmentrepresentative_civicrm_angularModules(&$angularModules) {
_assessmentrepresentative_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function assessmentrepresentative_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _assessmentrepresentative_civix_civicrm_alterSettingsFolders($metaDataFolders);
}
/**
 * Function to check if extension org.civicoop.contactsegment is installed
 */
function _assessmentrepresentative_checkContactSegmentInstalled() {
  $foundExtension = FALSE;
  try {
    $installedExtensions = civicrm_api3('Extension', 'Get', array());
    foreach ($installedExtensions['values'] as $extension) {
      if ($extension['key'] = 'org.civicoop.contactsegment' && $extension['status'] == 'installed') {
        $foundExtension = TRUE;
      }
    }
  } catch (CiviCRM_API3_Exception $ex) {
    throw new Exception(ts('Could not get any extensions in mainsector.php function _checkContactSegmentInstalled,
      error from API Extension Get: '.$ex->getMessage()));
  }
  return $foundExtension;
}