<?php

namespace Sail\Data\Model;

class Family extends SailDataObject
{
  // Mapping of fields to database format
  private const FAMILY_DB_FIELDS = array(
    'relationId' => '%s',
    'familyId' => '%s',
    'userId1' => '%s',
    'userId2' => '%s',
    'confirmingUserId' => '%s',
    'isConfirmed' => '%s'
  );

  private array $members;

  public function __construct(array $memberData, array $relationData)
  {
    $this->members = array_map(array($this, 'createUser'), $memberData);
    if (isset($relationData) && count($relationData) > 0)
      parent::__construct($relationData, self::FAMILY_DB_FIELDS);
  }

  public static function fieldKeys(): array
  {
    return self::FAMILY_DB_FIELDS;
  }

  public function getMembers(): array
  {
    return $this->members;
  }

  public function __toString(): string
  {
    if (count($this->members) == 0) {
      return "None";
    }
    $str = "";
    foreach ($this->members as $fm) {
      $str .= $fm->firstName . " " . $fm->lastName . " (" . $fm->email . ") \r\n<br/>";
    }
    return $str;
  }

  public function __toConfirmationString(): string
  {
    if (count($this->members) == 0) {
      return "None";
    }
    $str = "";
    foreach ($this->members as $fm) {
      $str .= $fm->firstName . " " . $fm->lastName . " (" . $fm->email . ")
        <form >
          <button>Confirm</button>
          <button>Deny</button>
          <br/>
        </form>";
    }
    return $str;
  }

  private function createUser(array $userData): ?User
  {
    if (!isset($userData) || count($userData) < 1)
      return null;
    return new User($userData);
  }
}
