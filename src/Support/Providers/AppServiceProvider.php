<?php

declare(strict_types=1);

namespace Support\Providers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Model::preventLazyLoading(! app()->isProduction());

        $this->registerTelescope();

        $this->configureMacros();
    }

    private function registerTelescope(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    private function configureMacros(): void
    {
        Carbon::macro('isSameHourAndMinute', fn ($date) => $this->isSameHour($date) && $this->isSameMinute($date));

        Carbon::macro('tryCreateFromFormat', function (string $format, $value) {
            try {
                return Carbon::createFromFormat($format, $value);
            } catch (Exception) {
                return null;
            }
        });
    }
}
