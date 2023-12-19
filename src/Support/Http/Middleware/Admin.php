<?php

declare(strict_types=1);

namespace Support\Http\Middleware;

use Closure;
use Domain\Users\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(auth()->check() && current_user() instanceof User, Response::HTTP_UNAUTHORIZED, 'Unauthenticated.');

        return $next($request);
    }
}
