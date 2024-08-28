<?php

namespace App\Listeners;

use App\Events\WithdrawalAmountDisbursed;
use App\Events\WithdrawalRequestDisbursed;
use App\Mail\WithdrawalRequestDisbursed as MailWithdrawalRequestDisbursed;
use App\Models\Location;
use App\Models\Order;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DebitLocationBalanceWithdrawalRequest
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
    public function handle(WithdrawalRequestDisbursed $event): void
    {
        Log::info("Withdrawal request:");
        Log::info($event->withdrawalRequest->toArray());

        if (!$event->withdrawalRequest->posted) {
            $location = Location::find($event->withdrawalRequest->disbursement_location_id);
            $balance = $location->balance;

            $balance->usd_balance -= $event->withdrawalRequest->amount;

            $saved = $balance->save();

            $event->withdrawalRequest->update([
                'posted' => $saved,
                'posted_datetime' => $saved ? Carbon::now() : null
            ]);

            try {
                Mail::to($event->withdrawalRequest->email)->send(new MailWithdrawalRequestDisbursed($event->withdrawalRequest));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
}
