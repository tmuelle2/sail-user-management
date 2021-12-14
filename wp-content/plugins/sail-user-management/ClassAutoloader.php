<?php

class ClassAutoloader {
    private static $classPathMap = array();

    private static function init() {
        if (!empty(self::$classPathMap)) {
            return;
        }

        error_log('Initializing library paths...');
        $HOME_DIR = '/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/';
        $libPaths = glob($HOME_DIR . 'paypalhttp_php-1.0.0/lib/PayPalHttp/Serializer/*.php');
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'paypalhttp_php-1.0.0/lib/PayPalHttp/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/*/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'mailchimp-marketing-php-3.0.69/lib/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'mailchimp-marketing-php-3.0.69/lib/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'mailchimp-marketing-php-3.0.69/lib/Api/*.php'));
        // The plugin wp-data-access currently loads Guzzle transitively
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'guzzle-7.2.0/src/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'guzzle-7.2.0/src/Cookie/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'guzzle-7.2.0/src/Exception/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'guzzle-7.2.0/src/Handler/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'psr7-2.0.0/src/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'promises-1.5.0/src/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'http-client-1.0.1/src/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'http-message-1.0/src/*.php'));
        
        $namespaceRegex =  '/^namespace (.*);$/m';
        $functionRegex =  '/^function (.*)\(.*$/m';
        foreach ($libPaths as $path) {
            $split = explode('/', $path);
            $fileNameWithExt = end($split);
            $justFileName = basename($fileNameWithExt, '.php');
            $fileContents = file_get_contents($path);
            // This loads full namespaced classes assuming file name and class name matches 
            if (preg_match($namespaceRegex, $fileContents, $namespaceMatches)) {
                self::$classPathMap[$namespaceMatches[1] . '\\' . $justFileName] = $path;
            // This hack loads some GuzzleHttp functions 
            } else if (self::endsWith($path, 'functions.php')) {
                $preg_match($functionRegex, $fileContents, $functionMatches);
                // Every other match will be the group with the function name
                for ($i = 1; $i < count($functionMatches); $i += 2) {
                    self::$classPathMap[$functionMatches[i]] = $path;
                }
            // This loads naked classes without namespaces assuming file name and class name matches 
            } else {
                self::$classPathMap[$justFileName] = $path;
            }
        }
        //error_log('Class path map: ' . print_r(self::$classPathMap, true));
    }

    public static function autoload($className) {
        self::init(); 
        //error_log('ClassAutoloader loading: ' . $className);
        if (!class_exists($className, false) && !function_exists($className) && isset(self::$classPathMap[$className])) {
            include_once(self::$classPathMap[$className]);
        } else {
            $split = explode('\\', $className);
            $justClassName = end($split);
            if (!empty($justClassName) && !class_exists($justClassName, false) && !function_exists($justClassName) && isset(self::$classPathMap[$justClassName])) {
                include_once(self::$classPathMap[$justClassName]);
            }
        }
    }

    // This can be deleted if Bluhost migrates to PHP 8
    // https://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
    private static function endsWith( $haystack, $needle ) {
        $length = strlen( $needle );
        if( !$length ) {
            return true;
        }
        return substr( $haystack, -$length ) === $needle;
    }
}