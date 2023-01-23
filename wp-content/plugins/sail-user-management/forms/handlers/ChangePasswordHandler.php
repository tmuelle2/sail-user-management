<?php

namespace Sail\Form\Handlers;

use WP_REST_Request;

use Sail\Data\Dao\UserDao;
use Sail\Utils\Logger;
use Sail\Utils\Singleton;

class ChangePasswordHandler extends SailFormHandler
{
    use Logger;
    use Singleton;

    private const ACTION_NAME = "change-password";
    private UserDao $dao;

    public function __construct()
    {
        $this->dao = UserDao::getInstance();
        parent::__construct(self::ACTION_NAME, true, true);
    }

    public function callback(WP_REST_Request $request)
    {
        $password = $request->get_param('password');
        $confirmPassword = $request->get_param('confirmPassword');
        $key = $request->get_param('pw_reset_key');
        $email = $request->get_param('user_email');

        if ($password != $confirmPassword) {
            // url is missing the key for password reset
            $this->log("ERROR: password and confirmPassword do not match!");
            return $this->response400();
        }

        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            wp_set_password($password, $user->ID);
            return $this->response200WithClientsideRedirect('/login');
        } else if (strlen($key) != 0 || strlen($email) != 0) {
            $user = check_password_reset_key($key, $email);
            if (username_exists($email) && email_exists($email) && !is_wp_error($user)) {
                wp_set_password($password, $user->ID);
                return $this->response200WithClientsideRedirect('/login');
            } else {
                $this->log("ERROR: check_password_reset_key() failed or user does not exist!");
                return $this->response400();
            }
        } else {
            // url is missing the key for password reset
            $this->log("ERROR: User is not logged in and trying to reset their password without a key/email parameter in the url.");
            $this->log("Now printing debug vars: " . print_r($key, true) . '\n' . print_r($email, true));
            return $this->response400();
        }
    }
}
