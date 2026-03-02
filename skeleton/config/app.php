<?php

declare(strict_types=1);

return [
    'name' => 'Fluxa',
    'debug' => (bool) ($_ENV['APP_DEBUG'] ?? true),
    'host' => $_ENV['APP_HOST'] ?? '127.0.0.1',
    'port' => (int) ($_ENV['APP_PORT'] ?? 8080),
];
