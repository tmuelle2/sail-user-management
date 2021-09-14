<?php

// In lieu of composer, load the PayPal libraries with autoload
// TODO: This is hella inefficient, this should be moved to a static class or some other mechanism to load the file paths once and only once
spl_autoload_register(function ($class_name) {
    $HOME_DIR = '/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/';
    $PAYPAL_LIB_PATHS = glob($HOME_DIR . 'paypalhttp_php-1.0.0/lib/PayPalHttp/Serializer/*.php');
    $PAYPAL_LIB_PATHS = array_merge($PAYPAL_LIB_PATHS, glob($HOME_DIR . 'paypalhttp_php-1.0.0/lib/PayPalHttp/*.php'));
    $PAYPAL_LIB_PATHS = array_merge($PAYPAL_LIB_PATHS, glob($HOME_DIR . 'Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/*/*.php'));
    $PAYPAL_LIB_CLASS_MAP = array();
    foreach ($PAYPAL_LIB_PATHS as $path) {
        $split = explode('/', $path);
        $justFileName = basename(end($split), '.php');
        $PAYPAL_LIB_CLASS_MAP[$justFileName] = $path;
    }
    $split = explode('\\', $class_name);
    $justClassName = end($split);
    if (!empty($justClassName) && isset($PAYPAL_LIB_CLASS_MAP[$justClassName])) {
        include $PAYPAL_LIB_CLASS_MAP[$justClassName];
    }
});

use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

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
        return new SandboxEnvironment($clientId, $clientSecret);
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

    // TODO: Record order transaction in DB after table is created
    // $orderRecord = array('orderId' => $orderId, 'orderJson' => json_encode($response));
    // $wpbd->insert(`sail_payments`, $orderRecord, array('orderId' => '%s', 'orderJson' => '%s'));
    if ($response->result->status == 'COMPLETED') {
        $cur_user_array = get_sail_user_array();
        $cur_user_array['isPaidMember'] = 1;
        $cur_user_array['lasDuePaymentDate'] = date('Y-m-d');

        $wpdb->update('sail_users', $cur_user_array, array('userId' => $cur_user_array['userId']), $USER_DB_FIELDS);
    }
  }
}

?>