<?php

namespace SellNow\Drivers;

use SellNow\Interface\PaymentInterface;

class StripeDriver implements PaymentInterface
{
    public function name(): string
    {
        return 'Stripe';
    }

    public function charge(float $amount, array $meta = []): bool
    {
        return true;
    }
}
