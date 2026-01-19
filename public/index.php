<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use SellNow\Controllers\AuthController;
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

// Router
if (isset($routes[$uri])) {
    $route = $routes[$uri];
    $controllerClass = $route['controller'];
    $controllerMethod = $route['method'];

    if ($method === 'POST' && isset($route['postMethod'])) $controllerMethod = $route['postMethod'];

    $controller = $container->get($controllerClass);
    $controller->$controllerMethod();
}
// switch ($uri) {
//     case '/':
//         echo "hello world";
//         // echo $twig->render('layouts/base.html.twig', ['content' => "<h1>Welcome</h1><a href='/login'>Login</a>"]);
//         break;

//     case '/login':
//         $auth = $container->get(AuthController::class);
//         if ($method === 'POST')
//             $auth->login();
//         else
//             $auth->loginForm();
//         break;

//     case '/register':
//         require_once __DIR__ . '/../src/Controllers/AuthController.php';
//         $auth = new \SellNow\Controllers\AuthController($twig, $db);
//         if ($method === 'POST')
//             $auth->register();
//         else
//             $auth->registerForm();
//         break;

//     case '/logout':
//         session_destroy();
//         redirect('/');
//         break;

//     case '/dashboard':
//         require_once __DIR__ . '/../src/Controllers/AuthController.php';
//         $auth = new \SellNow\Controllers\AuthController($twig, $db);
//         $auth->dashboard();
//         break;

//     case '/products/add':
//         require_once __DIR__ . '/../src/Controllers/ProductController.php';
//         $prod = new \SellNow\Controllers\ProductController($twig, $db);
//         if ($method === 'POST')
//             $prod->store();
//         else
//             $prod->create();
//         break;

//     case '/cart':
//         require_once __DIR__ . '/../src/Controllers/CartController.php';
//         $cart = new \SellNow\Controllers\CartController($twig, $db);
//         $cart->index();
//         break;

//     case '/cart/add':
//         require_once __DIR__ . '/../src/Controllers/CartController.php';
//         $cart = new \SellNow\Controllers\CartController($twig, $db);
//         $cart->add();
//         break;

//     case '/cart/clear':
//         require_once __DIR__ . '/../src/Controllers/CartController.php';
//         $cart = new \SellNow\Controllers\CartController($twig, $db);
//         $cart->clear();
//         break;

//     case '/checkout':
//         require_once __DIR__ . '/../src/Controllers/CheckoutController.php';
//         $checkout = new \SellNow\Controllers\CheckoutController($twig, $db);
//         $checkout->index();
//         break;

//     case '/checkout/process':
//         require_once __DIR__ . '/../src/Controllers/CheckoutController.php';
//         $checkout = new \SellNow\Controllers\CheckoutController($twig, $db);
//         $checkout->process();
//         break;

//     case '/payment':
//         require_once __DIR__ . '/../src/Controllers/CheckoutController.php';
//         $checkout = new \SellNow\Controllers\CheckoutController($twig, $db);
//         $checkout->payment();
//         break;

//     case '/checkout/success':
//         require_once __DIR__ . '/../src/Controllers/CheckoutController.php';
//         $checkout = new \SellNow\Controllers\CheckoutController($twig, $db);
//         $checkout->success();
//         break;

//     // Default: Dynamic Routes (Messy)
//     default:
//         // Check for public profile /username
//         // Imperfect: Assuming anything else is a username
//         $parts = explode('/', trim($uri, '/'));

//         if (count($parts) == 1 && !empty($parts[0])) {
//             require_once __DIR__ . '/../src/Controllers/PublicController.php';
//             $pub = new \SellNow\Controllers\PublicController($twig, $db);
//             $pub->profile($parts[0]);
//         } elseif (count($parts) == 2 && $parts[1] == 'products') {
//             // /username/products -> redirect to profile
//             redirect('/' . $parts[0]);
//         } else {
//             http_response_code(404);
//             echo "404 Not Found";
//         }
//         break;
// }
