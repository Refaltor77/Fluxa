# Middleware

## Creating Middleware

Implement `MiddlewareInterface`:

```php
<?php
declare(strict_types=1);

namespace App\Middleware;

use Closure;
use Fluxa\Http\Request;
use Fluxa\Http\Response;
use Fluxa\Middleware\MiddlewareInterface;

final class TimingMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        $duration = round((microtime(true) - $start) * 1000, 2);
        return $response->withHeader('x-response-time', $duration . 'ms');
    }
}
```

Or use the CLI:

```bash
vendor/bin/fluxa make:middleware RateLimiter
```

## Pipeline

Middleware executes in order. Each middleware can:

1. Modify the request before passing it down
2. Call `$next($request)` to continue the chain
3. Modify the response before returning it
4. Short-circuit by returning a Response without calling `$next`

```php
// Short-circuit example
final class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('authorization') === '') {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
```
