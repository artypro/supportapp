<?php

namespace App\Services\Telegram\States;

use App\Dto\TicketContextDto;
use Telegram\Bot\Api;

interface TicketStateInterface
{
    public function handle($update, TicketContextDto $context);
}
