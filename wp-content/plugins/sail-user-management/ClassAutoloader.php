<?php

class ClassAutoloader {
    private static $classPathMap = array();

    private static function init() {
        if (!empty(self::$classPathMap)) {
            return;
        }

        //error_log('Initializing library paths...');
        $HOME_DIR = '/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/';
        $libPaths = glob($HOME_DIR . 'paypalhttp_php-1.0.0/lib/PayPalHttp/Serializer/*.php');
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'paypalhttp_php-1.0.0/lib/PayPalHttp/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/*/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'mailchimp-marketing-php-3.0.69/lib/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'mailchimp-marketing-php-3.0.69/lib/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'mailchimp-marketing-php-3.0.69/lib/Api/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'guzzle-7.2.0/src/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'guzzle-7.2.0/src/Cookie/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'guzzle-7.2.0/src/Exception/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'guzzle-7.2.0/src/Handler/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'psr7-2.0.0/src/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'promises-1.5.0/src/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'http-client-1.0.1/src/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'http-message-1.0/src/*.php'));

        $namespaceRegex =  '/^namespace (.*);$/m';
        foreach ($libPaths as $path) {
            $split = explode('/', $path);
            $fileNameWithExt = end($split);
            $justFileName = basename($fileNameWithExt, '.php');
            if (preg_match($namespaceRegex, file_get_contents($path), $namespaceMatches)) {
                self::$classPathMap[$namespaceMatches[1] . '\\' . $justFileName] = $path;
            } else {
                self::$classPathMap[$justFileName] = $path;
            }
        }
        //error_log('Class path map: ' . print_r(self::$classPathMap, true));
    }

    public static function autoload($className) {
        self::init(); 
        error_log('ClassAutoloader loading: ' . $className);
        if (!class_exists($className, false) && !function_exists($className) && isset(self::$classPathMap[$className])) {
            require_once(self::$classPathMap[$className]);
        } else {
            $split = explode('\\', $className);
            $justClassName = end($split);
            if (!empty($justClassName) && !class_exists($justClassName, false) && !function_exists($justClassName) && isset(self::$classPathMap[$justClassName])) {
                require_once(self::$classPathMap[$justClassName]);
            }
        }
    }
}