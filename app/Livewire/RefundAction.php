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

class RefundAction extends Component
{
    use Toast;
    public bool $myModal;
    public Payment $payment;
    public Refund $refund;
    public string $label = '_';

    protected $listeners = [
        'payment.received' => '$refresh',
    ];

    #[On('payment.received')]
    public function paymentReceived()
    {
    }


    public function render()
    {
        return view('livewire.refunds.action');
    }

    public function action(bool $accept)
    {
        if (!$accept) {
            $this->refund->update([
                'rejected_by' => auth()->user()->id,
                'status' => 'REJECTED',
                'rejection_datetime' => Carbon::now()
            ]);
        } else {
            $this->refund->update([
                'approved_by' => auth()->user()->id,
                'status' => 'APPROVED',
                'approval_datetime' => Carbon::now()
            ]);
        }

        $this->dispatch('refund.changed');

        $this->dispatch('refund.actioned');

        $this->myModal = false;

        $this->toast(
            type: 'success',
            title: !$accept === 'Reject' ? 'Rejected' : 'Approved' . ' successfully',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-check',       // Optional (any icon)
            css: 'alert-success',                  // Optional (daisyUI classes)
            timeout: 10000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }
}
