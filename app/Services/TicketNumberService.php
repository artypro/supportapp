<?php

namespace App\Services;

use App\Models\Ticket;

class TicketNumberService
{
    /**
     * Generate a formatted ticket number for a ticket.
     *
     * @param Ticket $ticket
     * @return string
     */
    public function generate(Ticket $ticket): string
    {
        return 'AT-Ticket-' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT);
    }
}
