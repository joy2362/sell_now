<?php

namespace SellNow\Controllers;

use SellNow\Services\CheckoutService;
use Twig\Environment;

class CheckoutController
{
    public function __construct(
        private Environment $twig,
        private CheckoutService $checkoutService
    ) {}

    public function index()
    {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            redirect('/cart');
        }

        echo $this->twig->render('checkout/index.html.twig', [
            'total'     => $this->checkoutService->cartTotal($cart),
            'providers' => $this->checkoutService->providers(),
        ]);
    }

    public function process()
    {
        $provider = $_POST['provider'] ?? null;
        $cart = $_SESSION['cart'] ?? [];

        if (!$provider || empty($cart)) {
            redirect('/cart');
        }

        $total = $this->checkoutService->cartTotal($cart);

        redirect("/payment?provider=$provider&total=$total");
    }

    public function payment()
    {
        $provider = $_GET['provider'] ?? null;
        $total    = (float) ($_GET['total'] ?? 0);

        echo $this->twig->render('checkout/payment.html.twig', compact('provider', 'total'));
    }

    public function success()
    {
        $provider = $_POST['provider'] ?? 'Unknown';
        $cart = $_SESSION['cart'] ?? [];

        $total = $this->checkoutService->cartTotal($cart);

        $this->checkoutService->pay($provider, $total, [
            'user' => $_SESSION['user_id'] ?? null
        ]);

        unset($_SESSION['cart']);

        echo $this->twig->render('checkout/success.html.twig', [
            'provider' => $provider
        ]);
    }
}
