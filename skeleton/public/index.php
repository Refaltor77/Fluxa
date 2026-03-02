<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Fluxa\Core\Application;
use Fluxa\Http\Request;
use Fluxa\Http\Response;

$app = new Application(dirname(__DIR__));

$app->router->get('/', fn(Request $request) => Response::json([
    'message' => 'Welcome to Fluxa!',
    'docs' => 'https://github.com/fluxa/fluxa',
]));

$app->router->get('/health', fn(Request $request) => Response::json([
    'status' => 'ok',
    'timestamp' => time(),
]));

// Register your routes below
require __DIR__ . '/../routes/api.php';

$app->handle();
