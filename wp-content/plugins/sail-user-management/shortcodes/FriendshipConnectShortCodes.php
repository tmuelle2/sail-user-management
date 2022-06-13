<?php

namespace Sail\Shortcodes;

use Sail\Caching\InMemoryCache;
use Sail\Constants;
use Sail\Data\Dao\FriendshipConnectDao;
use Sail\Data\Dao\UserDao;
use Sail\Utils\FormUtils;
use Sail\Utils\HtmlUtils;
use Sail\Utils\Singleton;
use Sail\Utils\WebUtils;
use Sail\Utils\Logger;

final class FriendshipConnectShortCodes extends ShortCodeRegistrator
{
    use Singleton;
    use Logger;

    private const CACHE_KEY = "userShortCodes";

    public function getShortcodes(): array
    {
        return [

            /**
             * userFCExampleProfile shortcode
             */
            new class extends SailShortcode
            {
                public function getName(): string
                {
                    return 'userFCExampleProfile';
                }
                public function getShortcodeContent(): string
                {
                    return HtmlUtils::getSailTemplate('fc-example-profile.php');
                }
            },

            /**
             * userFCLanding shortcode
             */
            new class extends SailShortcode
            {
                public function getName(): string
                {
                    return 'userFCLanding';
                }
                public function getShortcodeContent(): string
                {
                    if (is_user_logged_in()) {
                        return HtmlUtils::getSailPage('fc-landing-login.html');
                    } else {
                        return HtmlUtils::getSailPage('fc-landing-nologin.html');
                    }
                }
            },

            /**
             * userFCProfileUpdate shortcode
             */
            new class extends SailShortcode
            {
                public function getName(): string
                {
                    return 'userFCProfileUpdate';
                }
                public function getShortcodeContent(): string
                {
                    $fcMember = FriendshipConnectDao::getInstance()->getFcProfile();
                    if (!isset($fcMember) || !isset($fcMember->userId) || $fcMember->userId < 1) return '';
                    return HtmlUtils::getSailTemplate('fc-profile-update.php', ['fcProfile' => $fcMember]);
                }
            },

            /**
             * userFCRegistration shortcode
             */
            new class extends PreprocessingSailShortcode
            {
                use Logger;
                public function getName(): string
                {
                    return 'userFCRegistration';
                }
                public function preprocessingCallback()
                {
                    $this->log("@@@ userFC Reg preprocess callback");
                    $sailUser = UserDao::getInstance()->getSailUser();
                    // TODO: figure out how to make preprocess independent of knowing the page slug/id
                    if (is_page('join-friendship-connect')) {
                        if (!is_user_logged_in()) {
                            $this->log("@@@@ not logged in.");
                            WebUtils::redirect('/login');
                        } else if (!$sailUser->isDuePayingUser()) {
                            $this->log("@@@@ not paid.");
                            WebUtils::redirect(urlencode('/error-message?title=You need to be a paying member to create a Friendship Connect Profile.&message=To pay dues, <a href="/user">click here to go to your profile page.</a>'));
                        } else if (!$sailUser->emailVerified) {
                            $this->log("@@@@ not verified.");
                            WebUtils::redirect(urlencode('/error-message?title=You need to verify your email in order to create a Friendship Connect Profile.&message=To verify your email, <a href="/user">click here to go to your profile page.</a>'));
                        } else if (null !== FriendshipConnectDao::getInstance()->getFcProfile()) {
                            $this->log("@@@@ already exists.");
                            WebUtils::redirect(urlencode('/error-message?title=You have already created a Friendship Connect Profile.&message=To edit your Friendship Connect Profile information, <a href="/user">click here to go to your profile page.</a>'));
                        }
                    }
                }
                public function getShortcodeContent(): string
                {
                    $this->log("@@@ userFC Reg shortcode");
                    return HtmlUtils::getSailTemplate('fc-registration.php');
                }
            },

            /**
             * userFCSearch shortcode
             */
            new class extends PreprocessingSailShortcode
            {
                public function getName(): string
                {
                    return 'userFCSearch';
                }
                public function preprocessingCallback()
                {
                    $fcMember = FriendshipConnectDao::getInstance()->getFcProfile();
                    // TODO: figure out how to make preprocess independent of knowing the page slug/id
                    if (is_page('friendship-connect-search')) {
                        if (!is_user_logged_in()) {
                            WebUtils::redirect('/login');
                        } else if (isset($fcMember) && !$fcMember->referenceApproved) {
                            return HtmlUtils::getSailPage('fc-pending-approval.html');
                        } else if ($fcMember == null) {
                            WebUtils::redirect("/join-friendship-connect");
                        }
                    }
                }
                public function getShortcodeContent(): string
                {
                    return HtmlUtils::getSailTemplate('fc-result-summary.php');
                }
            },
        ];
    }
}
