<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use SellNow\Drivers\{MySQLDriver, SQLiteDriver};
use SellNow\Interface\DatabaseInterface;

session_start();

//load env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$dbConfig = require __DIR__ . '/../config/database.php';
$routes = require __DIR__ . '/../routes/web.php';

// Build container
$builder = new ContainerBuilder();

$builder->addDefinitions([
    DatabaseInterface::class => DI\factory(function () use ($dbConfig) {
        $default = $dbConfig['default'];
        $connection = $dbConfig['connections'][$default];

        return match ($default) {
            'mysql'  => new MySQLDriver($connection),
            'sqlite' => new SQLiteDriver($connection),
            default  => throw new RuntimeException('Invalid DB driver'),
        };
    }),

    Environment::class => DI\factory(function () {
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        $twig = new Environment($loader, ['debug' => true]);
        $twig->addGlobal('session', $_SESSION);
        return $twig;
    }),
]);

$container = $builder->build();

// Basic Routing (Switch Statement)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$matched = false;

foreach ($routes as $routeUri => $route) {

    if ($routeUri === $uri) {
        $controller = $container->get($route['controller']);
        $action = ($method === 'POST' && isset($route['postMethod']))
            ? $route['postMethod']
            : $route['method'];

        $controller->$action();
        $matched = true;
        break;
    }
    if (str_contains($routeUri, '{username}')) {
        $pattern = '#^' . str_replace('{username}', '([^/]+)', $routeUri) . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            $controller = $container->get($route['controller']);
            $action = ($method === 'POST' && isset($route['postMethod']))
                ? $route['postMethod']
                : $route['method'];

            $controller->$action($matches[1]);
            $matched = true;
            break;
        }
    }
}

if (!$matched) {
    http_response_code(404);
    echo "404 Not Found";
}
