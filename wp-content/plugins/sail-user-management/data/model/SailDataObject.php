<?php

namespace Sail\Data\Model;

use Sail\Utils\PhpUtils;

/**
 * A trait that when used creates a immutable object that supports both object and array access patterns.
 */
abstract class SailDataObject implements \ArrayAccess
{
  private array $db_data;

  protected function __construct(array $data)
  {
    $this->db_data = $data;
  }

  public abstract static function fieldKeys(): array;

  /**
   * Since SailDataObjects are immutable this method is the only way of updating fields and returns a new SailDataObject with the updates applied.
   */
  public function merge(array $updates): SailDataObject
  {
    $merge = array_merge($this->db_data, $updates);
    $clazz = get_class($this);
    return new $clazz($merge);
  }

  public function dataKeys(): array
  {
    return array_keys($this->db_data);
  }

  public function getDatabaseData(): array
  {
    return $this->db_data;
  }

  protected function validateData($data): bool
  {
    $presentInBoth = array_intersect(array_keys(self::fieldKeys()), array_keys($data));
    if (PhpUtils::isAssociativeArray($data) && count($presentInBoth) == count(self::fieldKeys())) {
      return true;
    }
    throw new \InvalidArgumentException("Sail data object is missing expected data_keys: " . join(",", array_diff($presentInBoth, self::fieldKeys())));
  }

  public function offsetExists($offset): bool
  {
    return array_key_exists($offset, $this->db_data);
  }

  public function offsetGet($offset): mixed
  {
    return $this->offsetExists($offset) ? $this->db_data[$offset] : false;
  }

  public function __isset(string $name): bool
  {
    return isset($this->db_data[$name]);
  }

  public function __get(string $name)
  {
    return $this->__isset($name) ? $this->db_data[$name] : false;
  }

  public function __toString(): string
  {
    return print_r($this->db_data, true);
  }

  public function offsetSet($offset, $value): void
  {
    throw new \InvalidArgumentException("Cannot set property {$offset}. The object is immutable.");
  }

  public function offsetUnset($offset): void
  {
    throw new \InvalidArgumentException("Cannot unset property {$offset}. The object is immutable.");
  }

  public function __set(string $name, $value): void
  {
    throw new \InvalidArgumentException("Cannot set property {$name}. The object is immutable.");
  }

  public function __unset(string $name): void
  {
    throw new \InvalidArgumentException("Cannot set property {$name}. The object is immutable.");
  }
}
