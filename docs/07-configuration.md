# Configuration

## Config Files

Place PHP files in the `config/` directory. Each file represents a namespace:

```php
// config/app.php
return [
    'name' => 'My API',
    'debug' => true,
];

// config/database.php
return [
    'driver' => 'sqlite',
    'path' => __DIR__ . '/../database.sqlite',
];
```

## Accessing Values

Use dot notation to access nested values:

```php
$app->config->get('app.name');              // 'My API'
$app->config->get('database.driver');       // 'sqlite'
$app->config->get('app.missing', 'default'); // 'default'
```

## Setting Values at Runtime

```php
$app->config->set('app.debug', false);
```

## Using the Helper

The global `config()` helper loads from the working directory:

```php
$name = config('app.name', 'Fluxa');
```
