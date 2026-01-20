<?php

use SellNow\Interface\DatabaseInterface;
use SellNow\Drivers\{
    StripeDriver,
    PaypalDriver,
    RazorpayDriver,
    SQLiteDriver,
    MySQLDriver
};
use Twig\Loader\FilesystemLoader;
use SellNow\Payments\PaymentManager;
use SellNow\Services\CheckoutService;
use Twig\Environment;

return [
    PaymentManager::class => DI\create()->constructor([
        DI\get(StripeDriver::class),
        DI\get(PaypalDriver::class),
        DI\get(RazorpayDriver::class),
    ]),

    CheckoutService::class => DI\autowire(),

    DatabaseInterface::class => DI\factory(function () use ($dbConfig) {
        $default = $dbConfig['default'];
        $connection = $dbConfig['connections'][$default];

        return match ($default) {
            'mysql'  => new MySQLDriver($connection),
            'sqlite' => new SQLiteDriver($connection),
            default  => throw new RuntimeException('Invalid DB driver'),
        };
    }),

    Environment::class => DI\factory(function () {
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        $twig = new Environment($loader, ['debug' => true]);
        $twig->addGlobal('session', $_SESSION);
        return $twig;
    }),
];
