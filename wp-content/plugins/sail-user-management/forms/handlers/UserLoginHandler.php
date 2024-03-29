<?php

namespace Sail\Form\Handlers;

use Sail\Constants;
use WP_REST_Request;
use WP_REST_Response;

use Sail\Utils\Logger;
use Sail\Utils\Singleton;
use Sail\Utils\WebUtils;
use WP_Error;

class UserLoginHandler extends SailFormHandler
{
    use Logger;
    use Singleton;

    private const ACTION_NAME = "login";

    public function __construct()
    {
        parent::__construct(self::ACTION_NAME, false, true);
    }

    public function callback(WP_REST_Request $request)
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
                $redirect = $json['redirect_to'];

                if (isset($redirect) && (strpos($redirect, Constants::PROD_DOMAIN) !== false || strpos($redirect, Constants::DEV_DOMAIN) !== false)) {
                    return $this->response200WithClientsideRedirect($redirect);
                } else {
                    return $this->response200WithClientsideRedirect('/user');
                }
            }
        }
        else {
           WebUtils::redirect('/error-message?title=Email Does Not Exist&message=The email you used to login is not connected to any membership account. Please try again with a different email. If you have any questions, please contact: info@sailhousingsolutions.org'); 

        }
        return $this->response400();
    }
}

