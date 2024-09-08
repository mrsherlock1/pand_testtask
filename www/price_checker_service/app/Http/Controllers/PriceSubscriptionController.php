<?php

namespace App\Http\Controllers;

use App\Exceptions\ParsingErrorException;
use App\Http\Requests\PriceSubscriptionRequest;
use App\Services\PriceScrapperService;
use App\Services\SubscriptionsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PriceSubscriptionController extends Controller
{
    public function __construct(public SubscriptionsService $subscriptionsService)
    {
    }

    public function subscribe(PriceSubscriptionRequest $request)
    {
        try {
            $parsedPrice = (new PriceScrapperService(
                $request->input('link')
            ))->parse();

            $this->subscriptionsService->createSubscription(
                link: $request->input('link'),
                email: $request->input('email'),
                price: $parsedPrice
            );

        } catch (ParsingErrorException $exception) {
            return response()->json([
                'message' => 'Failed to parse the data, pleaase check the link'
            ], 400);
        } catch (\Exception $exception) {
            Log::error('Operation Failed', [
                'exception' => $exception
            ]);
            return response()->json([
                'message' => 'Operation failed'
            ], 500);
        }
    }
}
