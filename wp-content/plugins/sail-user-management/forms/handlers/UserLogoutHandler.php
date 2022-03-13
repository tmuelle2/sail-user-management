<?php

namespace Sail\Form\Handlers;

use WP_REST_Request;
use WP_REST_Response;

use Sail\Data\Dao\UserDao;
use Sail\Utils\EmailSender;
use Sail\Utils\Logger;
use Sail\Utils\Singleton;

class UserLogoutHandler extends SailFormHandler
{
    use Logger;
    use Singleton;

    private const ACTION_NAME = "logout";

    public function __construct()
    {
        parent::__construct(self::ACTION_NAME, true, false);
    }

    public function permissionCallback(): bool
    {
        return true;
    }

    public function callback(WP_REST_Request $request): WP_REST_Response
    {
        wp_logout();
        return $this->response200WithClientsideRedirect('/');
    }
}
