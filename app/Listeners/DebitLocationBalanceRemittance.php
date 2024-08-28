<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Events\RemittanceDisbursed;
use App\Jobs\CompleteAgentxRemittance;
use App\Mail\RemittanceReceiptEmail;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DebitLocationBalanceRemittance
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
    public function handle(RemittanceDisbursed $event): void
    {
        Log::info('Debiting Location');
        Log::info($event->remittance);
        if (is_null($event->remittance->disbursed_at)) {
            $location = Location::find($event->remittance->disburse_location_id);
            $balance = $location->balance;

            $balance->usd_balance -= $event->remittance->receivable;

            $saved = $balance->save();

            $event->remittance->update([
                'posted' => $saved,
                'disbursed_at' => $saved ? Carbon::now() : null
            ]);

            try {
                Log::info('SENDING REM RECEIPT');
                Mail::to($event->remittance->receiver_email)->send(new RemittanceReceiptEmail($event->remittance));
                CompleteAgentxRemittance::dispatch($event->remittance->reference);
            } catch (\Throwable $th) {
                //throw $th;
            }
        } else {
            
        }
    }
}
