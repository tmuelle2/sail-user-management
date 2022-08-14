<?php

namespace Sail\Clients;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use Sail\Utils\Singleton;

class PayPalClientFactory
{
    use Singleton;

    /**
     * Creates an instance of PayPalHttpClient.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    private static function environment()
    {
        // Set in the .htaccess with BluHost
        $clientId = getenv('PAYPAL_CLIENT_ID') ?: 'PAYPAL-SANDBOX-CLIENT-ID';
        $clientSecret = getenv('PAYPAL_CLIENT_SECRET') ?: 'PAYPAL-SANDBOX-CLIENT-SECRET';
        return new ProductionEnvironment($clientId, $clientSecret);
    }
}
