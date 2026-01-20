<?php

namespace SellNow\Controllers;

use SellNow\Services\CartService;
use Twig\Environment;

class CartController
{
    public function __construct(
        private Environment $twig,
        private CartService $cartService
    ) {}

    public function index(): void
    {
        $cart = $this->cartService->getCart();
        $total = $this->cartService->getTotal();

        echo $this->twig->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    public function add(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);

        if ($productId <= 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid product'
            ]);
            exit;
        }

        $result = $this->cartService->addProduct($productId, $quantity);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'cart' => $result
        ]);
        exit;
    }

    public function clear(): void
    {
        $this->cartService->clear();
        redirect("/cart");
    }
}
