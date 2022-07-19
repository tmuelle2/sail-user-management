<?php

namespace Sail\Data\Dao;

use Sail\Caching\InMemoryCache;
use Sail\Data\Model\Family;
use Sail\Data\Model\User;
use Sail\Data\Dao\UserDao;
use Sail\Utils\Singleton;
use Sail\Utils\Logger;

class FamilyDao
{
  use InMemoryCache;
  use Singleton;
  use Logger;

  public function createFamilyRelation(Family $relation): Family
  {
    global $wpdb;

    // Insert into family db table
    $this->log("Attempting to create sail_family row with this data: ");
    $this->log(print_r($relation->getDatabaseData(), true));
    $wpdb->insert('sail_family', $relation->getDatabaseData(), Family::fieldKeys());
    $this->log("Sail_family created");
    return $this->cache($relation->familyId, $relation);
  }

  public function updateFamilyRelation(Family $relation, array $updates): Family
  {
    return $this->updateFamilyRelationWithUpdatesAlreadySet($relation->merge($updates));
  }

  public function updateFamilyRelationWithUpdatesAlreadySet(Family $relation): Family
  {
    global $wpdb;
    $wpdb->show_errors = true;
    $wpdb->suppress_errors = false;
    $wpdb->show_errors();
    $num_updated_rows = $wpdb->update('sail_family', $relation->getDatabaseData(), array('relationId' => $relation->getDatabaseData()["relationId"]), Family::fieldKeys());

    if ($num_updated_rows === false) {
      $this->log("ERROR IN UPDATING FAMILY: ");
      $this->log(print_r($wpdb->error, true));
      $this->log(print_r($wpdb->last_query, true));
      $this->log(print_r(Family::fieldKeys(), true));
      $this->log("ERROR Done. ");
    }
    else {
      $this->log("Updated this many rows in sail_family: ");
      $this->log(print_r($num_updated_rows, true));
    }
    //$this->log(print_r($user, true));
    return $relation;
  }

  public function deleteFamilyRelation(string $relationId): void
  {
    global $wpdb;
    $this->log("Attempting to delete sail_family row with this relationId: ");
    $this->log(print_r($relationId, true));
    $wpdb->delete('sail_family', ['relationId' => $relationId]);
  }

  public function getFamilyRelationById(string $relationId): Family
  {
    global $wpdb;
    $family_members = [];

    $query = "SELECT * FROM `sail_family` WHERE relationId = ";
    $query .= $relationId;

    $results = $wpdb->get_results($query);

    if (count($results) > 0)
      return new Family($family_members, (array)$results[0]);
    else
      return new Family($family_members, []);
  }

  public function doesFamilyRelationExist(string $userId1, string $userId2): int
  {
    global $wpdb;

    $query = "SELECT * FROM `sail_family` WHERE (userId1 = ";
    $query .= $userId1;
    $query .= " AND userId2 = ";
    $query .= $userId2;
    $query .= ") OR (userId1 = ";
    $query .= $userId2;
    $query .= " AND userId2 = ";
    $query .= $userId1;
    $query .= ")";

    $results = $wpdb->get_results($query);

    return count($results);
  }

  public function getFamilyMembersForUser(User $user): Family
  {
    if ($this->isCached($user->userId)) {
      return $this->getCachedValue($user->userId);
    }

    global $wpdb;
    $family_members = [];

    if ($user->getDatabaseData()["familyId"] == null) {
      return new Family($family_members, []);
    }

    $query = "SELECT * FROM `sail_users` WHERE familyid = ";
    $query .= $user->getDatabaseData()["familyId"];

    $results = $wpdb->get_results($query);

    foreach ($results as $fm) {
      if ($fm->userId != $user->userId) {
        $su = UserDao::getInstance()->getSailUserById($fm->userId);
        array_push($family_members, $su->getDatabaseData());
      }
    }

    return new Family($family_members, []);
  }

  public function getPendingFamilyMembersForUser(User $user): Family
  {
    if ($this->isCached($user->userId)) {
      return $this->getCachedValue($user->userId);
    }

    global $wpdb;
    $family_members = [];

    if ($user->getDatabaseData()["familyId"] == null) {
      return new Family($family_members, []);
    }

    $query = "SELECT * FROM `sail_family` WHERE familyid = ";
    $query .= $user->getDatabaseData()["familyId"];
    $query .= " AND isConfirmed = 0";

    $results = $wpdb->get_results($query);

    foreach ($results as $fm) {
      if (($fm->userId1 == $user->userId || $fm->userId2 == $user->userId) && $fm->confirmingUserId != $user->userId) {
        $su = UserDao::getInstance()->getSailUserById($fm->confirmingUserId);
        array_push($family_members, $su->getDatabaseData());
      }
    }

    return new Family($family_members, []);
  }

  public function getNeedsConfirmationsForUser(User $user): Family
  {
    global $wpdb;
    $family_members = [];

    $query = "SELECT * FROM `sail_family` WHERE confirmingUserId = ";
    $query .= $user->getDatabaseData()['userId'];
    $query .= " AND isConfirmed = 0";

    $results = $wpdb->get_results($query);

    foreach ($results as $fm) {
      $userIdThatStartedTheLink = ($fm->userId1 != $user->getDatabaseData()['userId']) ? $fm->userId1 : $fm->userId2;
      $su = UserDao::getInstance()->getSailUserById($userIdThatStartedTheLink);
      array_push($family_members, $su->getDatabaseData());
    }

    return new Family($family_members, []);
  }
}
