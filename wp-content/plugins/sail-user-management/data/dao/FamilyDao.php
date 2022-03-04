<?php

namespace Sail\Data\Dao;

use Sail\Caching\InMemoryCache;
use Sail\Data\Model\Family;
use Sail\Data\Model\User;
use Sail\Utils\Singleton;

class FamilyDao
{
  use InMemoryCache;
  use Singleton;

  public function getFamilyMembersForUser(User $user): Family
  {
    if ($this->isCached($user->ID)) {
      return $this->getCachedValue($user->ID);
    }

    global $wpdb;
    $family_members = [];

    if ($user->family_id == null) {
      return new Family($family_members);
    }

    $query = "SELECT * FROM `sail_users` WHERE familyid = ";
    $query .= $user->family_id;

    $results = $wpdb->get_results($query);

    foreach ($results as $su) {
      if ($su->userid != $user->userid) {
        array_push($family_members, $su);
      }
    }

    return new Family($family_members);
  }
}
