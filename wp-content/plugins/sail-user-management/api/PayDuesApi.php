<?php

namespace Sail\Api;

use WP_REST_Request;
use WP_REST_Response;

use Sail\Clients\PayPalOrder;
use Sail\Utils\Singleton;

class PayDuesApi extends SailApi
{
    use Singleton;

    protected function getRoutePrefix(): string
    {
        return 'membership/v1';
    }

    protected function getApiRoute(): string
    {
        return '/dues';
    }

    protected function getMethod(): string
    {
        return 'POST';
    }

    public function callback(WP_REST_Request $request)
    {
        if (!is_user_logged_in()) {
            return $this->response403();
        }
        //$json = $request->get_json_params();
        //if (!isset($json['id'])) {
        //    return $this->response400();
        //}
        PayPalOrder::recordOrder(date('Y-m-d'));
        return new WP_REST_Response();
    }

    public function permissionCallback(): bool
    {
        return is_user_logged_in();
    }
}
