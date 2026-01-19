<?php

namespace SellNow\Controllers;

use SellNow\Interface\DatabaseInterface;
use Twig\Environment;

class PublicController
{
    public function __construct(
        private Environment $twig,
        private DatabaseInterface $db
    ) {}

    public function profile($username)
    {
        // Raw SQL to find user
        // Imperfect: Inefficient separate queries
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM users WHERE username = :u");
        $stmt->execute(['u' => $username]);
        $user = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$user) {
            echo "User not found";
            return;
        }

        // Raw SQL to find products
        // Imperfect: SQL Injection possible if $user->id was tainted? (It's not here but shows intent)
        $pStmt = $this->db->query("SELECT * FROM products WHERE user_id = $user->id");
        $products = $pStmt->fetchAll(\PDO::FETCH_ASSOC);

        echo $this->twig->render('public/profile.html.twig', [
            'seller' => $user,
            'products' => $products
        ]);
    }
}
