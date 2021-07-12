<?php

if (is_user_logged_in()) {
    wp_logout();
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org');
    exit;
}else {
    // Fail redirect 
    nocache_headers();
    wp_safe_redirect('https://sailhousingsolutions.org');
    exit;
}
