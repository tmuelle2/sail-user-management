<?php

namespace Sail\Form\Handlers;

use WP_REST_Request;
use WP_REST_Response;

use Sail\Data\Dao\UserDao;
use Sail\Data\Dao\FriendshipConnectDao;
use Sail\Data\Model\FriendshipConnectProfile;
use Sail\Utils\EmailSender;
use Sail\Utils\Logger;
use Sail\Utils\Singleton;
use Sail\Utils\HtmlUtils;

class FriendshipConnectProfileRegistrationHandler extends SailFormHandler
{
        use Logger;
        use Singleton;

        private const ACTION_NAME = "fc-registration";
        private FriendshipConnectDao $fcDao;
        private UserDao $userDao;

        public function __construct()
        {
                $this->fcDao = FriendshipConnectDao::getInstance();
                $this->userDao = UserDao::getInstance();
                parent::__construct(self::ACTION_NAME, true, false);
        }

        public function callback(WP_REST_Request $request)
        {
                // Extract form data
                $this->log("%%%%%% FC Reg %%%%%% ");
                $user = $this->userDao->getSailUser();
                $params = $request->get_json_params();
                $params["namePreference"] = "Nickname";
                $params["userId"] = $user->getDatabaseData()["userId"];
                if (str_contains($params["authorized"], "1")) {
                        $params["authorized"] = "1";
                }
                $this->log("%%%%%% params: ");
                $this->log(print_r($params, true));
                $files = $request->get_file_params();
                $fcProfile = HtmlUtils::getFriendshipConnectProfileFormData($params);

                $this->log("%%%%%% fcprofile: ");
                $this->log(print_r($fcProfile, true));


                // Throw a 40x if they are not a paid member or a fc profile already exists
                if (!$user->isDuePayingUser())
                        return $this->response403();
                if ($this->fcDao->getFcProfile() != null)
                        return $this->response400();

                // Upload the profile pic
                $fcProfile = $fcProfile->merge(['profilePicture' => ("http://sailhousingsolutions.org/wp-admin/identicon.php?size=200&hash=" . md5($user->email))]);
                if (
                        isset($files['profilePicture']) && isset($files['profilePicture']['name']) && isset($files['profilePicture']['name'])
                        && !empty($files['profilePicture']['name']) && !empty($files['profilePicture']['name'])
                ) {
                        $upload = wp_upload_bits($files['profilePicture']['name'], null, file_get_contents($files['profilePicture']['tmp_name']));

                        if (!$upload['error'])
                                $fcProfile = $fcProfile->merge(['profilePicture' => $upload['url']]);
                }

                // Create the fc profile and send an email to admins that it needs approval
                $this->fcDao->createFcProfile($fcProfile);
                EmailSender::sendFcProfileCreatedEmail();

                // Success redirect
                return $this->response200WithClientsideRedirect('/user');
        }
}
