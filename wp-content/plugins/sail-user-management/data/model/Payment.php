<?php

namespace Sail\Data\Model;

class Payment extends SailDataObject
{
    public const PAYMENT_DB_FIELDS = array('orderId' => '%s', 'orderJson' => '%s');

    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public static function fieldKeys(): array
    {
        return self::PAYMENT_DB_FIELDS;
    }
}
