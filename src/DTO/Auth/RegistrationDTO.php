<?php

namespace SellNow\DTO\Auth;

class RegistrationDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public string $user_name,
        public ?string $name,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: password_hash($data['password'], PASSWORD_BCRYPT),
            user_name: $data['user_name'],
            name: $data['name'],
        );
    }
}
