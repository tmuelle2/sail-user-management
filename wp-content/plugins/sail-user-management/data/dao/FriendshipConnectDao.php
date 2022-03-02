<?php

namespace Sail\Data\Dao;

use Sail\Caching\InMemoryCache;
use Sail\Data\Model\FriendshipConnectProfile;
use Sail\Utils\Singleton;

class FriendshipConnectDao
{
  use InMemoryCache;
  use Singleton;

  public function getActiveFcProfiles(): array
  {
    global $wpdb;
    $query = "SELECT * FROM `fc_members` WHERE referenceApproved = 1";
    $results = $wpdb->get_results($query);
    return array_map(fn($row) => new FriendshipConnectProfile($row), $results);
  }

  // returns the fc member info of the currently logged in user if it exists
  public function getFcProfile(): FriendshipConnectProfile
  {

    $user = wp_get_current_user();
    return $this->getFcProfileById($user->id);
  }

  public function getFcProfileById(string $userId): FriendshipConnectProfile
  {
    return $this->getFcProfileForUser($userId, 'ARRAY_A');
  }

  public function updateFcProfileWithUpdatesAlreadySet(FriendshipConnectProfile $fcProfile): FriendshipConnectProfile
  {
    global $wpdb;
    $wpdb->update('fc_members', $fcProfile, array('userId' => $fcProfile->userId), FriendshipConnectProfile::fieldKeys());
    return $fcProfile;
  }

  public function createFcProfile(FriendshipConnectProfile $fcProfile): FriendshipConnectProfile
  {
    global $wpdb;
    $wpdb->insert('fc_members', $fcProfile, FriendshipConnectProfile::fieldKeys());
    return $fcProfile;
  }

  private function getFcProfileForUser(string $userId, string $outputFormat): FriendshipConnectProfile
  {
    if ($this->isCached($userId)) {
      return $this->getCachedValue($userId);
    }

    global $wpdb;
    $query = "SELECT * FROM `fc_members` wHERE userid = ";
    $query .= $userId;

    $result = $wpdb->get_results($query, $outputFormat);

    return count($result) == 0 ? null : new FriendshipConnectProfile($result[0]);
  }
}
