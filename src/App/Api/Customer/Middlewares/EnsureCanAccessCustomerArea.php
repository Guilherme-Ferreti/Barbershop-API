<?php

declare(strict_types=1);

namespace App\Api\Customer\Middlewares;

use Closure;
use Domain\Customers\Models\Customer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanAccessCustomerArea
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($this->canAccessCustomerArea(), Response::HTTP_UNAUTHORIZED, 'Unauthorized.');

        return $next($request);
    }

    private function canAccessCustomerArea(): bool
    {
        return auth()->check() && current_user() instanceof Customer;
    }
}
