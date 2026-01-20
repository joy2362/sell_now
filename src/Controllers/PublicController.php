<?php

namespace SellNow\Controllers;

use Exception;
use SellNow\Interface\DatabaseInterface;
use SellNow\Models\Product;
use SellNow\Models\User;
use SellNow\Services\PublicService;
use Twig\Environment;

class PublicController
{
    public function __construct(
        private Environment $twig,
        private PublicService $publicService
    ) {}

    public function profile($username)
    {
        try {
            $data = $this->publicService->getProfileData($username);
            echo $this->twig->render('public/profile.html.twig', $data);
        } catch (Exception $ex) {
            echo "Error: " . $ex->getMessage();
        }
    }
}
