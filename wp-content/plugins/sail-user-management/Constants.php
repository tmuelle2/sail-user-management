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
    public const JS_ROUTE = self::ROOT_CONTENT_ROUTE . 'js/';

    public const PROD_DOMAIN = 'sailhousingsolutions.org';
    public const DEV_DOMAIN = 'localhost';

    public const FORM_REST_PREFIX = '/wp-json/forms/v1/';
}
