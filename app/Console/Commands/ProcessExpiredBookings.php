<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class ProcessExpiredBookings extends Command
{
    protected $signature = 'bookings:process-expired';
    protected $description = 'Process booking requests that have expired confirmation deadlines';

    public function handle(BookingService $bookingService): int
    {
        $this->info('Processing expired booking confirmations...');

        $count = $bookingService->processExpiredConfirmations();

        $this->info("Processed {$count} expired booking(s).");

        return Command::SUCCESS;
    }
}

