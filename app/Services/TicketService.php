<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;
use App\Contracts\TicketNotifierInterface;

class TicketService
{
    public function __construct(protected TicketNotifierInterface $notifier) {}

    public function createFromWebForm(array $validated): Ticket
    {
        $ticket = $this->createTicket($validated);
        $this->createInitialMessage($ticket, $validated);
        $this->notifier->notify($ticket);
        return $ticket;
    }

    protected function createTicket(array $validated): Ticket
    {
        return Ticket::create([
            'user_id' => Auth::id(),
            'channel' => Ticket::CHANNEL_WEB,
            'category_id' => $validated['category_id'],
            'subject' => $validated['subject'],
            'status' => Ticket::STATUS_NEW,
        ]);
    }

    protected function createInitialMessage(Ticket $ticket, array $validated): void
    {
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'sender_id' => Auth::id(),
            'text' => $validated['message'] ?? $validated['subject'],
        ]);
    }
}
