<?php

include '/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/ClassAutoloader.php';
spl_autoload_register('ClassAutoloader::autoload');

class MailChimpSailNewsletterClient {
    use MailchimpMarketing\ApiClient;
    use MailchimpMarketing\ApiException;

    private $client;
    private static $listId = '1304042';
    
    public function __construct()  {
        $client = new ApiClient();
        $mailchimp->setConfig([
            'apiKey' => getenv('MAIL_CHIP_API_KEY') ?: 'MAIL_CHIP_API_KEY',
            'server' => 'us1'
        ]);
    }

    public function subscribe($email) {
        return update_list($email, 'subscribed');
    }

    public function unsubscribe($email) {
        return update_list($email, 'unsubscribed');
    }

    public function status($email) {
        try {
            $response = $client->lists->getListMember($listId, md5(strtolower($email)));
            return $response->status;
        } catch (ApiException $e) {
            error_log(print_r($e, true));
            return 'error';
        }
    }

    private function update_list($email, $status) {
        try {
            error_log('Attempting to update ' . $email . ' newsletter subscription to ' . $status);
            $response = $client->lists->updateListMember($listId, md5(strtolower($email)), ['status' => $status]);
            error_log(print_r($response, true));
            return $response;
        } catch (ApiException $e) {
            error_log(print_r($e, true));
        }
    }
}