<?php

namespace Sail\Form\Handlers;

use WP_REST_Request;
use WP_REST_Response;

use Sail\Data\Dao\UserDao;
use Sail\Utils\EmailSender;
use Sail\Utils\Logger;
use Sail\Utils\Singleton;

class AddFamilyMemberHandler extends SailFormHandler
{
    use Logger;
    use Singleton;

    private const ACTION_NAME = "add-family-member";
    private UserDao $dao;

    public function __construct()
    {
        $this->dao = UserDao::getInstance();
        parent::__construct(self::ACTION_NAME, true, false);
    }

    public function callback(WP_REST_Request $request)
    {
        $emailInput = $request->get_param('email');
        $user = $this->dao->getSailUser();

        // Send verification email
        $familyLinkingKey = EmailSender::sendAccountLinkingEmail($user, $emailInput);

        $this->log("Sent link-family-member email from {$user->email} to {$emailInput}a");

        // Update SAIL users db table
        $this->dao->updateUser($user, ['familyLinkingKey' => $familyLinkingKey]);

        // Success redirect
        return $this->response200WithClientsideRedirect('/user');
    }
}
