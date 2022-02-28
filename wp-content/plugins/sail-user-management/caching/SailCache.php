<?php

namespace Sail\Caching;

/**
 * Implementations of this interface offer methods for storing and retrieving values from a cache.
 * The lifecyle and storage of the cache differs based on implementation.
 */
interface SailCache
{
    /**
     * Returns true if there is a cached value associated with a key, otherwise returns false.
     */
    public function isCached(string $key): bool;

    /**
     * Stores a value in the cache associated with a key that can be used to retrieve it.
     * Doc comments are required for union types in PHP<8
     * @param string $key 
     * @param object|array $val 
     * @return object|array 
     */
    public function cache(string $key, $val);

    /**
     * Returns a cached value associated with a key.
     * Doc comments are required for union types in PHP<8
     * @param string $key 
     * @return object|array 
     */
    public function getCachedValue(string $key);
}
