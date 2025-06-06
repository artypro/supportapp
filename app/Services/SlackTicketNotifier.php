<?php

namespace App\Services;

use App\Contracts\TicketNotifierInterface;
use App\Models\Ticket;
use App\Notifications\TicketSubmittedSlack;
use Illuminate\Support\Facades\Notification;

class SlackTicketNotifier implements TicketNotifierInterface
{
    public function notify(Ticket $ticket): void
    {
        Notification::route('slack', config('services.slack.webhook_url'))
            ->notify(new TicketSubmittedSlack($ticket));
    }
}
