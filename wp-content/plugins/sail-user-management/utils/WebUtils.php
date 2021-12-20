<?php

namespace Sail\Utils;

class WebUtils {
    public static function redirect($route) {
        nocache_headers();
        if ($route[0] != '/') {
            $route = '/' . $route;   
        }
        wp_safe_redirect(get_site_url() . $route);
    }
}