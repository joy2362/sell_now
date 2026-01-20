<?php

namespace SellNow\Services;

use SellNow\Payments\PaymentManager;

class CheckoutService
{
    public function __construct(private PaymentManager $paymentManager) {}

    public function cartTotal(array $cart): float
    {
        return array_reduce($cart, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function providers(): array
    {
        return $this->paymentManager->availableProviders();
    }

    public function pay(string $provider, float $total, array $meta = []): bool
    {
        $driver = $this->paymentManager->get($provider);
        return $driver->charge($total, $meta);
    }
}
