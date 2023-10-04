<?php

namespace App\Console\Commands;

use App\Models\Plan;
use Illuminate\Console\Command;

class ExpiredPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired:plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expired the plan and level type become 1';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
			\Log::info("Start expired plan command!");
    
			
			

			
			\Log::info("End expired plan command!");
			
			return true;
		}catch (Exception $e) {
       		 return $e;
		}
    }
}
