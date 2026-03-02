<?php

declare(strict_types=1);

namespace Fluxa\Http;

final readonly class Request
{
    /** @param array<string, string> $headers */
    public function __construct(
        public string $method,
        public string $path,
        public array $query,
        public array $headers,
        public string $body,
        public array $params = [],
    ) {}

    public static function capture(): self
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$name] = $value;
            }
        }

        return new self(
            method: $method,
            path: $path,
            query: $_GET,
            headers: $headers,
            body: file_get_contents('php://input') ?: '',
        );
    }

    public function json(): mixed
    {
        return json_decode($this->body, true, 512, JSON_THROW_ON_ERROR);
    }

    public function header(string $name, string $default = ''): string
    {
        return $this->headers[strtolower($name)] ?? $default;
    }

    public function param(string $name, string $default = ''): string
    {
        return $this->params[$name] ?? $default;
    }

    public function queryParam(string $name, string $default = ''): string
    {
        return $this->query[$name] ?? $default;
    }

    public function withParams(array $params): self
    {
        return new self(
            method: $this->method,
            path: $this->path,
            query: $this->query,
            headers: $this->headers,
            body: $this->body,
            params: $params,
        );
    }
}
