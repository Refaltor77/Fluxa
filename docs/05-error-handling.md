# Error Handling

## Automatic JSON Errors

All uncaught exceptions are converted to JSON responses:

```json
{
    "error": {
        "message": "Route not found: GET /missing",
        "code": 404
    }
}
```

## HTTP Exceptions

Throw `HttpException` for specific status codes:

```php
use Fluxa\Exceptions\HttpException;

throw new HttpException(403, 'Forbidden');
throw new HttpException(422, 'Invalid email address');
```

## Debug Mode

Enable in `config/app.php`:

```php
return ['debug' => true];
```

Debug mode adds trace information:

```json
{
    "error": {
        "message": "Division by zero",
        "code": 500,
        "exception": "DivisionByZeroError",
        "file": "/app/src/Controllers/MathController.php:12",
        "trace": ["..."]
    }
}
```

Never enable debug mode in production.
