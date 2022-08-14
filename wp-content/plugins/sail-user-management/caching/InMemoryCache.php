<?php

namespace Sail\Caching;

use Sail\Utils\Singleton;

/**
 * A simple SailCache implementation trait that stores cached values in an internal static
 * associative array.  This means that the cache life cycle is one request.  It uses the calling
 * object's class name as namespace to avoid cross-class key collisions.  Note that different
 * instances of the same class share namespaces and therefore cached values.
 *
 * TODO: Compare this performance against Wordpress' transients https://developer.wordpress.org/reference/functions/set_transient/
 */
trait InMemoryCache //implements SailCache // Traits can't currently (PHP<=8.x) implement interfaces
{
    use Singleton;

    private array $cache = array();

    public function isCached(string $key): bool
    {
        return isset($this->cache[$key]);
    }

    public function cache(string $key, $val)
    {
        $this->cache[$key] = serialize($val);
        return $val;
    }

    public function getCachedValue(string $key)
    {
        return unserialize($this->cache[$key]);
    }
}
