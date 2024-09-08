<?php

namespace App\Console\Commands;

use App\Jobs\CheckPriceJob;
use App\Models\PriceSubscription;
use Illuminate\Console\Command;

class CheckPriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-price-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscriptions = PriceSubscription::query()
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($subscriptions as $subscription) {
            CheckPriceJob::dispatch(
                $subscription
            );
        }
    }
}
