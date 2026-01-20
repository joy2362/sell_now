<?php

namespace SellNow\Payments;

use Exception;
use SellNow\Interface\PaymentInterface;

class PaymentManager
{
    public function __construct(private array $drivers) {}

    public function get(string $provider): PaymentInterface
    {
        foreach ($this->drivers as $driver) {
            if (strcasecmp($driver->name(), $provider) === 0) {
                return $driver;
            }
        }

        throw new Exception("Payment provider [$provider] not supported");
    }

    public function availableProviders(): array
    {
        return array_map(fn($d) => $d->name(), $this->drivers);
    }
}
