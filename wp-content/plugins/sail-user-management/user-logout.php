<?php

use Sail\Utils\WebUtils;

if (is_user_logged_in()) {
    wp_logout();
    WebUtils::redirect('');
    exit;
}else {
    // Fail redirect 
    WebUtils::redirect('');
    exit;
}
