<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Fluxa\Core\Application;
use Fluxa\Http\Request;
use Fluxa\Http\Response;

$app = new Application(dirname(__DIR__));

// Simple route
$app->router->get('/', fn(Request $request) => Response::json([
    'name' => 'Fluxa Example API',
    'version' => '1.0.0',
]));

// Route with parameters
$app->router->get('/users/{id}', fn(Request $request) => Response::json([
    'user' => [
        'id' => (int) $request->param('id'),
        'name' => 'John Doe',
    ],
]));

// POST route with JSON body
$app->router->post('/users', function (Request $request): Response {
    $data = $request->json();
    return Response::json(['created' => $data], 201);
});

// Route with controller
$app->router->get('/health', fn() => Response::json(['status' => 'ok']));

// Middleware example
$app->router->get('/admin/dashboard', fn(Request $request) => Response::json([
    'dashboard' => 'secret data',
]))->middleware(\App\Middleware\AuthMiddleware::class);

$app->handle();
