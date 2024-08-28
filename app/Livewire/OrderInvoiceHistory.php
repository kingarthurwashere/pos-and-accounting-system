<?php

namespace App\Livewire;

use App\Livewire\Forms\RefundRequestForm;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\RefundMethod;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class OrderInvoiceHistory extends Component
{
    use Toast;
    public Order $order;

    public RefundRequestForm $refundRequestForm;

    // protected $listeners = [
    //     'payment.received' => '$refresh',
    // ];

    // #[On('payment.received')]
    // public function paymentReceived()
    // {
    // }

    public function render()
    {
        $invoices = Invoice::where('payable_identifier', $this->order->id)->orderBy('id', 'DESC')->get();
        return view('livewire.order.order-invoice-history', [
            'order' => $this->order,
            'items' => $invoices,
            'methods' => RefundMethod::get(),
        ]);
    }
}
