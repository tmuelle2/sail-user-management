<?php

ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// In lieu of composer, load the PayPal library manually
/*
$HOME_DIR = '/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/';
include 'paypalhttp_php-1.0.0/lib/PayPalHttp/Serializer.php';
print 'Including from directory: ' . $HOME_DIR;
foreach (glob($HOME_DIR . 'paypalhttp_php-1.0.0/lib/PayPalHttp/Serializer/*.php') as $filename) {
    print 'Including file: ' . $filename;
    include $filename;
}
foreach (glob($HOME_DIR . 'paypalhttp_php-1.0.0/lib/PayPalHttp/*.php') as $filename) {
    print 'Including file: ' . $filename;
    include $filename;
}
foreach (glob($HOME_DIR . 'Checkout-PHP-SDK-1.0.1/lib/PayPalCheckoutSdk/* /*.php') as $filename) {
    print 'Including file: ' . $filename;
    include $filename;
}
*/

// In lieu of composer, load the PayPal libraries with autoload
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
spl_autoload_register(function ($class_name) {
    global $PAYPAL_LIB_CLASS_MAP;
    include $PAYPAL_LIB_CLASS_MAP[$class_name];
});

use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
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


class CaptureOrder
{
  // 2. Set up your server to receive a call from the client
  /**
   *This function can be used to capture an order payment by passing the approved
   *order ID as argument.
   *
   *@param orderId
   *@param debug
   *@returns
   */
  public static function capture($orderId, $debug=false)
  {
    $request = new OrdersCaptureRequest($orderId);

    // 3. Call PayPal to capture an authorization
    $client = PayPalClient::client();
    $response = $client->execute($request);
    // 4. Save the capture ID to your database. Implement logic to save capture to your database for future reference.
    if ($debug)
    {
      print 'Status Code: {$response->statusCode}\n';
      print 'Status: {$response->result->status}\n';
      print 'Order ID: {$response->result->id}\n';
      print 'Links:\n';
      foreach($response->result->links as $link)
      {
        print '\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n';
      }

      print 'Capture Ids:\n';
      foreach($response->result->purchase_units as $purchase_unit)
      {
        foreach($purchase_unit->payments->captures as $capture)
        {   
          print '\t{$capture->id}';
        }
      }
      // To print the whole response body, uncomment the following line
      echo json_encode($response->result, JSON_PRETTY_PRINT);
    }

    return $response;
  }
}

?>