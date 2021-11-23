<?php

use MailchimpMarketing\ApiClient;
use MailchimpMarketing\ApiException;
use GuzzleHttp\Exception\ClientException;

class MailChimpSailNewsletterClient {

    private $client;
    private $listId;     

    public function __construct()  {
        $this->listId = getenv('MAIL_CHIP_LIST_ID') ?: 'MAIL_CHIP_LIST_ID';
        $this->client = new ApiClient();
        $this->client->setConfig([
            'apiKey' => getenv('MAIL_CHIP_API_KEY') ?: 'MAIL_CHIP_API_KEY',
            'server' => 'us1'
        ]);
    }

    public function subscribe($email) {
        return $this->update_list($email, 'subscribed');
    }

    public function unsubscribe($email) {
        return $this->update_list($email, 'unsubscribed');
    }

    public function status($email) {
        try {
            $response = $this->client->lists->getListMember($this->listId, md5(strtolower($email)));
            return $response->status;
        } catch (ApiException | ClientException $e) {
            error_log($e->getMessage());
            error_log(print_r(($e->getResponse())->getBody()->getContents(), true));
            return 'error';
        }
    }

    private function update_list($email, $status) {
        try {
            error_log('Attempting to update ' . $email . ' newsletter subscription to ' . $status);
            $response = $this->client->lists->setListMember($this->listId, md5(strtolower($email)), [
                'email_address' => $email,
                'status_if_new' => $status,
                'status' => $status,
            ]);
            error_log(print_r($response, true));
            return $response;
        } catch (ApiException | ClientException $e) {
            error_log($e->getMessage());
            error_log(print_r(($e->getResponse())->getBody()->getContents(), true));
        }
    }
}