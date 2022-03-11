<?php

namespace Sail\Form\Handlers;

use Sail\Data\Dao\FriendshipConnectDao;
use Sail\Utils\HtmlUtils;
use WP_REST_Request;
use WP_REST_Response;

use Sail\Utils\Logger;
use Sail\Utils\Singleton;

class FriendshipConnectProfileUpdateHandler extends SailFormHandler
{
    use Logger;
    use Singleton;

    private const ACTION_NAME = "fc-profile-update";
    private FriendshipConnectDao $fcDao;

    public function __construct()
    {
        $this->fcDao = FriendshipConnectDao::getInstance();
        parent::__construct(self::ACTION_NAME, true, false);
    }

    public function callback(WP_REST_Request $request)
    {
        $curMember = $this->fcDao->getFcProfile();
        $memberUpdate = HtmlUtils::getFriendshipConnectProfileFormData($request->get_json_params(), $curMember);
        $memberUpdate = $memberUpdate->updateProfilePic($request);

        // Update FC members db table
        $this->fcDao->updateFcProfileWithUpdatesAlreadySet($memberUpdate);

        return $this->response200WithClientsideRedirect('/user');
    }
}
