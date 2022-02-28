<?php

namespace Sail\Data\Dao;

use Sail\Caching\InMemoryCache;
use Sail\Data\Model\User;
use Sail\Utils\Logger;
use Sail\Utils\Singleton;

class UserDao
{
  use InMemoryCache;
  use Logger;
  use Singleton;

  private const DEFAULT_FORMAT = 'ARRAY_A';

  // Returns the sail User for the currently logged in user
  public function getSailUser(): User
  {
    $user = wp_get_current_user();
    return $this->getSailUserById($user->id);
  }

  // Returns the sail User row corresponding to the userid
  public function getSailUserById(string $user_id): User
  {
    return $this->getUser($user_id, self::DEFAULT_FORMAT);
  }

  public function updateUser(User $user, array $updates): User
  {
    return $this->updateUserWithUpdatesAlreadySet($user->merge($updates));
  }

  public function updateUserWithUpdatesAlreadySet(User $user): User
  {
    global $wpdb;
    $wpdb->update('sail_users', $user, array('userId' => $user->userId), User::fieldKeys());
    return $user;
  }

  public function createUser(User $user): User
  {
    global $wpdb;

    // Create Wordpress user
    $this->log("Creating wp user...");
    $user_id = wp_create_user(
      $user->email,
      $user->password,
      $user->email
    );
    $this->log("Wp user created: ");
    $this->log(print_r($user_id, true));
    $data['userId'] = $user_id;

    // Insert into SAIL users db table
    $this->log("Attempting to create sail_user with this data: ");
    $this->log(print_r($data, true));
    $wpdb->insert('sail_users', $data, User::fieldKeys());
    $this->log("Sail_user created");
    return $this->cache($user->user_id, $user);
  }

  public function verifyUserEmail($email, $verificationKey)
  {
    // Get sail user from DB
    $user = $this->getUserByEmail($email, self::DEFAULT_FORMAT);
    $this->log("Verifying email of: " . print_r($user, true));
    if (isset($user) && $user->emailVerificationKey == $verificationKey) {
      $this->updateUser($user, ['emailVerified' => true]);
    }
  }

  private function getUser(string $user_id, string $output_format): User
  {
    if ($this->isCached($user_id)) {
      return $this->getCachedValue($user_id);
    }

    global $wpdb;
    $query = "SELECT * FROM `sail_users` WHERE userid = ";
    $query .= $user_id;

    $result = $wpdb->get_row($query, $output_format);

    return count($result) == 0 ? null : self::cache($user_id, new User($result[0]));
  }

  private function getUserByEmail(string $email, string $output_format): User
  {
    if ($this->isCached($email)) {
      return $this->getCachedValue($email);
    }

    global $wpdb;
    $query = "SELECT * FROM `sail_users` WHERE email = '" . $email . "'";

    $result = $wpdb->get_row($query, $output_format);

    return count($result) == 0 ? null : self::cache($email, new User($result[0]));
  }
}
