<?php

namespace App\Listeners;

use App\Events\LocationBalanceUpdated;
use App\Models\LocationBalance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateCityBalance
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LocationBalanceUpdated $event): void
    {
        $city = $event->balance->location->city;

        //Get locations
        $sum = 0;
        foreach ($city->locations as $l) {
            $sum += $l->balance->usd_balance;
        }

        $city->balance()->update([
            'usd_balance' => $sum
        ]);
    }
}
