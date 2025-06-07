<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Services\SlackTicketNotifier;

class SlackNotifier
{
    protected SlackTicketNotifier $notifier;

    public function __construct(SlackTicketNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function handle(TicketCreated $event)
    {
        $this->notifier->notify($event->ticket);
    }
}
