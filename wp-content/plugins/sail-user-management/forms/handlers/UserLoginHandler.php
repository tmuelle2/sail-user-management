<?php

namespace Sail\Form\Handlers;

use Sail\Constants;
use WP_REST_Request;
use WP_REST_Response;

use Sail\Utils\Logger;
use Sail\Utils\Singleton;

class UserLoginHandler extends SailFormHandler
{
    use Logger;
    use Singleton;

    private const ACTION_NAME = "login";

    public function __construct()
    {
        parent::__construct(self::ACTION_NAME, false, true);
    }

    public function permissionCallback(): bool
    {
        return true;
    }

    public function callback(WP_REST_Request $request): WP_REST_Response
    {
        // Login Wordpress user
        $json = $request->get_json_params();
        $email = $json['email'];
        $password = $json['password'];
        $remember = $json['remember'];
        if (username_exists($email) && email_exists($email)) {
            $creds = array(
                'user_login'    => $email,
                'user_password' => $password,
                'remember'      => $remember
            );

            $user = wp_signon($creds, is_ssl());

            if (is_wp_error($user)) {
                // Fail redirect
                $this->log($user->get_error_message());
                return $this->response400();
            } else {
                // Success redirect
                wp_set_current_user($user->ID, $user->data->user_login);
                $redirect = $json['redirect_to'];

                if (isset($redirect) && (strpos($redirect, Constants::PROD_DOMAIN) !== false || strpos($redirect, Constants::DEV_DOMAIN) !== false)) {
                    return $this->response200WithClientsideRedirect($redirect);
                } else {
                    return $this->response200WithClientsideRedirect('/user');
                }
            }
        }
        return $this->response400();
    }
}
