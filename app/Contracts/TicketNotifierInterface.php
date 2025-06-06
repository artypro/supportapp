<?php

namespace App\Contracts;

use App\Models\Ticket;

interface TicketNotifierInterface
{
    public function notify(Ticket $ticket): void;
}
