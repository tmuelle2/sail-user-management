<?php

namespace Sail\Form\Handlers;

use WP_REST_Request;
use WP_REST_Response;

use Sail\Data\Dao\UserDao;
use Sail\Utils\HtmlUtils;
use Sail\Utils\Logger;
use Sail\Utils\Singleton;
use Sail\Form\Handlers\SailFormHandler;

class UserProfileUpdateHandler extends SailFormHandler
{
    use Logger;
    use Singleton;

    private const ACTION_NAME = "user-update";
    private UserDao $dao;

    public function __construct()
    {
        $this->dao = UserDao::getInstance();
        parent::__construct(self::ACTION_NAME, true, false);
    }

    public function callback(WP_REST_Request $request)
    {
        $this->log("$$$$$$$$$ USser Update$$$$$$$$$$ ");
        $params = $request->get_json_params();
        $this->log("$$$$$$$$$ params3: ");
        $this->log(print_r($params, true));
        $curUser = $this->dao->getSailUser();
        $this->log(print_r($curUser, true));
        $userUpdate = HtmlUtils::getUserFormData($params, $curUser);

        // Update SAIL users db table
        $this->dao->updateUserWithUpdatesAlreadySet($userUpdate);

        // Success redirect
        return $this->response200WithClientsideRedirect('/user?alert=success');
    }
}
