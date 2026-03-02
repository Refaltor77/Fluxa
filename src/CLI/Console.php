<?php

declare(strict_types=1);

namespace Fluxa\CLI;

final class Console
{
    private const VERSION = '1.0.0';

    /** @var array<string, CommandInterface> */
    private array $commands = [];

    public function register(CommandInterface $command): void
    {
        $this->commands[$command->name()] = $command;
    }

    public function run(array $argv): int
    {
        $commandName = $argv[1] ?? null;
        $args = array_slice($argv, 2);

        if ($commandName === null || $commandName === 'help' || $commandName === '--help') {
            return $this->showHelp();
        }

        if ($commandName === '--version') {
            self::line('Fluxa ' . self::VERSION);
            return 0;
        }

        $command = $this->commands[$commandName] ?? null;

        if ($command === null) {
            self::error("Unknown command: {$commandName}");
            self::line('Run "fluxa help" for available commands.');
            return 1;
        }

        return $command->execute($args);
    }

    private function showHelp(): int
    {
        self::line('');
        self::line("\033[1;36m  Fluxa\033[0m v" . self::VERSION);
        self::line('  Ultra-minimalist API micro-framework');
        self::line('');
        self::line("\033[1mAvailable commands:\033[0m");
        self::line('');

        foreach ($this->commands as $name => $command) {
            self::line(sprintf("  \033[32m%-20s\033[0m %s", $name, $command->description()));
        }

        self::line('');
        return 0;
    }

    public static function line(string $text): void
    {
        fwrite(STDOUT, $text . PHP_EOL);
    }

    public static function error(string $text): void
    {
        fwrite(STDERR, "\033[31m  ERROR:\033[0m {$text}" . PHP_EOL);
    }

    public static function success(string $text): void
    {
        fwrite(STDOUT, "\033[32m  ✓\033[0m {$text}" . PHP_EOL);
    }

    public static function info(string $text): void
    {
        fwrite(STDOUT, "\033[34m  ➜\033[0m {$text}" . PHP_EOL);
    }
}
