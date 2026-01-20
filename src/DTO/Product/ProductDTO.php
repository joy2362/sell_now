<?php

namespace SellNow\DTO\Product;

class ProductDTO
{
    public function __construct(
        public int $user_id,
        public string $title,
        public string $slug,
        public float $price,
        public ?string $image_path,
        public ?string $file_path,
        public bool $is_active
    ) {}

    public static function fromArray(array $data, array $files, int $user_id): self
    {
        return new self(
            user_id: $user_id,
            title: $data['title'],
            slug: strtolower(str_replace(' ', '-', $data['title'])) . '-' . rand(1000, 9999),
            price: (float) $data['price'],
            image_path: uploadFile($files['image'] ?? null, '', __DIR__ . '/../../../public/uploads/'),
            file_path: uploadFile($files['product_file'] ?? null, 'dl_', __DIR__ . '/../../../public/uploads/'),
            is_active: true
        );
    }
}
