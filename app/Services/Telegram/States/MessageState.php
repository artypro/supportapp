<?php

namespace App\Services\Telegram\States;

use App\Dto\TicketContextDto;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\TicketFile;
use App\Services\MessageBuilder;
use App\Events\TicketCreated;
use App\Services\TicketNumberService;

class MessageState extends BaseState
{
    protected TicketNumberService $ticketNumberService;
    protected MessageBuilder $messageBuilder;

    public function __construct()
    {
        parent::__construct();

        $this->ticketNumberService = new TicketNumberService();
    }

    public function handle($update, TicketContextDto $context)
    {
        $ticket = Ticket::create([
            'user_id'     => $context->sender->id,
            'channel'     => Ticket::CHANNEL_TLGM,
            'category_id' => $context->state['category_id'],
            'subject'     => $context->state['subject'],
            'status'      => Ticket::STATUS_NEW,
        ]);

        $ticketMessage = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'sender_id' => $context->sender->id,
            'text'      => mb_substr($context->text, 0, 2000),
        ]);

        $fileInfo = $context->fileInfo;
        if ($fileInfo) {
            TicketFile::create([
                'ticket_message_id' => $ticketMessage->id,
                'file_url' => $fileInfo->fileUrl,
                'file_name' => $fileInfo->fileName,
                'file_size' => $fileInfo->fileSize,
            ]);
        }
        event(new TicketCreated($ticket));

        $this->telegramApiService->sendMessage(
            $context->chatId,
            $this->messageBuilder->get(
                MessageBuilder::TICKET_CREATED, [
                    'number' => $this->ticketNumberService->generate($ticket)
                ]
            )
        );
        $this->stateStorageService->clear($context->chatId);

        return true;
    }
}
