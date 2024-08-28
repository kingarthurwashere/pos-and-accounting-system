<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Payment $payment;

    public function __construct($payment)
    {
        $this->payment = $payment; // Assuming $order contains order details
    }

    public function build()
    {
        return $this->markdown('emails.order-payment-receipt')
            ->subject('Dxbrunners Receipt #' . $this->payment->id);
    }
}
