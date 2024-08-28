<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\Remittance;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemittanceReceiptEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Remittance $remittance;

    public function __construct($remittance)
    {
        $this->remittance = $remittance; // Assuming $order contains order details
    }

    public function build()
    {
        return $this->markdown('emails.instabucks-proof')
            ->subject('Instabucks Disbursement #' . $this->remittance->reference);
    }
}
