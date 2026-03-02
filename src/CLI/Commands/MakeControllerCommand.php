<?php

declare(strict_types=1);

namespace Fluxa\CLI\Commands;

use Fluxa\CLI\CommandInterface;
use Fluxa\CLI\Console;

final class MakeControllerCommand implements CommandInterface
{
    public function name(): string
    {
        return 'make:controller';
    }

    public function description(): string
    {
        return 'Create a new controller class';
    }

    public function execute(array $args): int
    {
        $name = $args[0] ?? null;

        if ($name === null) {
            Console::error('Please provide a controller name.');
            Console::line('  Usage: fluxa make:controller UserController');
            return 1;
        }

        $name = str_replace('.php', '', $name);
        if (!str_ends_with($name, 'Controller')) {
            $name .= 'Controller';
        }

        $dir = getcwd() . '/src/Controllers';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/' . $name . '.php';
        if (file_exists($path)) {
            Console::error("Controller {$name} already exists.");
            return 1;
        }

        $template = <<<PHP
        <?php

        declare(strict_types=1);

        namespace App\\Controllers;

        use Fluxa\\Http\\Request;
        use Fluxa\\Http\\Response;

        final class {$name}
        {
            public function index(Request \$request): Response
            {
                return Response::json(['controller' => '{$name}']);
            }
        }

        PHP;

        file_put_contents($path, $template);
        Console::success("Controller created: src/Controllers/{$name}.php");

        return 0;
    }
}
