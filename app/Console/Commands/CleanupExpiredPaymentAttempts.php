<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\OrderController;

class CleanupExpiredPaymentAttempts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:cleanup-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired payment attempts and refund inventory';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting cleanup of expired payment attempts...');
        
        $orderController = new OrderController();
        $count = $orderController->handleExpiredPaymentAttempts();
        
        $this->info("Processed {$count} expired payment attempts");
        
        return 0;
    }
} 