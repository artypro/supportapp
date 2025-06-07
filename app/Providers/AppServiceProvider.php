<?php

namespace App\Providers;

use App\Services\MessageBuilder;
use Illuminate\Support\ServiceProvider;
use App\Contracts\TicketNotifierInterface;
use App\Services\SlackTicketNotifier;
use App\Services\Telegram\TelegramApiService;
use Telegram\Bot\Api;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TicketNotifierInterface::class, SlackTicketNotifier::class);
        $this->app->singleton(TelegramApiService::class, function ($app) {
            return new TelegramApiService($app->make(Api::class), new MessageBuilder);
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
