<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;
use App\Services\TelegramTicketService;
use Illuminate\Support\Facades\App;

class TelegramWebhookController extends Controller
{
    protected $service;

    public function __construct(TelegramTicketService $service)
    {
        $this->service = $service;
    }

    public function handle()
    {
        $telegram = App::make(Api::class);
        $update   = $telegram->getWebhookUpdate();
        $this->service->handleUpdate($update);
        return response()->json(['status' => 'ok']);
    }
}
