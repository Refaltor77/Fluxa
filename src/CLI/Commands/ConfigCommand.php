<?php

declare(strict_types=1);

namespace Fluxa\CLI\Commands;

use Fluxa\CLI\CommandInterface;
use Fluxa\CLI\Console;
use Fluxa\Config\Config;

final class ConfigCommand implements CommandInterface
{
    private string $action;

    public function __construct(string $action)
    {
        $this->action = $action;
    }

    public function name(): string
    {
        return 'config:' . $this->action;
    }

    public function description(): string
    {
        return match ($this->action) {
            'get' => 'Get a configuration value',
            'set' => 'Set a configuration value',
        };
    }

    public function execute(array $args): int
    {
        $config = new Config();
        $configPath = getcwd() . '/config';
        $config->loadDirectory($configPath);

        return match ($this->action) {
            'get' => $this->handleGet($config, $args),
            'set' => $this->handleSet($config, $args),
        };
    }

    private function handleGet(Config $config, array $args): int
    {
        $key = $args[0] ?? null;

        if ($key === null) {
            Console::error('Usage: fluxa config:get <key>');
            return 1;
        }

        $value = $config->get($key);

        if ($value === null) {
            Console::error("Key '{$key}' not found.");
            return 1;
        }

        Console::line(is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : (string) $value);
        return 0;
    }

    private function handleSet(Config $config, array $args): int
    {
        $key = $args[0] ?? null;
        $value = $args[1] ?? null;

        if ($key === null || $value === null) {
            Console::error('Usage: fluxa config:set <key> <value>');
            return 1;
        }

        $segments = explode('.', $key);
        $file = $segments[0];
        $filePath = getcwd() . '/config/' . $file . '.php';

        if (!file_exists($filePath)) {
            Console::error("Config file '{$file}.php' not found.");
            return 1;
        }

        $config->set($key, $value);
        Console::success("Set {$key} = {$value}");

        return 0;
    }
}
