<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Events\RefundDisbursed;
use App\Events\WithdrawalRequestDisbursed;
use App\Models\Location;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DebitOrderBalanceRefund
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     */
    public function handle(RefundDisbursed $event): void
    {
        Log::info("LESS ORDER BALANCE:");
        Log::info($event->refund->toArray());

        if (!is_null($event->refund->disbursement_datetime)) {
            //Get payment details
            $payment = Payment::find($event->refund->payment_id);

            if($payment->payable_slug === 'order') {
                $order = Order::find($payment->payable_identifier);
                $order->balance += $event->refund->amount;

                if($order->balance >= $order->total) {
                    $order->refunded = 1;
                }
                $order->save();
            }
        }
    }
}
