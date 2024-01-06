<?php

namespace Sail\Utils;

final class PhpUtils {
    // Returns true if the array is an associative array
    public static function isAssociativeArray(array $array): bool
    {
        $keys = array_keys($array);
        return $keys !== array_keys($keys);
    }

    // This can be deleted if Bluhost migrates to PHP 8
    // https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
    public static function endsWith(string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }

    // Returns true if on localhost
    public static function isLocalhost($whitelist = ['localhost', '127.0.0.1', '::1']) {
        return in_array($_SERVER['HTTP_HOST'], $whitelist);
    }
}