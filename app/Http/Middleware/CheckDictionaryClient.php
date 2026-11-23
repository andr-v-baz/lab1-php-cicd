<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDictionaryClient
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->hasHeader('X-Dictionary-Client')) {
            return response()->json([
                'message' => 'Forbidden: X-Dictionary-Client header is required',
            ], 403)->header('X-Security-Policy', 'Enforced');
        }

        $response = $next($request);

        $response->headers->set('X-Security-Policy', 'Enforced');

        return $response;
    }
}
