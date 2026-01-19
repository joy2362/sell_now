<?php

namespace SellNow\Controllers;

use Twig\Environment;

class HomeController
{
    public function __construct(
        private Environment $twig,
    ) {}

    public function index()
    {
        echo $this->twig->render('home.html.twig');
    }
}
