<?php

declare(strict_types=1);

namespace Fluxa\CLI\Commands;

use Fluxa\CLI\CommandInterface;
use Fluxa\CLI\Console;

final class MakeMiddlewareCommand implements CommandInterface
{
    public function name(): string
    {
        return 'make:middleware';
    }

    public function description(): string
    {
        return 'Create a new middleware class';
    }

    public function execute(array $args): int
    {
        $name = $args[0] ?? null;

        if ($name === null) {
            Console::error('Please provide a middleware name.');
            Console::line('  Usage: fluxa make:middleware AuthMiddleware');
            return 1;
        }

        $name = str_replace('.php', '', $name);
        if (!str_ends_with($name, 'Middleware')) {
            $name .= 'Middleware';
        }

        $dir = getcwd() . '/src/Middleware';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/' . $name . '.php';
        if (file_exists($path)) {
            Console::error("Middleware {$name} already exists.");
            return 1;
        }

        $template = <<<PHP
        <?php

        declare(strict_types=1);

        namespace App\\Middleware;

        use Closure;
        use Fluxa\\Http\\Request;
        use Fluxa\\Http\\Response;
        use Fluxa\\Middleware\\MiddlewareInterface;

        final class {$name} implements MiddlewareInterface
        {
            public function handle(Request \$request, Closure \$next): Response
            {
                // Before request...

                \$response = \$next(\$request);

                // After request...

                return \$response;
            }
        }

        PHP;

        file_put_contents($path, $template);
        Console::success("Middleware created: src/Middleware/{$name}.php");

        return 0;
    }
}
