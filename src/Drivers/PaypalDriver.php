<?php

namespace SellNow\Drivers;

use SellNow\Interface\PaymentInterface;

class PaypalDriver implements PaymentInterface
{
    public function name(): string
    {
        return 'PayPal';
    }

    public function charge(float $amount, array $meta = []): bool
    {
        return true;
    }
}
