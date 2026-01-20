<?php

namespace SellNow\Interface;

interface PaymentInterface
{
    public function name(): string;

    public function charge(float $amount, array $meta = []): bool;
}
