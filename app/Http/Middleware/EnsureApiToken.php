<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $expected = config('app.device_checker_api_token');

        if (empty($token) || empty($expected) || !hash_equals($expected, $token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
