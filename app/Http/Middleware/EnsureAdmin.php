<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            mb_strtolower((string) $request->user()?->role?->role_name) === 'admin',
            Response::HTTP_FORBIDDEN
        );

        return $next($request);
    }
}
