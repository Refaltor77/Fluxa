<?php

declare(strict_types=1);

namespace Fluxa\CLI\Commands;

use Fluxa\CLI\CommandInterface;
use Fluxa\CLI\Console;

final class InstallSkeletonCommand implements CommandInterface
{
    public function name(): string
    {
        return 'install:skeleton';
    }

    public function description(): string
    {
        return 'Post-install setup for new projects (called by Composer)';
    }

    public function execute(array $args): int
    {
        $base = getcwd();

        Console::line('');
        Console::line("\033[1;36m  ⚡ Fluxa\033[0m");
        Console::line('');

        if (file_exists($base . '/.env.example') && !file_exists($base . '/.env')) {
            copy($base . '/.env.example', $base . '/.env');
            Console::success('Environment file created (.env)');
        }

        Console::line('');
        Console::success('Your Fluxa project is ready!');
        Console::line('');
        Console::info('Start the development server:');
        Console::line("    \033[1mcd " . basename($base) . "\033[0m");
        Console::line("    \033[1mcomposer serve\033[0m");
        Console::line('');
        Console::info("Or use the Fluxa CLI:");
        Console::line("    \033[1mvendor/bin/fluxa serve\033[0m");
        Console::line('');

        return 0;
    }
}
