<?php

declare(strict_types=1);

namespace App\Controllers;

use Fluxa\Http\Request;
use Fluxa\Http\Response;

final class HomeController
{
    public function index(Request $request): Response
    {
        return Response::json([
            'message' => 'Hello from HomeController!',
        ]);
    }
}
