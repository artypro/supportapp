<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class TicketSubmittedSlack extends Notification
{
    use Queueable;

    public function __construct(
        protected Ticket $ticket
    ) {
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content('A new support ticket has been submitted!')
            ->attachment(function ($attachment) {
                $attachment->title('Ticket Details')
                    ->fields([
                                 'User'      => $this->ticket->user_id ? $this->ticket->user_id : 'Telegram User',
                                 'Ticket ID' => $this->ticket->id,
                                 'Channel'   => $this->ticket->channel,
                                 'Category'  => $this->ticket->category_id,
                                 'Subject'   => $this->ticket->subject,
                             ]);
            });
    }
}
