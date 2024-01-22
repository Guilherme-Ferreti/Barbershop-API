<?php

declare(strict_types=1);

namespace App\Api\Admin\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Modules\Auth\Models\Barber;
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
        abort_unless($this->canAccessAdminArea(), Response::HTTP_UNAUTHORIZED, 'Unauthorized.');

        return $next($request);
    }

    private function canAccessAdminArea(): bool
    {
        return auth()->check() && current_user() instanceof Barber;
    }
}
