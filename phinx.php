<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

// Load .env for Phinx CLI
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dbConfig = require __DIR__ . '/config/database.php';

$driver = $dbConfig['default'];

$environments = [
    'default_migration_table' => 'phinxlog',
    'default_environment'     => 'development',

    'development' => [],
];

if ($driver === 'sqlite') {
    $environments['development'] = [
        'adapter' => 'sqlite',
        'name'    => $dbConfig['connections'][$driver]['path'],
    ];
} else {
    $environments['development'] = [
        'adapter' => 'mysql',
        'host'    => $dbConfig['connections'][$driver]['host'],
        'name'    => $dbConfig['connections'][$driver]['database'],
        'user'    => $dbConfig['connections'][$driver]['username'],
        'pass'    => $dbConfig['connections'][$driver]['password'],
        'port'    => $dbConfig['connections'][$driver]['port'],
        'charset' => 'utf8mb4',
    ];
}

return [
    'paths' => [
        'migrations' => 'database/migrations',
        'seeds'      => 'database/seeds',
    ],
    'environments' => $environments,
];
