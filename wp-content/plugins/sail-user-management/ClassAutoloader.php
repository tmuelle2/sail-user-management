<?php

class ClassAutoloader {
    private static $classPathMap;

    private static function init() {
        if (isset($classPathMap)) {
            return;
        }

        error_log('Initializing library paths...');
        $HOME_DIR = '/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/';
        $libPaths = glob($HOME_DIR . 'paypalhttp_php-1.0.0/lib/PayPalHttp/Serializer/*.php');
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'paypalhttp_php-1.0.0/lib/PayPalHttp/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/*/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'mailchimp-marketing-php-3.0.69/lib/*.php'));
        $libPaths = array_merge($libPaths, glob($HOME_DIR . 'mailchimp-marketing-php-3.0.69/lib/Api/*.php'));
        $classPathMap = array();
        foreach ($libPaths as $path) {
            $split = explode('/', $path);
            $justFileName = basename(end($split), '.php');
            $classPathMap[$justFileName] = $path;
        }
        error_log('Class path map: ' . print_r($classPathMap, true));
    }

    public static function autoload($className) {
        self::init(); 
        error_log('ClassAutoloader loading: ' . $class_name);
        $split = explode('\\', $class_name);
        $justClassName = end($split);
        if (!empty($justClassName) && isset($libraryPaths[$justClassName])) {
            include $libraryPaths[$justClassName];
        }
    }
}

$autoloader = new ClassAutoloader();
spl_autoload_register(array($autoloader, 'autoload'));