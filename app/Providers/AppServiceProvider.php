<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\TicketNotifierInterface;
use App\Services\SlackTicketNotifier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TicketNotifierInterface::class, SlackTicketNotifier::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
