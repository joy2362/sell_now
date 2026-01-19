<?php

return [
    'default' => $_ENV['DB_CONNECTION'] ?? 'mysql',

    "connections" => [
        'sqlite' => [
            'path'   => $_ENV['DB_SQLITE_PATH'] ?? __DIR__ . '/../database/database.sqlite',
        ],
        'mysql' => [
            'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port' => $_ENV['DB_PORT'] ?? '3306',
            'database' => $_ENV['DB_DATABASE'] ?? 'root',
            'username' => $_ENV['DB_USERNAME'] ?? 'sellnow',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'charset' => 'utf8mb4',
        ],
    ],
];
