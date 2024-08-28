<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Events\RemittanceDisbursed;
use App\Events\RemittanceFloatDeposited;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CreditLocationBalanceRemittance
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
    public function handle(RemittanceFloatDeposited $event): void
    {
        Log::info("Remittance Float Deposit:");
        Log::info($event->remittance->toArray());

        $location = Location::find($event->remittance->funded_location_id);
     
        $balance = $location->balance;

        $balance->usd_balance += $event->remittance->amount;
        $saved = $balance->save();

        //Acknowledge payment
        $event->remittance->payment->update([
            'posted' => $saved,
        ]);   
    }
}
