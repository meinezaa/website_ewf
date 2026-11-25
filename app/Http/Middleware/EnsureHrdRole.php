<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHrdRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $roleName = $request->user()?->role?->role_name;

        abort_unless(is_string($roleName) && strcasecmp(trim($roleName), 'HRD') === 0, 403);

        return $next($request);
    }
}
