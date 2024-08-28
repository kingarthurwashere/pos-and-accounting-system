<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Events\RefundDisbursed;
use App\Events\WithdrawalRequestDisbursed;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DebitLocationBalanceRefund
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
    public function handle(RefundDisbursed $event): void
    {
        Log::info("Refund:");
        Log::info($event->refund->toArray());

        if (is_null($event->refund->disbursement_datetime)) {
            $location = Location::find($event->refund->disbursement_location_id);
            $balance = $location->balance;

            $balance->usd_balance -= $event->refund->amount;

            $saved = $balance->save();

            $event->refund->update([
                'posted' => $saved,
                'disbursement_datetime' => $saved ? Carbon::now() : null
            ]);

            //Update payment to REFUNDED
            $event->refund->payment->update([
                'status' => PaymentStatus::REFUNDED
            ]);
        }
    }
}
