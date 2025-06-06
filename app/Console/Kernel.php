<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // ...existing commands...
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:archive-incomplete-tickets')->daily();
    }

    // ...existing methods...
}