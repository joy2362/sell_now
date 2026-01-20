<?php

namespace SellNow\Controllers;

use Exception;
use SellNow\DTO\Auth\{LoginDTO, RegistrationDTO};
use SellNow\Services\AuthService;
use Twig\Environment;

class AuthController
{
    public function __construct(
        private Environment $twig,
        private AuthService $authService
    ) {}

    public function loginForm()
    {
        echo $this->twig->render('auth/login.html.twig');
    }

    public function login()
    {
        try {
            $dto = LoginDTO::fromArray($_POST);

            $user = $this->authService->login($dto);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['user_name'];

            redirect("/dashboard");
        } catch (Exception $e) {
            redirect("/login?error=" . urlencode($e->getMessage()));
        }
    }

    public function registerForm()
    {
        echo $this->twig->render('auth/register.html.twig');
    }

    public function register()
    {
        try {
            $dto = RegistrationDTO::fromArray($_POST);
            $this->authService->register($dto);

            redirect("/login?msg=Registered successfully");
        } catch (Exception $e) {
            die("Error registering: " . $e->getMessage());
        }
    }

    public function dashboard()
    {
        echo $this->twig->render('dashboard.html.twig', ['username' => $_SESSION['username']]);
    }

    public function logout()
    {
        session_destroy();
        redirect('/');
    }
}
