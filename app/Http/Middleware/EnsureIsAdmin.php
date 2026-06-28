<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_if(
            !auth()->check() || !auth()->user()->isAdmin(),
            Response::HTTP_FORBIDDEN,
            'Access denied.'
        );

        return $next($request);
    }
}