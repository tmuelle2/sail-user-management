<?php

namespace Sail\Clients;

use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalHttp\HttpResponse;
use Sail\Data\Dao\PaymentDao;
use Sail\Data\Dao\UserDao;
use Sail\Data\Model\Payment;

class PayPalOrder
{
    /**
     *You can use this function to retrieve an order by passing order ID as an argument.
     */
    public static function getOrder(string $orderId): HttpResponse
    {
        // Call PayPal to get the transaction details
        $client = PayPalClientFactory::client();
        return $client->execute(new OrdersGetRequest($orderId));
    }

    /**
     * Stores payment record and updates user last payment date.
     */
    public static function recordOrder(string $orderId): void
    {
        $response = self::getOrder($orderId);

        $payment = new Payment(array('orderId' => $orderId, 'orderJson' => json_encode($response)));
        PaymentDao::getInstance()->recordPayment($payment);
        if ($response['result']['status'] == 'COMPLETED') {
            $user = UserDao::getInstance()->getSailUser();
            UserDao::getInstance()->updateUser($user, array('isPaidMember' => 1, 'lastDuePaymentDate' => date('Y-m-d')));
        }
    }
}
