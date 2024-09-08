<?php

namespace App\Jobs;

use App\Exceptions\ParsingErrorException;
use App\Models\PriceSubscription;
use App\Services\PriceScrapperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckPriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public PriceSubscription $priceSubscription)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $parsedPrice = (new PriceScrapperService(
                $this->priceSubscription->link
            ))->parse();

            if ($parsedPrice != $this->priceSubscription->current_price) {
                $this->priceSubscription->current_price = $parsedPrice;
                $this->priceSubscription->save();
                $this->priceSubscription->users->each(function ($user) {
                    NotifyUsersJob::dispatch($user, $this->priceSubscription->current_price);
                });
            }

        } catch (\Exception $exception) {
            Log::error('Failed to check price', [
                'exception' => $exception
            ]);
        }
    }
}
