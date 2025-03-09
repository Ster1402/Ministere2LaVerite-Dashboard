<?php

namespace App\Console\Commands;

use App\Services\FreeMoPayService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckPendingDonations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'donations:check-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update status of all pending donations';

    /**
     * The FreeMoPay service.
     *
     * @var FreeMoPayService
     */
    protected $freeMoPayService;

    /**
     * Create a new command instance.
     *
     * @param FreeMoPayService $freeMoPayService
     * @return void
     */
    public function __construct(FreeMoPayService $freeMoPayService)
    {
        parent::__construct();
        $this->freeMoPayService = $freeMoPayService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to check pending donations...');

        try {
            $updatedCount = $this->freeMoPayService->checkPendingDonations();

            $this->info("Completed checking pending donations. Updated {$updatedCount} donation(s).");
            Log::info("Scheduled job: checked pending donations", ['updated_count' => $updatedCount]);

            return 0; // Success
        } catch (\Exception $e) {
            $this->error('Error checking pending donations: ' . $e->getMessage());
            Log::error('Error in scheduled job for checking pending donations', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return 1; // Error
        }
    }
}
