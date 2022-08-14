<?php

namespace Sail\Api;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use Sail\Utils\Logger;

abstract class SailApi
{
  use Logger;

  abstract protected function getRoutePrefix(): string;
  abstract protected function getApiRoute(): string;
  abstract protected function getMethod(): string;
  abstract public function callback(WP_REST_Request $request); // WP_REST_Response | WP_Error
  abstract public function permissionCallback(): bool;

  public function registerApi()
  {
    register_rest_route($this->getRoutePrefix(), $this->getApiRoute(), array(
      'methods' => $this->getMethod(),
      'callback' => array($this, 'callback'),
      'permission_callback' => array($this, 'permissionCallback'),
    ));
  }

  protected function response403(): WP_Error
  {
    return new WP_Error('rest_unauthorized', __('Only authenticated users can access this api.', 'rest_unauthorized'), array('status' => 403));
  }

  protected function response400(): WP_Error
  {
    return new WP_Error('bad_request', __('Missing required field', 'bad_request'), array('status' => 400));
    exit;
  }

  protected function response302WithClientsideRedirect(string $redirectLocation): WP_REST_Response
  {
    return new WP_REST_Response(null, 302, array('Location' => $redirectLocation));
  }

  protected function response200WithClientsideRedirect(string $redirectLocation): WP_REST_Response
  {
    $this->log("returning 200 with redirect to: " . $redirectLocation);
    return new WP_REST_Response(null, 200, array('Location' => $redirectLocation));
  }
}
