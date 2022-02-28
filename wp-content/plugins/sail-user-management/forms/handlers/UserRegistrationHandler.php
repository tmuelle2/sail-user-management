<?php

namespace Sail\Form\Handlers;

use WP_REST_Request;
use WP_REST_Response;

use Sail\Data\Dao\UserDao;
use Sail\Data\Model\User;
use Sail\Utils\EmailSender;
use Sail\Utils\HtmlUtils;
use Sail\Utils\Logger;
use Sail\Utils\Singleton;

class UserRegistrationHandler extends SailFormHandler
{
    use Logger;
    use Singleton;

    private const ACTION_NAME = "user-registration";

    public function __construct()
    {
        parent::__construct(self::ACTION_NAME, false, true);
    }

    public function callback(WP_REST_Request $request): WP_REST_Response
    {
        // Extract form data
        $params = $request->get_body_params();
        $user = HtmlUtils::getUserFormData($params);

        // Create Wordpress user
        $email = $params['email'];
        $password = $params['password'];
        if (!username_exists($email) && !email_exists($email)) {

            // Send verification email
            $emailVerificationKey = EmailSender::sendVerificationEmail($user);
            $data['emailVerificationKey'] = $emailVerificationKey;
            $data['emailVerified'] = false;
            UserDao::getInstance()->createUser($user->merge($data));

            // Signon user
            $creds = array('user_login' => $email, 'user_password' => $password);
            wp_signon($creds, is_ssl());

            // Success redirect
            return $this->response200WithClientsideRedirect('/upgrade-registration');
        }
        return $this->response400();
    }
}
