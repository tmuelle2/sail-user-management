<?php

namespace Sail\Utils;

/**
 * A trait that when added to a class makes it use a Singleton.
 * It makes private magic functions that create instances and vends
 * a public method get_instance() to retrieve the only copy of an
 * object.
 */
trait Singleton
{
    private static $instances = [];

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function __unserialize(array $data)
    {
    }

    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
    }
}
