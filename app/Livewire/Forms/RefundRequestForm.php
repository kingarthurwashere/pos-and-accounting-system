<?php

namespace App\Livewire\Forms;

use App\Models\Payment;
use App\Models\Refund;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Livewire\Attributes\Rule;
use Livewire\Form;

class RefundRequestForm extends Form
{

    public ?WithdrawalRequest $request;

    #[Rule('required')]
    public string $notes = '';
    #[Rule('required')]
    public string $method = 'CASH';


    public function refund(Payment $payment)
    {
        $this->validate();

        $r = $payment->refund()->create([
            'amount' => $payment->received_amount,
            'refund_method' => $this->method,
            'notes' => $this->notes,
            'initiated_by' => auth()->user()->id,
            'initiation_location_id' => auth()->user()->login_location_id,
            'initiation_datetime' => Carbon::now()
        ]);

        //$this->reset();

        return $r;
    }
}
