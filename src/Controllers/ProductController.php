<?php

namespace SellNow\Controllers;

use Exception;
use SellNow\DTO\Product\ProductDTO;
use SellNow\Services\ProductService;
use Twig\Environment;

class ProductController
{
    public function __construct(
        private Environment $twig,
        private ProductService $productService
    ) {}

    public function create()
    {
        echo $this->twig->render('products/add.html.twig');
    }

    public function store()
    {
        try {
            $dto = ProductDTO::fromArray($_POST, $_FILES, (int) $_SESSION['user_id']);
            $this->productService->store($dto);

            redirect("/dashboard");
        } catch (Exception $ex) {
            echo "Error: " . $ex->getMessage();
        }
    }
}
