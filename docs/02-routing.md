# Routing

## Defining Routes

Fluxa supports all standard HTTP methods:

```php
$app->router->get('/users', $handler);
$app->router->post('/users', $handler);
$app->router->put('/users/{id}', $handler);
$app->router->patch('/users/{id}', $handler);
$app->router->delete('/users/{id}', $handler);
```

## Handlers

A handler can be a Closure or a controller array:

```php
// Closure
$app->router->get('/ping', fn(Request $r) => Response::json(['pong' => true]));

// Controller
$app->router->get('/users', [UserController::class, 'index']);
```

Controllers are resolved through the DI container automatically.

## Dynamic Parameters

Use `{name}` syntax for URL parameters:

```php
$app->router->get('/users/{id}', fn(Request $r) =>
    Response::json(['id' => $r->param('id')])
);

$app->router->get('/posts/{postId}/comments/{commentId}', fn(Request $r) =>
    Response::json([
        'post' => $r->param('postId'),
        'comment' => $r->param('commentId'),
    ])
);
```

## Per-Route Middleware

```php
$app->router->get('/admin', $handler)
    ->middleware(AuthMiddleware::class, LogMiddleware::class);
```

## Global Middleware

Applied to all routes:

```php
$app->router->middleware(CorsMiddleware::class);
```
