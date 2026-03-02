# Getting Started

## Installation

```bash
composer require fluxa/fluxa
```

## Project Setup

Run the install command to scaffold your project:

```bash
vendor/bin/fluxa install
```

This creates:

```
├── config/app.php       # Application configuration
├── public/index.php     # Entry point
├── src/Controllers/     # Your controllers
├── src/Middleware/       # Your middleware
└── .gitignore
```

## Your First API

Edit `public/index.php`:

```php
<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Fluxa\Core\Application;
use Fluxa\Http\Request;
use Fluxa\Http\Response;

$app = new Application(dirname(__DIR__));

$app->router->get('/api/hello', fn(Request $request) => Response::json([
    'greeting' => 'Hello, ' . $request->queryParam('name', 'World') . '!',
]));

$app->handle();
```

## Start the Server

```bash
vendor/bin/fluxa serve
```

Test it:

```bash
curl http://127.0.0.1:8080/api/hello?name=Fluxa
# {"greeting":"Hello, Fluxa!"}
```
