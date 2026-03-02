<?php

declare(strict_types=1);

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        static $config = null;

        if ($config === null) {
            $config = new \Fluxa\Config\Config();
            $configPath = getcwd() . '/config';
            if (is_dir($configPath)) {
                $config->loadDirectory($configPath);
            }
        }

        return $config->get($key, $default);
    }
}
