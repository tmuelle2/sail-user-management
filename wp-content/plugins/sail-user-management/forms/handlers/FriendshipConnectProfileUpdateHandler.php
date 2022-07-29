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
        $this->log("ENTERED FC UPDATE CALLBACK");
        $curMember = $this->fcDao->getFcProfile();
        $this->log("curMember:");
        $this->log(print_r($curMember, true));
        // $this->log("get_json_params:");
        // $this->log(print_r($request->get_json_params(), true)); //prints very long base64 string
        $memberUpdate = HtmlUtils::getFriendshipConnectProfileFormData($request->get_json_params(), $curMember);
        // $this->log("memberUpdate1:");
        // $this->log(print_r($memberUpdate, true)); //prints very long base64 string

        // !!!! change updateProfilePic so it actually works !!!!!
        $memberUpdate = $memberUpdate->updateProfilePic($request, $curMember->getDatabaseData()["profilePicture"]);
        // $this->log("memberUpdate2:");
        // $this->log(print_r($memberUpdate, true)); //prints very long base64 string

        // Update FC members db table
        $this->fcDao->updateFcProfileWithUpdatesAlreadySet($memberUpdate);

        return $this->response200WithClientsideRedirect('/user');
    }
}
