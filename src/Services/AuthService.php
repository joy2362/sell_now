<?php

namespace SellNow\Services;

use Exception;
use SellNow\DTO\Auth\LoginDTO;
use SellNow\DTO\Auth\RegistrationDTO;
use SellNow\Models\User;

class AuthService
{
    public function __construct(private User $user) {}

    /**
     * Register a new user
     */
    public function register(RegistrationDTO $dto): void
    {
        if ($this->user->where(['email' => $dto->email])) {
            throw new Exception("Email already exists");
        }

        $this->user->create([
            'email' => $dto->email,
            'password' => $dto->password,
            'name' => $dto->name,
            'user_name' => $dto->user_name,
        ]);
    }

    /**
     * Login a user
     */
    public function login(LoginDTO $dto): array
    {
        $dbUser = $this->user->where(['email' => $dto->email])[0] ?? null;

        if (!$dbUser || !password_verify($dto->password, $dbUser['password'])) {
            throw new Exception("Invalid credentials");
        }

        return $dbUser;
    }
}
