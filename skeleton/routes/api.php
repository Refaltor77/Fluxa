<?php

declare(strict_types=1);

use Fluxa\Http\Request;
use Fluxa\Http\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Define your API routes here. The $app variable is available
| from the parent scope (public/index.php).
|
*/

$app->router->get('/api/example', fn(Request $request) => Response::json([
    'message' => 'This is an example endpoint',
    'query' => $request->query,
]));
