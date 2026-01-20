<?php

namespace SellNow\Services;

use SellNow\DTO\Product\ProductDTO;
use SellNow\Models\Product;

class ProductService
{
    public function __construct(private Product $productModel) {}

    public function store(ProductDTO $dto)
    {
        $this->productModel->create([
            'title' => $dto->title,
            'slug' => $dto->slug,
            'price' => $dto->price,
            'user_id' => $dto->user_id,
            'image_path' => $dto->image_path,
            'file_path' => $dto->file_path,
            'is_active' => $dto->is_active,
        ]);
    }
}
