<?php

use SellNow\Middleware\AuthMiddleware;
use SellNow\Middleware\GuestMiddleware;

return [
    'auth' => AuthMiddleware::class,
    'guest' => GuestMiddleware::class,
];
