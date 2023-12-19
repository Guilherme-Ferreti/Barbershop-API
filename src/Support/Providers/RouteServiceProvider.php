<?php

declare(strict_types=1);

namespace Support\Providers;

use Domain\Schedules\Models\Schedule;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));

        $this->configureRoute();
        $this->configureCustomRouteBindings();

    }

    private function configureRoute(): void
    {
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group([
                    base_path('src/App/Api/Admin/routes.php'),
                    base_path('src/App/Api/Customer/routes.php'),
                    base_path('src/App/Api/Public/routes.php'),
                ]);
        });
    }

    private function configureCustomRouteBindings(): void
    {
        Route::bind('pendingSchedule', fn (string $value) => Schedule::pending()->findOrFail($value));
    }
}
