<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;

class ArchiveIncompleteTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:archive-incomplete-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive tickets with status Incomplete older than 24h by setting status to Archived';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Ticket::where('status', 'Incomplete')
            ->where('created_at', '<=', now()->subDay())
            ->update(['status' => 'Archived']);
    }
}
