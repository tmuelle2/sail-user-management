<?php

namespace Sail\Form\Handlers;

use WP_REST_Request;
use WP_REST_Response;

use Sail\Data\Dao\UserDao;
use Sail\Utils\EmailSender;
use Sail\Utils\Logger;
use Sail\Utils\Singleton;

class ForgotPasswordHandler extends SailFormHandler
{
    use Logger;
    use Singleton;

    private const ACTION_NAME = "forgot-password";
    private UserDao $dao;

    public function __construct()
    {
        $this->dao = UserDao::getInstance();
        parent::__construct(self::ACTION_NAME, false, true);
    }

    public function callback(WP_REST_Request $request)
    {
        $email = $request->get_json_params('email');
        $wpUser = get_user_by('login', $email);
        if (username_exists($email) && email_exists($email) && !is_wp_error($wpUser)) {
            EmailSender::sendForgotPasswordEmail($wpUser);

            if (is_wp_error($wpUser)) {
               return $this->response400();
            } else {
                return $this->response200WithClientsideRedirect('/login');
            }
        }
        return $this->response400();
    }
}
