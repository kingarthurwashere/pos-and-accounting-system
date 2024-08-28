<?php

namespace App\Listeners;

use App\Events\OrderPaymentReceived;
use App\Jobs\UpdateAgentxOrderBalance;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class UpdateOrderBalance
{
    private $httpClient;

    public function __construct()
    {
        // Initialize Guzzle HTTP client
        $this->httpClient = new Client();
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPaymentReceived $event): void
    {
        if ($event->payment->receiving_cashier !== null) {
            $order = Order::find($event->payment->payable_identifier);
            $order->balance -= $event->payment->received_amount;

            $payment = Payment::find($event->payment->id);
            $payment->update([
                'posted' => $order->save(),
                'posted_datetime' => Carbon::now()
            ]);

            //Update Location Balance
            $balance = $payment->location->balance;
            $balance->update([
                'usd_balance' => $balance->usd_balance + $payment->received_amount
            ]);

            $order = Order::find($payment->payable_identifier);

            if ($order) {
                if ($order->source === 'ONLINE') {
                    UpdateAgentxOrderBalance::dispatch($event->payment->id);
                }
            }
        } else {
            Log::error('Payment DID NOT UPDATE order balance');
        }
    }
}
