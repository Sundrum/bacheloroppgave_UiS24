<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class SubscriptionPaymentTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptionPayment:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform subscription payment task';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Your task logic here
        // This could be sending emails, generating reports, etc.
        // $this->info('Custom task executed successfully!');
        // Log::info('Custom task executed successfully!');
    }
}
