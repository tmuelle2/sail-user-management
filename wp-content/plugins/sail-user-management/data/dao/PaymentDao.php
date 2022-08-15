<?php

namespace Sail\Data\Dao;

use Sail\Data\Model\Payment;
use Sail\Utils\Singleton;

class PaymentDao
{
    use Singleton;

    public function recordPayment(Payment $payment): void
    {
        global $wpdb;
        $wpdb->insert('sail_payments', $payment->getDatabaseData());
    }
}
