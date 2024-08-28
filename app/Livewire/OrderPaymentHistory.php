<?php

namespace App\Livewire;

use App\Livewire\Forms\RefundRequestForm;
use App\Models\Order;
use App\Models\Payment;
use App\Models\RefundMethod;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class OrderPaymentHistory extends Component
{
    use Toast;
    public Order $order;

    public RefundRequestForm $refundRequestForm;

    protected $listeners = [
        'refund.changed' => '$refresh',
    ];

    // #[On('payment.received')]
    // public function paymentReceived()
    // {
    // }

    public function render()
    {
        $payments = Payment::where('payable_identifier', $this->order->id)->where('source', '!=', 'INITIATED')->orderBy('id', 'DESC')->get();
        return view('livewire.order.order-payment-history', [
            'order' => $this->order,
            'items' => $payments,
            'methods' => RefundMethod::get(),
        ]);
    }
}
