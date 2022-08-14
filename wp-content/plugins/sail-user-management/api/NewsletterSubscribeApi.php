<?php

namespace Sail\Api;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

use Sail\Clients\MailChimpSailNewsletterClient;
use Sail\Data\Dao\UserDao;
use Sail\Utils\Singleton;

class NewsletterSubscribeApi extends SailApi
{
    use Singleton;

    private MailChimpSailNewsletterClient $client;

    private function __construct()
    {
        $this->client = MailChimpSailNewsletterClient::getInstance();
    }

    protected function getRoutePrefix(): string
    {
        return 'newsletter/v1';
    }

    protected function getApiRoute(): string
    {
        return '/subscribe';
    }

    protected function getMethod(): string
    {
        return 'POST';
    }

    public function callback(WP_REST_Request $request)
    {
        if (is_user_logged_in()) {
            $response = $this->client->subscribe(UserDao::getInstance()->getSailUser()->email);
        } else {
            if (!isset($request->get_json_params()['email'])) {
                return $this->response400();
            }
            $response = $this->client->subscribe($request->get_json_params()['email']);
        }
        if (isset($response)) {
            return new WP_REST_Response($status = $response->status);
        }
        return new WP_Error('subscribe_error', 'Could not subscribe to newsletter', array('status' => 500));
    }

    public function permissionCallback(): bool
    {
        return true;
    }
}
