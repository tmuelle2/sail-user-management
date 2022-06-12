<?php

namespace Sail\Data\Model;

class Family
{
  private array $members;

  public function __construct(array $data)
  {
    $this->members = array_map(array($this, 'createUser'), $data);
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
      $str .= $fm->firstName . " " . $fm->lastName . " (" . $fm->email . ") \r\n";
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
