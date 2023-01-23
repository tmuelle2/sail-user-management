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
        $params = $request->get_json_params();
        $wpUser = get_user_by('login', $params["email"]);
        if (username_exists($params["email"]) && email_exists($params["email"]) && !is_wp_error($wpUser)) {
            EmailSender::getInstance()->sendForgotPasswordEmail($wpUser);

            if (is_wp_error($wpUser)) {
               return $this->response400();
            } else {
                return $this->response200WithClientsideRedirect('/login');
            }
        }
        return $this->response400();
    }
}
