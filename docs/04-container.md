# Dependency Injection Container

## Auto-Resolution

The container automatically resolves class dependencies via reflection:

```php
final class UserRepository
{
    public function findAll(): array { return []; }
}

final class UserController
{
    public function __construct(private UserRepository $repo) {}

    public function index(Request $request): Response
    {
        return Response::json($this->repo->findAll());
    }
}

// UserRepository is auto-resolved — no registration needed
$app->router->get('/users', [UserController::class, 'index']);
```

## Singletons

Same instance returned every time:

```php
$app->container->singleton(
    DatabaseConnection::class,
    fn($c) => new DatabaseConnection('sqlite:app.db')
);
```

## Factories

New instance every time:

```php
$app->container->factory(
    Logger::class,
    fn($c) => new Logger(date('Y-m-d'))
);
```

## Pre-Built Instances

```php
$app->container->instance(Config::class, $myConfig);
```

## Interface Binding

```php
$app->container->bind(
    CacheInterface::class,
    fn($c) => $c->get(RedisCache::class)
);
```
