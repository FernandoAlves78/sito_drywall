<?php

namespace App\Providers;

use App\Contracts\PreventivoCalendarSync;
use App\Services\GoogleCalendarService;
use App\Services\GooglePreventivoCalendarSync;
use App\Services\NullPreventivoCalendarSync;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GoogleCalendarService::class);

        $this->app->bind(PreventivoCalendarSync::class, function ($app) {
            if ($app->make(GoogleCalendarService::class)->isConfigured()) {
                return $app->make(GooglePreventivoCalendarSync::class);
            }

            return $app->make(NullPreventivoCalendarSync::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
