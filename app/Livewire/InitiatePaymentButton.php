<?php

namespace App\Livewire;

use App\Enums\InvoiceStatus;
use App\Livewire\Traits\OrderLiveUtils;
use App\Models\Order;
use App\Models\Payable;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Livewire;
use Mary\Traits\Toast;

class InitiatePaymentButton extends Component
{
    public Order $order;
    use Toast;
    public $label = 'Initiate Payment';
    public $received_amount;
    public $selectedPaymentOption = 'Full';
    public $showPartAmountInput = false;

    public function initiatePayment()
    {
        try {
            if (!$this->order->initiatedPayment) {
                auth()->user()->initiatedPayments()->create([
                    'amount_due' => $this->selectedPaymentOption === 'Full' ? $this->order->balance : null,
                    'payable_slug' => 'order',
                    'status' => $this->selectedPaymentOption === 'Full' ? InvoiceStatus::NOTPAID : InvoiceStatus::DRAFT,
                    'payable_identifier' => $this->order->id,
                    'covers_full_balance' => $this->selectedPaymentOption === 'Full' ? true : false
                ]);

                $this->dispatch('order-payment-initiated');

                $this->toast(
                    type: 'success',
                    title: 'Payment initiated',
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-check',       // Optional (any icon)
                    css: 'alert-success',                  // Optional (daisyUI classes)
                    timeout: 10000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
            } else {

                $this->toast(
                    type: 'warning',
                    title: 'Payment already initiated',
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-information-circle',       // Optional (any icon)
                    css: 'alert-warning',                  // Optional (daisyUI classes)
                    timeout: 5000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
            $this->toast(
                type: 'error',
                title: 'Payment initiation failed',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-error',                  // Optional (daisyUI classes)
                timeout: 5000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        }
    }


    public function updatedSelectedPaymentOption($value)
    {
        $this->selectedPaymentOption = $value;
        $this->showPartAmountInput = $value === 'Part';
    }

    public function render()
    {
        return view('livewire.initiate-payment-button');
    }
}
