<?php

namespace Sail;

define('SITE_URL', get_site_url());

class Constants
{
    public const HOME_DIR = __DIR__ . '/';

    public const CSS_DIR = self::HOME_DIR . 'pages/css/';
    public const HTML_DIR = self::HOME_DIR . 'pages/html/';
    public const JS_DIR = self::HOME_DIR . 'pages/js/';
    public const TEMPLATE_DIR = self::HOME_DIR . 'pages/templates/';

    public const PROD_DOMAIN = 'sailhousingsolutions.org';
    public const DEV_DOMAIN = 'localhost';
    
    public const FORM_REST_PREFIX = '/wp-json/forms/v1/';
}
