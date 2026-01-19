<?php

use SellNow\Controllers\{
    AuthController,
    CartController,
    CheckoutController,
    HomeController,
    ProductController,
    PublicController
};

return [
    '/' => ['controller' => HomeController::class, 'method' => 'index'],

    '/login' => ['controller' => AuthController::class, 'method' => 'loginForm', 'postMethod' => 'login'],
    '/register' => ['controller' => AuthController::class, 'method' => 'registerForm', 'postMethod' => 'register'],
    '/dashboard' => ['controller' => AuthController::class, 'method' => 'dashboard'],
    '/logout' => ['controller' => AuthController::class, 'method' => 'logout'],

    '/products/add' => ['controller' => ProductController::class, 'method' => 'create', 'postMethod' => 'store'],

    '/cart' => ['controller' => CartController::class, 'method' => 'index'],
    '/cart/add' => ['controller' => CartController::class, 'method' => 'add'],
    '/cart/clear' => ['controller' => CartController::class, 'method' => 'clear'],

    '/checkout' => ['controller' => CheckoutController::class, 'method' => 'index'],
    '/checkout/process' => ['controller' => CheckoutController::class, 'method' => 'process'],
    '/payment' => ['controller' => CheckoutController::class, 'method' => 'payment'],
    '/checkout/success' => ['controller' => CheckoutController::class, 'method' => 'success'],

    '/{username}' => ['controller' => PublicController::class, 'method' => 'profile'],
    '/{username}/products' => ['controller' => PublicController::class, 'method' => 'redirectToProfile'],

];
