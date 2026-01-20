<?php

namespace SellNow\Services;

use Exception;
use SellNow\Models\User;
use SellNow\Models\Product;

class PublicService
{
    public function __construct(
        private User $userModel,
        private Product $productModel
    ) {}


    public function getProfileData(string $username): ?array
    {
        $user = $this->userModel->where(['user_name' => $username]);

        if (!$user || !isset($user[0])) {
            throw new Exception("Username not found");
        }

        $products = $this->productModel->where(['user_id' => $user[0]['id']]);

        return [
            'user' => $user,
            'products' => $products
        ];
    }
}
