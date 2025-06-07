<?php

namespace App\Services\Telegram\States;

use App\Services\Telegram\StateStorageService;
use App\Services\Telegram\TelegramApiService;
use App\Services\MessageBuilder;

abstract class BaseState implements TicketStateInterface
{
    protected TelegramApiService $telegramApiService;
    protected MessageBuilder $messageBuilder;
    protected StateStorageService $stateStorageService;

    public function __construct() {
        $this->telegramApiService = app()->make(TelegramApiService::class);
        $this->messageBuilder = new MessageBuilder();
        $this->stateStorageService = new StateStorageService();
    }
}
