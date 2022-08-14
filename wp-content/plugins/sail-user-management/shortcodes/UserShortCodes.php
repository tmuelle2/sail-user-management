<?php

namespace Sail\Shortcodes;

use Sail\Caching\InMemoryCache;
use Sail\Clients\MailChimpSailNewsletterClient;
use Sail\Data\Dao\UserDao;
use Sail\Utils\HtmlUtils;
use Sail\Utils\Logger;
use Sail\Utils\Singleton;
use Sail\Utils\WebUtils;

final class UserShortCodes extends ShortCodeRegistrator
{
    use Logger;
    use Singleton;

    private const CACHE_KEY = "userShortCodes";

    public function getShortcodes(): array
    {
        // TODO anonymous classes are not serializable, find a way to cache
        return [
            /**
             * userAddFamilyMember shortcode
             */
            new class extends PreprocessingSailShortcode
            {
                public function getName(): string
                {
                    return 'userAddFamilyMember';
                }
                public function preprocessingCallback()
                {
                    // TODO: figure out how to make preprocess independent of knowing the page slug/id
                    if (is_page('user') && !is_user_logged_in()) {
                        WebUtils::redirect('/login');
                    }
                }
                public function getShortcodeContent(): string
                {
                    return HtmlUtils::getSailTemplate('add-family-member.php');
                }
            },

            /**
             * userChangePassword shortcode
             */
            new class extends PreprocessingSailShortcode
            {
                public function getName(): string
                {
                    return 'userChangePassword';
                }
                public function preprocessingCallback()
                {
                    // TODO: figure out how to make preprocess independent of knowing the page slug/id
                    if (is_page('user') && !is_user_logged_in()) {
                        WebUtils::redirect('/login');
                    }
                }
                public function getShortcodeContent(): string
                {
                    return HtmlUtils::getSailTemplate('change-password.php');
                }
            },

            /**
             * userEmailVerification shortcode
             */
            new class extends PreprocessingSailShortcode
            {
                public function getName(): string
                {
                    return 'userEmailVerification';
                }
                public function preprocessingCallback()
                {
                    // TODO: figure out how to make preprocess independent of knowing the page slug/id
                    if (is_page('user') && !is_user_logged_in()) {
                        WebUtils::redirect('/login');
                    }
                }
                public function getShortcodeContent(): string
                {
                    return HtmlUtils::getSailTemplate('verify-email.php');
                }
            },

            /**
             * userForgotPassword shortcode
             */
            new class extends SailShortcode
            {
                public function getName(): string
                {
                    return 'userForgotPassword';
                }
                public function getShortcodeContent(): string
                {
                    return HtmlUtils::getSailTemplate('forgot-password.php');
                }
            },

            /**
             * userLogout shortcode
             */
            new class extends PreprocessingSailShortcode
            {
                public function getName(): string
                {
                    return 'userLogout';
                }
                public function preprocessingCallback()
                {
                    // TODO: figure out how to make preprocess independent of knowing the page slug/id
                    if (is_page('logout') && !is_user_logged_in()) {
                        WebUtils::redirect('/');
                    }
                }
                public function getShortcodeContent(): string
                {
                    return HtmlUtils::getSailTemplate('logout.php');
                }
            },

            /**
             * userPostRegistration shortcode
             */
            new class extends SailShortcode
            {
                public function getName(): string
                {
                    return 'userPostRegistration';
                }
                public function getShortcodeContent(): string
                {
                    return HtmlUtils::getSailTemplate('membership-upgrade.php', ['isNewMember' => true]);
                }
            },

            /**
             * userProfile shortcode
             */
            new class extends PreprocessingSailShortcode
            {
                public function getName(): string
                {
                    return 'userProfile';
                }
                public function preprocessingCallback()
                {
                    // TODO: figure out how to make preprocess independent of knowing the page slug/id
                    if (is_page('user') && !is_user_logged_in()) {
                        WebUtils::redirect('/login');
                    }
                }
                public function getShortcodeContent(): string
                {
                    // Docs say nested output buffers are chill
                    ob_start();
                    echo HtmlUtils::getSailTemplate('profile.php');
                    echo HtmlUtils::getSailTemplate('membership-upgrade.php', ['isNewMember' => false]);
                    $sailUser = UserDao::getInstance()->getSailUser();
                    if (!$sailUser->emailVerified) {
                        echo HtmlUtils::getSailTemplate('verify-email.php');
                    }
                    return ob_get_clean();
                }
            },

            /**
             * userRegistration shortcode
             */
            new class extends PreprocessingSailShortcode
            {
                public function getName(): string
                {
                    return 'userRegistration';
                }
                public function preprocessingCallback()
                {
                    // TODO: figure out how to make preprocess independent of knowing the page slug/id
                    if (is_page('register') && is_user_logged_in()) {
                        WebUtils::redirect('/error-message?title=You need to log out before attempting to register for an account.&message= ');
                    }
                }
                public function getShortcodeContent(): string
                {
                    return HtmlUtils::getSailTemplate('registration.php');
                }
            },

            /**
             * userSignOn shortcode
             */
            new class extends PreprocessingSailShortcode
            {
                public function getName(): string
                {
                    return 'userSignOn';
                }
                public function preprocessingCallback()
                {
                    // TODO: figure out how to make preprocess independent of knowing the page slug/id
                    if (is_page('login') && is_user_logged_in()) {
                        WebUtils::redirect('/user');
                    }
                }
                public function getShortcodeContent(): string
                {
                    return HtmlUtils::getSailTemplate('login.php');
                }
            },

            /**
             * userSubscribeNewsletter shortcode
             */
            new class extends SailShortcode
            {
                public function getName(): string
                {
                    return 'userSubscribeNewsletter';
                }
                public function getShortcodeContent(): string
                {
                    $isSub = false;
                    if (is_user_logged_in()) {
                        $subStatus = MailChimpSailNewsletterClient::getInstance()->status(UserDao::getInstance()->getSailUser()->email);
                        $isSub = $subStatus == 'subscribed' || $subStatus == 'pending';
                    }
                    return HtmlUtils::getSailTemplate('newsletter-subscribe-button.php', ['isSubscribed' => $isSub]);
                }
            },

            /**
             * userUpdateProfile shortcode
             */
            new class extends PreprocessingSailShortcode
            {
                public function getName(): string
                {
                    return 'userUpdateProfile';
                }
                public function preprocessingCallback()
                {
                    // TODO: figure out how to make preprocess independent of knowing the page slug/id
                     if (is_page('user') && !is_user_logged_in()) {
                         WebUtils::redirect('/login');
                     }
                }
                public function getShortcodeContent(): string
                {
                    $sailUser = UserDao::getInstance()->getSailUser();
                    return HtmlUtils::getSailTemplate('update-profile.php', ['sailUser' => $sailUser]);
                }
            }
        ];
    }
}
