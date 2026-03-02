<?php

declare(strict_types=1);

namespace Fluxa\Exceptions;

use Fluxa\Http\Response;
use Throwable;

final class ErrorHandler
{
    public function __construct(private readonly bool $debug = false) {}

    public function handle(Throwable $e): Response
    {
        $statusCode = $e instanceof HttpException ? $e->statusCode : 500;

        $payload = [
            'error' => [
                'message' => $e->getMessage() ?: 'Internal Server Error',
                'code' => $statusCode,
            ],
        ];

        if ($this->debug) {
            $payload['error']['exception'] = $e::class;
            $payload['error']['file'] = $e->getFile() . ':' . $e->getLine();
            $payload['error']['trace'] = array_slice(
                array_map(
                    fn(array $frame) => ($frame['file'] ?? '?') . ':' . ($frame['line'] ?? '?') . ' ' . ($frame['class'] ?? '') . ($frame['type'] ?? '') . ($frame['function'] ?? ''),
                    $e->getTrace()
                ),
                0,
                10,
            );
        }

        return Response::json($payload, $statusCode);
    }
}
