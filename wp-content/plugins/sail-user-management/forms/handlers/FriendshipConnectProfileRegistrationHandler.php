<?php

namespace Sail\Form\Handlers;

use WP_REST_Request;
use WP_REST_Response;

use Sail\Data\Dao\FriendshipConnectDao;
use Sail\Utils\Logger;
use Sail\Utils\Singleton;

class FriendshipConnectProfileUpdateHandler extends SailFormHandler
{
    use Logger;
    use Singleton;

    private const ACTION_NAME = "fc-registration";
    private FriendshipConnectDao $fcDao;

    public function __construct()
    {
        $this->fcDao = FriendshipConnectDao::getInstance();
        parent::__construct(self::ACTION_NAME, true, false);
    }

    public function callback(WP_REST_Request $request): WP_REST_Response
    {
        // TODO implement
        return $this->response403();
    }
}
