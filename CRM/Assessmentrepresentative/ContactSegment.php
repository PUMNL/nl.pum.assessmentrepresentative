<?php

/**
 * Class to process contact segment in the context of an assessment representative
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @date 12 November 2015
 * @license AGPL-3.0
 */
class CRM_Assessmentrepresentative_ContactSegment {
  /**
   * Method to process post hook for ContactSegment
   * - if $op = create and role is Customer and segment type is sector (parent) then:
   *   - check if contactsegment with role customer is the only sector for the customer and if so
   *     - retrieve all active cases for customer (this should only be a first projectintake)
   *     - check if they have a sector coordinator and if not set one based on the sector
   *
   * @param $op
   * @param $objectName
   * @param $objectId
   * @param $objectRef
   */
  public static function post($op, $objectName, $objectId, &$objectRef) {
    if ($op == 'create') {
      if (!isset($objectRef->role_value) || !isset($objectRef->segment_id) || !isset($objectRef->contact_id)) {
        return;
      }
      if ($objectRef->role_value == 'Customer') {
        $parentSegmentId = civicrm_api3('Segment', 'Getvalue', array('id' => $objectRef->segment_id, 'return' => 'parent_id'));
        if (empty($parentSegmentId)) {
          self::processCases($objectRef->contact_id);
        }
      }
    }
  }

  /**
   * Method to set the sector coordinator for the cases that can be processed
   *
   * @param $contactId
   */
  private static function processCases($contactId) {
    try {
      $foundCases = civicrm_api3('Case', 'Get', array('contact_id' => $contactId));
      $sectorCoordinatorId = CRM_Threepeas_BAO_PumCaseRelation::getSectorCoordinatorId($contactId);
      foreach ($foundCases['values'] as $case) {
        if (self::canProcessCase($case)) {
          CRM_Threepeas_BAO_PumCaseRelation::createCaseRelation($case['id'], $contactId, $sectorCoordinatorId,
            $case['start_date'], "sector_coordinator");
        }
      }
    } catch (CiviCRM_API3_Exception $ex) {}
  }

  /**
   * Method to determine if case can be processed
   *
   * @param $case
   * @return bool
   */
  private static function canProcessCase($case) {
    if ($case['is_deleted'] == 1) {
      return FALSE;
    }
    $statusToBeIgnored = array('Cancelled','Completed', 'Closed', 'Declined', 'Error', 'Rejected', 'Resolved');
    try {
      $optionGroupId = civicrm_api3('OptionGroup', 'Getvalue', array('name' => 'case_status', 'return' => 'id'));
      try {
        $caseStatusName = civicrm_api3('OptionValue', 'Getvalue',
          array('option_group_id' => $optionGroupId, 'value' => $case['status_id'], 'return' => 'name'));
        if (in_array($caseStatusName, $statusToBeIgnored)) {
          return FALSE;
        } else {
          return TRUE;
        }
      } catch (CiviCRM_API3_Exception $ex) {
        return FALSE;
      }
    } catch (CiviCRM_API3_Exception $ex) {
      return FALSE;
    }
  }
}