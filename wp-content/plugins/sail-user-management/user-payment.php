<?php

use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    /**
     * Set up and return PayPal PHP SDK environment with PayPal access credentials.
     * This sample uses SandboxEnvironment. In production, use LiveEnvironment.
     */
    public static function environment()
    {
        // Set in the .htaccess with BluHost
        $clientId = getenv('PAYPAL_CLIENT_ID') ?: 'PAYPAL-SANDBOX-CLIENT-ID';
        $clientSecret = getenv('PAYPAL_CLIENT_SECRET') ?: 'PAYPAL-SANDBOX-CLIENT-SECRET';
        return new ProductionEnvironment($clientId, $clientSecret);
    }
}

class PayPalOrder
{
  /**
   *You can use this function to retrieve an order by passing order ID as an argument.
   */
  public static function getOrder($orderId)
  {
    // Call PayPal to get the transaction details
    $client = PayPalClient::client();
    return $client->execute(new OrdersGetRequest($orderId));
  }

  public static function recordOrder($orderId) {
    $response = self::getOrder($orderId);

    global $USER_DB_FIELDS;
    global $wpdb;

    $orderRecord = array('orderId' => $orderId, 'orderJson' => json_encode($response));
    $wpdb->insert('sail_payments', $orderRecord, array('orderId' => '%s', 'orderJson' => '%s'));
    if ($response->result->status == 'COMPLETED') {
        $cur_user_array = get_sail_user_array();
        $cur_user_array['isPaidMember'] = 1;
        $cur_user_array['lastDuePaymentDate'] = date('Y-m-d');

        $wpdb->update('sail_users', $cur_user_array, array('userId' => $cur_user_array['userId']), $USER_DB_FIELDS);

        // Check if their email is verified and send welcome email if it is
        if ($cur_user_array['emailVerified']) {
            $headers = array('Content-Type: text/html; charset=UTF-8');
            ob_start();
            include('/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/emails/welcome-email.html');
            $body = ob_get_contents();
            ob_end_clean();
            wp_mail($cur_user_array['email'], "Welcome to SAIL!", $body, $headers);
        }
    }
  }
}

?>