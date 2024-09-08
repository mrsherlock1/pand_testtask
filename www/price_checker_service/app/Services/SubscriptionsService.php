<?php

namespace App\Services;

use App\Models\PriceSubscription;
use App\Models\SubscribedUser;

class SubscriptionsService
{
    public function createSubscription($link, $email, $price): void
    {
        $subscription = PriceSubscription::query()
            ->updateOrCreate(
                ['link' => $link],
                ['current_price' => $price]
            );

        $userExists = $subscription->whereHas('users', function ($user) use ($email) {
            $user->where('email', $email);
        })->exists();

        if (!$userExists) {
            $user = new SubscribedUser([
                'email' => $email
            ]);

            $subscription->users()->save($user);
        }
    }
}
