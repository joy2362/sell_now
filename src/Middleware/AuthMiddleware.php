<?php

namespace SellNow\Middleware;

use SellNow\Interface\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(): void
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
    }
}
