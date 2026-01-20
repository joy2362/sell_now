<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;

session_start();

//load env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$dbConfig = require __DIR__ . '/../config/database.php';
$routes = require __DIR__ . '/../routes/web.php';
$middlewareMap = require __DIR__ . '/../config/Middleware.php';

// Build container
$builder = new ContainerBuilder();
$builder->addDefinitions(require __DIR__ . '/../config/Container.php');
$container = $builder->build();

// Basic Routing (Switch Statement)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

foreach ($routes as $routeUri => $route) {

    $isDynamic = str_contains($routeUri, '{username}');
    $matchedRoute = false;
    $params = [];

    if ($routeUri === $uri) {
        $matchedRoute = true;
    } elseif ($isDynamic) {
        $pattern = '#^' . str_replace('{username}', '([^/]+)', $routeUri) . '$#';
        if (preg_match($pattern, $uri, $matches)) {
            $matchedRoute = true;
            $params[] = $matches[1];
        }
    }

    if ($matchedRoute) {
        if (!empty($route['middleware'])) {
            foreach ($route['middleware'] as $middlewareKey) {
                $middlewareClass = $middlewareMap[$middlewareKey];
                $container->get($middlewareClass)->handle();
            }
        }

        $controller = $container->get($route['controller']);

        $action = ($method === 'POST' && isset($route['postMethod']))
            ? $route['postMethod']
            : $route['method'];

        $controller->$action(...$params);
        exit;
    }
}

http_response_code(404);
echo "404 Not Found";
