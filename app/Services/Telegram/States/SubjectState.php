<?php

namespace App\Services\Telegram\States;

use App\Dto\TicketContextDto;
use App\Services\TelegramTicketService;
use App\Services\MessageBuilder;

class SubjectState extends BaseState
{
    public function handle($update, TicketContextDto $context)
    {
        $state = $context->state;
        $state['subject'] = mb_substr($context->text, 0, 2000);
        $state['step'] = TelegramTicketService::STATE_STEP_MESSAGE;

        $this->stateStorageService->set($context->chatId, $state);
        $this->telegramApiService->sendDefinedMessage($context->chatId, MessageBuilder::FILE_ATTACHMENT_PROMPT);
        return true;
    }
}
