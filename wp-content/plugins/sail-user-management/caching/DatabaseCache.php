<?php

namespace Sail\Caching;

/**
 * A simple SailCache implementation trait that stores cached values in database table.
 * This means that the cache life cycle is not bound to any one request.  It uses the calling
 * object's class name as namespace to avoid cross-class key collisions.  Note that different
 * instances of the same class share namespaces and therefore cached values.
 *
 * Since $wpdb caches the results of queries automatically, this does not keep an internal
 * InMemoryCache of results as it would be redundant.
 * Source: https://developer.wordpress.org/reference/classes/wpdb/#select-a-variable
 *
 * TODO: Compare this performance against Wordpress' transients https://developer.wordpress.org/reference/functions/set_transient/
 */
trait DatabaseCache //implements SailCache // Traits can't currently (PHP<=8.x) implement interfaces
{
    private static $DB_TABLE = 'sail_cache';
    private static $DB_KEY = 'cacheKey';
    private static $DB_VALUE = 'cacheValue';

    public function isCached(string $key): bool
    {
        global $wpdb;
        $value = $wpdb->get_var($this->getCacheQuery($key));
        return isset($value);
    }

    public function cache(string $key, $val)
    {
        global $wpdb;
        $serializedVal = serialize($val);
        if ($this->isCached($key)) {
            $wpdb->update(self::$DB_TABLE, array(self::$DB_KEY => $key, self::$DB_VALUE => $serializedVal), array(self::$DB_KEY => $key));
        } else {
            $wpdb->insert(self::$DB_TABLE, array(self::$DB_KEY => $key, self::$DB_VALUE => $serializedVal));
        }
        return $val;
    }

    public function getCachedValue(string $key)
    {
        global $wpdb;
        return unserialize($wpdb->get_var($this->getCacheQuery($key)));
    }

    private function getCacheQuery(string $key): string
    {
        $tablename = self::$DB_TABLE;
        $colKey = self::$DB_KEY;
        $colVal = self::$DB_VALUE;
        return "SELECT $colVal FROM $tablename WHERE $colKey = '$key'";
    }

    // TODO: Remove this function after table is created and duplicator dumps are updated?
    private static function recreateTable()
    {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        global $wpdb;
        $tablename = self::$DB_TABLE;
        $colKey = self::$DB_KEY;
        $colVal = self::$DB_VALUE;
        $sqlDrop = "DROP TABLE IF EXISTS $tablename";
        $wpdb->query($sqlDrop);
        $sqlCreate = "CREATE TABLE $tablename ($colKey TEXT NOT NULL, $colVal TEXT NOT NULL)";
        maybe_create_table($tablename, $sqlCreate);
    }
}
