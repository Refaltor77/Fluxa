<?php

declare(strict_types=1);

namespace Fluxa\Http;

final readonly class Response
{
    /** @param array<string, string> $headers */
    public function __construct(
        public int $statusCode = 200,
        public string $body = '',
        public array $headers = ['content-type' => 'application/json'],
    ) {}

    public static function json(mixed $data, int $status = 200, array $headers = []): self
    {
        return new self(
            statusCode: $status,
            body: json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            headers: array_merge(['content-type' => 'application/json'], $headers),
        );
    }

    public static function text(string $text, int $status = 200): self
    {
        return new self(
            statusCode: $status,
            body: $text,
            headers: ['content-type' => 'text/plain; charset=utf-8'],
        );
    }

    public static function empty(int $status = 204): self
    {
        return new self(statusCode: $status);
    }

    public function withStatus(int $status): self
    {
        return new self($status, $this->body, $this->headers);
    }

    public function withHeader(string $name, string $value): self
    {
        return new self($this->statusCode, $this->body, array_merge($this->headers, [$name => $value]));
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo $this->body;
    }
}
