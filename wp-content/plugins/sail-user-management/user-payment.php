<?php

ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

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

use PayPalHttp\HttpRequest;
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

class TransactionGetRequest extends HttpRequest
{
    function __construct($transactionId)
    {
        parent::__construct("/v1/reporting/transactions?transaction_id={transaction_id}&fields=transaction_info,payer_info&start_date=1970-01-01T00:00:00-0700&end_date=9000-01-01T00:00:00-0700", "GET");

        $this->path = str_replace("{transaction_id}", urlencode($transactionId), $this->path);
        $this->headers["Content-Type"] = "application/json";
    }
}

class PayPalPayment
{
  // 2. Set up your server to receive a call from the client
  /**
   *You can use this function to retrieve an order by passing order ID as an argument.
   */
  public static function getOrder($orderId)
  {
    // 3. Call PayPal to get the transaction details
    $client = PayPalClient::client();
    $response = $client->execute(new TransactionGetRequest($orderId));

    /**
     *Enable the following line to print complete response as JSON.
     */
    print json_encode($response->result);
    print "Status Code: {$response->statusCode}\n";
    print "Status: {$response->result->status}\n";
    print "Order ID: {$response->result->id}\n";
    print "Intent: {$response->result->intent}\n";
    print "Links:\n";

    foreach($response->result->links as $link)
    {
      print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
    }

    // 4. Save the transaction in your database. Implement logic to save transaction to your database for future reference.
    print "Gross Amount: {$response->result->purchase_units[0]->amount->currency_code} {$response->result->purchase_units[0]->amount->value}\n";

    // To print the whole response body, uncomment the following line
    echo json_encode($response->result, JSON_PRETTY_PRINT);
  }

}

?>