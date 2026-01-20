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

    '/login' => ['controller' => AuthController::class, 'method' => 'loginForm', 'postMethod' => 'login',  'middleware' => ['guest'],],
    '/register' => ['controller' => AuthController::class, 'method' => 'registerForm', 'postMethod' => 'register', 'middleware' => ['guest'],],
    '/dashboard' => ['controller' => AuthController::class, 'method' => 'dashboard', 'middleware' => ['auth'],],
    '/logout' => ['controller' => AuthController::class, 'method' => 'logout', 'middleware' => ['auth'],],

    '/products/add' => ['controller' => ProductController::class, 'method' => 'create', 'postMethod' => 'store', 'middleware' => ['auth'],],

    '/cart' => ['controller' => CartController::class, 'method' => 'index'],
    '/cart/add' => ['controller' => CartController::class, 'method' => 'add'],
    '/cart/clear' => ['controller' => CartController::class, 'method' => 'clear'],

    '/checkout' => ['controller' => CheckoutController::class, 'method' => 'index', 'middleware' => ['auth'],],
    '/checkout/process' => ['controller' => CheckoutController::class, 'method' => 'process', 'middleware' => ['auth'],],
    '/payment' => ['controller' => CheckoutController::class, 'method' => 'payment', 'middleware' => ['auth'],],
    '/checkout/success' => ['controller' => CheckoutController::class, 'method' => 'success', 'middleware' => ['auth'],],

    '/{username}' => ['controller' => PublicController::class, 'method' => 'profile'],
    '/{username}/products' => ['controller' => PublicController::class, 'method' => 'redirectToProfile'],

];
