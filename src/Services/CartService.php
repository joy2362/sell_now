<?php

namespace SellNow\Services;

use SellNow\Models\Product;

class CartService
{
    public function __construct(private Product $product) {}

    public function getCart(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    public function getTotal(): float
    {
        $cart = $this->getCart();
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    public function addProduct(int $productId, int $quantity): array
    {
        $product = $this->product->findWhere(['id' => $productId]);

        if (!$product) {
            return ['status' => 'error', 'message' => 'Product not found'];
        }

        $_SESSION['cart'][] = [
            'product_id' => $product['id'],
            'title' => $product['title'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];

        return ['status' => 'success', 'count' => count($_SESSION['cart'])];
    }

    public function clear(): void
    {
        unset($_SESSION['cart']);
    }
}
