<?php

/**
 * Plugin Name: SAIL User Management
 * Plugin URI: https://github.com/tmuelle2/sail-user-management
 * Description: SAIL website user management plugin
 * Version: 0.1
 */

// Load dependencies of ClassAutoloader and ClassAutoloader itself
require_once __DIR__ . '/Constants.php';
require_once __DIR__ . '/caching/DatabaseCache.php';
require_once __DIR__ . '/utils/Logger.php';
require_once __DIR__ . '/utils/Singleton.php';
require_once __DIR__ . '/utils/Stopwatch.php';
require_once __DIR__ . '/utils/PhpUtils.php';
require_once __DIR__ . '/utils/ClassAutoloader.php';

use Sail\Utils\ClassAutoloader;

spl_autoload_register(array(ClassAutoloader::getInstance(), 'autoload'));

//ClassAutoloader::getInstance()->updateCachedClasspath();

use Sail\Api\NewsletterSubscribeApi;
use Sail\Api\NewsletterUnsubscribeApi;
use Sail\Api\PayDuesApi;
use Sail\Constants;
use Sail\Data\Dao\UserDao;
use Sail\Form\Handlers\AddFamilyMemberHandler;
use Sail\Form\Handlers\ChangePasswordHandler;
use Sail\Form\Handlers\ForgotPasswordHandler;
use Sail\Form\Handlers\FriendshipConnectProfileRegistrationHandler;
use Sail\Form\Handlers\FriendshipConnectProfileUpdateHandler;
use Sail\Form\Handlers\UserLoginHandler;
use Sail\Form\Handlers\UserLogoutHandler;
use Sail\Form\Handlers\UserProfileUpdateHandler;
use Sail\Form\Handlers\UserRegistrationHandler;
use Sail\Form\Handlers\VerifyEmailApi;
use Sail\Shortcodes\FriendshipConnectShortCodes;
use Sail\Shortcodes\MessageShortCodes;
use Sail\Shortcodes\UserShortCodes;
use Sail\Utils\EmailSender;
use Sail\Utils\WebUtils;

/**
 * Runs on set current user hook, runs on every request.
 * Hook in early to ensure permission definition runs before Media Vault.
 */
add_action('set_current_user', 'sailPluginPreInit');
function sailPluginPreInit()
{
    // Restrict Media Vault files to paid members
    if (function_exists('mgjp_mv_add_permission')) {
        mgjp_mv_add_permission('paid-subscribers', array(
            'description' => 'Restricts files to paid members.',
            'select' => 'Paid Members',
            'logged_in' => true, // whether the user must be logged in
            'run_in_admin' => false, // whether to run the access check in admin
            'cb' => 'restrictMediaVaultToPaidMembers',
        ));
    }
}

function restrictMediaVaultToPaidMembers()
{
    if (is_user_logged_in()) {
        $sailUser = UserDao::getInstance()->getSailUser()();
        if ($sailUser->isDuePayingUser()) {
            return true;
        }
    }
    return false;
}

/**
 * Runs on plugin init hook, runs on every request.
 */
add_action('init', 'sailPluginInit');
function sailPluginInit()
{
    UserShortCodes::getInstance()->registerShortcodes();
    FriendshipConnectShortcodes::getInstance()->registerShortcodes();
    MessageShortCodes::getInstance()->registerShortcodes();
}

add_action('wp_enqueue_scripts', 'sailPluginEnqueueScripts');
function sailPluginEnqueueScripts()
{
    wp_enqueue_style('sailPluginStyle', Constants::CSS_ROUTE . 'common.css');
    wp_enqueue_script('sailPluginJs', Constants::JS_ROUTE . 'common.js');
}

/**
 * Runs on plugin activation, only run once when the plugin activates.
 */
register_activation_hook(__file__, 'sailPluginActivate');
function sailPluginActivate()
{
    ClassAutoloader::getInstance()->updateCachedClasspath();
}

/**
 * Initialize rest apis.
 */
add_action('rest_api_init', 'registerApis');
function registerApis()
{
    // General APIs
    NewsletterSubscribeApi::getInstance()->registerApi();
    NewsletterUnsubscribeApi::getInstance()->registerApi();
    PayDuesApi::getInstance()->registerApi();
    VerifyEmailApi::getInstance()->registerApi();

    // Form submission APIs
    AddFamilyMemberHandler::getInstance()->registerApi();
    ChangePasswordHandler::getInstance()->registerApi();
    ForgotPasswordHandler::getInstance()->registerApi();
    FriendshipConnectProfileRegistrationHandler::getInstance()->registerApi();
    FriendshipConnectProfileUpdateHandler::getInstance()->registerApi();
    UserLoginHandler::getInstance()->registerApi();
    UserLogoutHandler::getInstance()->registerApi();
    UserProfileUpdateHandler::getInstance()->registerApi();
    UserRegistrationHandler::getInstance()->registerApi();
}

// TODO refactor
function sail_user_reverify_email()
{
    global $wpdb;
    // send verification email
    $user_arr = UserDao::getInstance()->getSailUser();
    $email_verification_key = EmailSender::sendVerificationEmail($user_arr);
    $user_arr['emailverificationkey'] = $email_verification_key;
    $user_arr['emailverified'] = false;
    $wpdb->update('sail_users', $user_arr, array('userid' => $user_arr['userid']), $user_db_fields);
    webutils::redirect('/success-message?title=verification email sent&message=%3ca%20href%3d%22https%3a%2f%2fsailhousingsolutions.org%2fuser%22%3eclick%20here%20to%20go%20to%20your%20profile%20page.%3c%2fa%3e');
    exit;
}
add_action('admin_post_sail_user_reverify_email', 'sail_user_reverify_email');

// TODO refactor
function sail_user_link_family_member()
{
    include_once Constants::HOME_DIR . 'link-family-member.php';
}
// this runs on every request! cannot error out unnecessarily!!
add_action('wp', 'sail_user_link_family_member');
