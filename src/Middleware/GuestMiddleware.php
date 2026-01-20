<?php

namespace SellNow\Middleware;

use SellNow\Interface\MiddlewareInterface;

class GuestMiddleware implements MiddlewareInterface
{
    public function handle(): void
    {
        if (isset($_SESSION['user_id'])) {
            redirect('/dashboard');
        }
    }
}
