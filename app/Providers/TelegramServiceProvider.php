<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Telegram\Bot\Api;

class TelegramServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Api::class, function ($app) {
            return new Api(config('services.telegram.bot_token'));
        });
    }
}
