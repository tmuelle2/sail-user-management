<?php

namespace Sail\Utils;

use WP_User;

use Sail\Data\Model\User;
use Sail\Constants;

final class EmailSender
{
    public final static function sendAccountLinkingEmail(User $sailUser, string $linkTargetEmail): string
    {
        $siteUrl = WebUtils::getUrl();
        $familyLinkingKey = uniqid('family-linking-key-', true);
        $url = esc_url_raw("$siteUrl/link-family-member" . "?family_linking_key=$familyLinkingKey&email=$sailUser->email");

        $message = "Hello!\r\n\r\n";
        $message .= "A SAIL user with the email ";
        $message .= $sailUser->email;
        $message .= " is requesting that your accounts are linked together as part of a family to share the benefits of a SAIL membership. ";
        $message .= "To finish the linking process use the link below. Before clicking the link, please make sure you are logged in using the account associated with this email: ";
        $message .= $linkTargetEmail;
        $message .= "\r\n\r\n";
        $message .= $url;
        $message .= "\r\n\r\nIf you do not have an account with SAIL, please ignore this email.";

        wp_mail($linkTargetEmail, "SAIL Family Account Link Request", $message);

        return $familyLinkingKey;
    }

    public final static function sendForgotPasswordEmail(WP_User $wpUser): string
    {
        $siteUrl = WebUtils::getUrl();
        $resetKey = get_password_reset_key($wpUser);
        $userLogin = $wpUser->user_login;
        $url = esc_url_raw("$siteUrl/change-password" . "?pw_reset_key=$resetKey&user_email=$userLogin");

        $message = "Hello ";
        $message .= $userLogin;
        $message .= "!\r\n\r\n";
        $message .= "Someone has requested a link to reset your password, and you can do this through the link below:\r\n\r\n";
        $message .= $url;
        $message .= "\r\n\r\nIf you didn't request this, please ignore this email.";
        $message .= "\r\n\r\nYour password won't change until you access the link above and create a new one.";

        wp_mail($userLogin, "SAIL Password Reset Link", $message);

        return $resetKey;
    }

    public final static function sendVerificationEmail(User $sailUser): string
    {
        // Send verification email
        $siteUrl = WebUtils::getUrl();
        $email = $sailUser['email'];
        $emailVerificaitonKey = uniqid('sail-email-verification-', true);
        $url = esc_url_raw("$siteUrl" . Constants::API_PREFIX  . "membership/v1/verify-email&verification_key=$emailVerificaitonKey&email=$email");

        $message = "Hello ";
        $message .= $sailUser['firstName'];
        $message .= "!\r\n\r\n";
        $message .= "Thanks for joining SAIL! In order to ensure that your email is configured correctly, please verify it by clicking this link:\r\n\r\n";
        $message .= $url;
        $message .= "\r\n\r\nIf you didn't sign-up for SAIL, please ignore this email.";

        wp_mail($email, "SAIL Email Verification", $message);

        return $emailVerificaitonKey;
    }

    public final static function sendFcProfileCreatedEmail(): string
    {
        $email = "info@sailhousingsolutions.org";

        $message = "Hello, \r\n\r\n";
        $message .= "A new Friendship Connect Profile was created and is awaiting a reference approval.\r\n\r\n";
        $message .= "If you are a Wordpress Admin, please review the SAIL reference of the new FC Profile by going to the DATABASE ACCESS panel on the admin page.\r\n\r\n";
        $message .= "If you are not a Wordpess Admin, please ignore this email.";

        wp_mail($email, "New Friendship Connect Profile Created", $message);

        return "sent?";
    }
}
