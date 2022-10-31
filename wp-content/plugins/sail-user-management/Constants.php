<?php

namespace Sail;

define('SITE_URL', get_site_url());

class Constants
{
    public const HOME_DIR = __DIR__ . '/';

    public const HTML_DIR = self::HOME_DIR . 'pages/html/';
    public const TEMPLATE_DIR = self::HOME_DIR . 'pages/templates/';

    public const ROOT_CONTENT_ROUTE = '/wp-content/plugins/sail-user-management/pages/';
    public const CSS_ROUTE = self::ROOT_CONTENT_ROUTE . 'css/';
    public const CSS_COMMON = self::CSS_ROUTE . 'common.css';
    public const JS_ROUTE = self::ROOT_CONTENT_ROUTE . 'js/';
    public const JS_COMMON = self::JS_ROUTE . 'common.js';
    public const IMAGES_ROUTE = self::ROOT_CONTENT_ROUTE . 'images/';

    public const JS_COMMON_SCRIPT_HANDLE = 'sailCommonJs';

    public const PROD_DOMAIN = 'sailhousingsolutions.org';
    public const DEV_DOMAIN = 'localhost';

    public const FORM_REST_PREFIX = '/?rest_route=/forms/v1/';
    public const API_PREFIX = '/?rest_route=/';
}
