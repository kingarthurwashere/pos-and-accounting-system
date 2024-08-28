<?php

namespace App\Livewire;

use App\Livewire\Forms\RefundRequestForm;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\RefundMethod;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class RefundDisburse extends Component
{
    use Toast;
    public bool $myModal;
    public Payment $payment;
    public Refund $refund;
    public string $label = '_';

    protected $listeners = [];


    public function render()
    {
        return view('livewire.refunds.disburse');
    }

    public function disburse()
    {
        if (!auth()->user()->location->locationFloatSuffices($this->refund->amount)) {
            $this->toast(
                type: 'error',
                title: 'Not enough float at your location',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-error',                  // Optional (daisyUI classes)
                timeout: 8000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        } else {
            $this->refund->update([
                'disbursed_by' => auth()->user()->id,
                'disbursement_location_id' => auth()->user()->login_location_id,
                'status' => 'DISBURSED',
            ]);

            $this->dispatch('refund.changed');

            $this->myModal = false;

            $this->toast(
                type: 'success',
                title: 'Disbursed successfully',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-check',       // Optional (any icon)
                css: 'alert-success',                  // Optional (daisyUI classes)
                timeout: 10000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        }
    }
}
