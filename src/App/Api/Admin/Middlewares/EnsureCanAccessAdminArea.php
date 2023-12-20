<?php

declare(strict_types=1);

namespace App\Api\Admin\Middlewares;

use Closure;
use Domain\Barbers\Models\Barber;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanAccessAdminArea
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(auth()->check() && current_user() instanceof Barber, Response::HTTP_UNAUTHORIZED, 'Unauthenticated.');

        return $next($request);
    }
}
