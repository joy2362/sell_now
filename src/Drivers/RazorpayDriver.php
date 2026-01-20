<?php

namespace SellNow\Drivers;

use SellNow\Interface\PaymentInterface;

class RazorpayDriver implements PaymentInterface
{
    public function name(): string
    {
        return 'Razorpay';
    }

    public function charge(float $amount, array $meta = []): bool
    {
        return true;
    }
}
