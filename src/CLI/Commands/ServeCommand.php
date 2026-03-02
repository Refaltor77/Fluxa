<?php

declare(strict_types=1);

namespace Fluxa\CLI\Commands;

use Fluxa\CLI\CommandInterface;
use Fluxa\CLI\Console;

final class ServeCommand implements CommandInterface
{
    public function name(): string
    {
        return 'serve';
    }

    public function description(): string
    {
        return 'Start the local development server';
    }

    public function execute(array $args): int
    {
        $host = $this->getOption($args, '--host', '127.0.0.1');
        $port = $this->getOption($args, '--port', '8080');

        $docRoot = getcwd() . '/public';
        if (!is_dir($docRoot)) {
            $docRoot = getcwd();
        }

        Console::line('');
        Console::info("Fluxa development server started on \033[1mhttp://{$host}:{$port}\033[0m");
        Console::line("  Press Ctrl+C to stop.\n");

        $router = $docRoot . '/index.php';
        passthru("php -S {$host}:{$port} {$router}");

        return 0;
    }

    private function getOption(array $args, string $flag, string $default): string
    {
        $index = array_search($flag, $args, true);
        if ($index !== false && isset($args[$index + 1])) {
            return $args[$index + 1];
        }
        return $default;
    }
}
