<?php

namespace Sail\Utils;

use WP_User;

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;

use Sail\Data\Model\User;
use Sail\Constants;

class EmailSender
{
    use Singleton;
    use Logger;

    private Client $client;
    private Gmail $gmail;
    private string $fromEmail = 'info@sailhousingsolutions.org';
    private string $api_key;

    private function __construct() {
        $this->api_key = getenv('GMAIL_API_KEY') ?: 'GMAIL_API_KEY';
        // TODO this is horrible, read the key from a pem file like a normal person
        $this->api_key = str_replace("\\n", "\n", $this->api_key);
        $this->client = new Client([
            'application_name' => 'SAIL Housing Solutions Website',
            'scopes' => [Gmail::GMAIL_SEND],
            //'developer_key' => getenv('GMAIL_API_KEY') ?: 'GMAIL_API_KEY',
            //'client_id' => getenv('GMAIL_API_CLIENT_ID') ?: 'GMAIL_API_KEY',
            //'client_secret' => getenv('GMAIL_API_CLIENT_SECRET') ?: 'GMAIL_API_CLIENT_SECRET'
            'subject' => $this->fromEmail,
            'credentials' => [
                "type" => "service_account",
                "project_id" => getenv('GMAIL_API_PROJECT_ID') ?: 'GMAIL_API_PROJECT_ID',
                "private_key_id" => getenv('GMAIL_API_KEY_ID') ?: 'GMAIL_API_KEY_ID',
                "private_key" => $this->api_key,
                "client_email" => getenv('GMAIL_API_EMAIL') ?: 'GMAIL_API_EMAIL',
                "client_id" => getenv('GMAIL_API_CLIENT_ID') ?: 'GMAIL_API_CLIENT_ID',
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/sail-housing-solutions-website%40august-now-370901.iam.gserviceaccount.com"
            ]
        ]);
        $this->gmail = new Gmail($this->client);
    }

    private function sendEmail(string $toEmail, string $subject, string $message, string $mimeType = 'text/plain') {
        if (PhpUtils::isLocalhost()) {
            $this->log('Running on local host, skipping email send');
            return 'Dummy Email Return Value';
        }
        $this->log($this->api_key);
        //return wp_mail($toEmail, $subject, $message);
        $rawMessage = "From: <{$this->fromEmail}> \r\n";
        $rawMessage .= "To: <{$toEmail}>\r\n";
        $rawMessage .= "Subject: {$subject}\r\n";
        $rawMessage .= "Content-Type: {$mimeType}; charset=utf-8\r\n";
        $rawMessage .= $message;
        return $this->gmail->users_messages->send('me', new Message(['raw' => base64_encode($rawMessage)]));
        /*return $this->gmail->users_messages->send('me', new Message([
            'payload' => new MessagePart([
                'mimeType' => $mimeType,
                'headers' => [
                    new MessagePartHeader([
                        'name' => 'to',
                        'value' => $toEmail,
                    ]),
                    new MessagePartHeader([
                        'name' => 'subject',
                        'value' => $subject,
                    ])
                ],
                'body' => new MessagePartBody([
                    'data' => base64_encode($message)
                ])
            ])
        ]));*/
    }

    public function sendAccountLinkingEmail(User $sailUser, string $linkTargetEmail): string
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

        $this->sendEmail($linkTargetEmail, "SAIL Family Account Link Request", $message);

        return $familyLinkingKey;
    }

    public function sendForgotPasswordEmail(WP_User $wpUser): string
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

        $this->sendEmail($userLogin, "SAIL Password Reset Link", $message);

        return $resetKey;
    }

    public function sendVerificationEmail(User $sailUser): string
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

        $this->sendEmail($email, "SAIL Email Verification", $message);

        return $emailVerificaitonKey;
    }

    public function sendFcProfileCreatedEmail(): string
    {
        $message = "Hello, \r\n\r\n";
        $message .= "A new Friendship Connect Profile was created and is awaiting a reference approval.\r\n\r\n";
        $message .= "If you are a Wordpress Admin, please review the SAIL reference of the new FC Profile by going to the DATABASE ACCESS panel on the admin page.\r\n\r\n";
        $message .= "If you are not a Wordpess Admin, please ignore this email.";

        return (string) $this->sendEmail($this->fromEmail, "New Friendship Connect Profile Created", $message);
    }

    public function sendWelcomeEmail(string $email): string 
    {
        ob_start();
        include('/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/emails/welcome-email.html');
        $body = ob_get_contents();
        ob_end_clean();
        return (string) $this->sendEmail($email, "Welcome to SAIL!", $body, 'text/html');
    }
}
