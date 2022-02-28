<?php

namespace Sail\Form\Handlers;

use WP_REST_Request;
use WP_REST_Response;

use Sail\Api\SailApi;
use Sail\Data\Dao\UserDao;
use Sail\Utils\Logger;
use Sail\Utils\Singleton;

class VerifyEmailApi extends SailApi
{
    use Logger;
    use Singleton;

    private UserDao $dao;

    public function __construct()
    {
        $this->dao = UserDao::getInstance();
    }

    protected function getRoutePrefix(): string
    {
        return 'membership/v1';
    }

    protected function getApiRoute(): string
    {
        return 'verify-email';
    }

    protected function getMethod(): string
    {
        return 'GET';
    }

    public function permissionCallback(): bool { 
        return is_user_logged_in();
        //WebUtils::redirect('/login?redirect_to=' . urlencode(home_url(add_query_arg($_GET,$wp->request))) );
    }

    public function callback(WP_REST_Request $request): WP_REST_Response
    {
        $email = $request->get_query_params('email');
        $verificationKey = $request->get_query_params('verification_key');
        $this->log("Attempting to verify email $email with code $verificationKey");
        // Ensure query string parameters exist 
        if (isset($verifyKey) && isset($email) && email_exists($_GET['email'])) 
        {
            return $this->response200WithClientsideRedirect('/success-message?title=Thank you, your email has been verified.&message=%3Ca%20href%3D%22https%3A%2F%2Fsailhousingsolutions.org%2Fuser%22%3EClick%20here%20to%20go%20to%20your%20profile%20page.%3C%2Fa%3E');
        } else {
            return $this->response400();
        }
    }
}
