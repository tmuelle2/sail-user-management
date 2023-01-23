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
                //$this->log("%%%%%% FC Reg %%%%%% ");
                $user = $this->userDao->getSailUser();
                $params = $request->get_json_params();
                $params["namePreference"] = "Nickname";
                $params["userId"] = $user->getDatabaseData()["userId"];
                if (str_contains($params["authorized"], "1")) {
                        $params["authorized"] = "1";
                }

                // Override the pfp to the default for now, we'll update it after the fcprofile is created
                $params["profilePicture"] = "http://sailhousingsolutions.org/wp-admin/identicon.php?size=200&hash=" . md5($user->email);

                //$this->log("%%%%%% params: ");
                //$this->log(print_r($params, true));
                $fcProfile = HtmlUtils::getFriendshipConnectProfileFormData($params);

                //$this->log("%%%%%% fcprofile: ");
                //$this->log(print_r($fcProfile, true));

                // Throw a 40x if they are not a paid member or a fc profile already exists
                if (!$user->isDuePayingUser())
                        return $this->response403();
                if ($this->fcDao->getFcProfile() != null)
                        return $this->response400();


                // Create the fc profile (update the pfp) and send an email to admins that it needs approval
                $this->fcDao->createFcProfile($fcProfile);
                $fcProfile = $fcProfile->updateProfilePic($request, $fcProfile->getDatabaseData()["profilePicture"]);
                $this->fcDao->updateFcProfileWithUpdatesAlreadySet($fcProfile);

                EmailSender::getInstance()->sendFcProfileCreatedEmail();

                // Success redirect
                return $this->response200WithClientsideRedirect('/user');
        }
}
