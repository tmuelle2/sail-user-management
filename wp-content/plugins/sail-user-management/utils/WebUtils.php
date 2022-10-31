<?php

namespace Sail\Utils;

use Sail\Constants;

final class WebUtils
{
    public final static function redirect(string $route): void
    {
        nocache_headers();
        if ($route[0] != '/') {
            $route = '/' . $route;
        }
        wp_safe_redirect(SITE_URL . $route);
        exit;
    }

    public final static function isProd(): bool
    {
        return str_contains(SITE_URL, Constants::PROD_DOMAIN);
    }

    public final static function getUrl(string $postfix = ''): string {
        return (self::isProd() ? 'https://' . Constants::PROD_DOMAIN : 'http://'. Constants::DEV_DOMAIN) . $postfix;
    }

    public final static function getFormsApiUrl(string $postfix = ''): string {
        return self::getUrl('/?rest_route=/forms/v1/' . $postfix);
    }
}
