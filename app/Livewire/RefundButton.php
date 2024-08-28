<?php

namespace App\Livewire;

use App\Livewire\Forms\RefundRequestForm;
use App\Models\Payment;
use App\Models\RefundMethod;
use Livewire\Component;
use Mary\Traits\Toast;

class RefundButton extends Component
{
    use Toast;
    public bool $myModal;
    public Payment $payment;
    public ?RefundRequestForm $refundRequestForm;

    public function render()
    {
        return view('livewire.payment.refund-button', [
            'refund_methods' => RefundMethod::get()
        ]);
    }

    public function refund(Payment $payment)
    {
        $this->refundRequestForm->validate();
        try {
            $this->refundRequestForm->refund($payment);
            $this->myModal = false;
        $this->dispatch('refund.changed');

        $this->toast(
            type: 'success',
            title: 'Refund request submitted',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-check',       // Optional (any icon)
            css: 'alert-success',                  // Optional (daisyUI classes)
            timeout: 10000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
        } catch (\Throwable $th) {
            $this->toast(
                type: 'error',
                title: $th->getMessage(),
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-check',       // Optional (any icon)
                css: 'alert-error',                  // Optional (daisyUI classes)
                timeout: 10000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        }
        

        
    }
}
