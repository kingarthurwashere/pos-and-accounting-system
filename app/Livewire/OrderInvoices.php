<?php

namespace App\Livewire;

use App\Livewire\Forms\OrderCustomerForm;
use App\Livewire\Traits\OrderLiveUtils;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentSource;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class OrderInvoices extends Component
{
    use WithPagination;
    use Toast;

    public ?Order $order;
    public Collection $items;
    public bool $editCustomerModal;
    public bool $receivePaymentModal;

    public OrderCustomerForm $orderCustomerForm;


    #[Rule('required')]
    public $tender;
    #[Rule('required')]
    public string $source;

    #[Validate('required')]
    public $amountDue;

    protected $listeners = [
        'payment.received' => '$refresh',
    ];

    public function mount()
    {
        $this->source = 'CASH';
        $this->orderCustomerForm->customerName = $this->order->customer_name;
        $this->orderCustomerForm->customerEmail = $this->order->customer_email;
        $this->orderCustomerForm->customerPhone = $this->order->customer_phone;
    }

    #[On('order-payment-initiated')]
    public function paymentInitiated()
    {
    }

    public function changeToPartPayment()
    {
        $invoice = $this->order->initiatedPayment;
        $invoice->covers_full_balance = 0;
        $invoice->save();
    }

    public function changeToFullPayment()
    {

        $invoice = $this->order->initiatedPayment;
        $invoice->covers_full_balance = 1;
        $invoice->save();
    }

    public function receivePayment()
    {
        $invoice = $this->order->initiatedPayment;

        if ($invoice->covers_full_balance) {
            $this->amountDue = $this->order->balance / 100;

            if ($this->source !== 'CASH') {
                $this->tender = $this->order->balance / 100;
            }
        } else {
            if ($this->source !== 'CASH') {
                $this->tender = $this->amountDue;
            }
        }

        $this->validate([
            'tender' => 'required|numeric|min:' . $this->amountDue,
            'amountDue' => 'required|numeric|max:' . $this->order->balance / 100,
            'source' => 'required',
        ]);

        $change_amount = $this->tender - $this->amountDue;
        $closing_balance = $this->order->balance - ($this->amountDue * 100);

        $payment = new Payment;
        $payment->invoice_id = $invoice->id;
        $payment->tender = $this->tender;
        $payment->source = $this->source;
        $payment->payable_slug = 'order';
        $payment->covers_full_balance = $invoice->covers_full_balance;
        $payment->received_amount = $this->amountDue;
        $payment->payable_identifier = $this->order->id;
        $payment->change_amount = $change_amount;
        $payment->status = 'RECEIVED';
        $payment->receiving_cashier = auth()->user()->id;
        $payment->location_id = auth()->user()->login_location_id;
        $payment->opening_balance = $this->order->balance;
        $payment->closing_balance = $closing_balance;
        $payment->initiated_by = $invoice->initiated_by;
        $payment->received_amount_datetime = Carbon::now();
        $payment->save();

        $this->dispatch('payment.received');

        $this->receivePaymentModal = false;

        $this->toast(
            type: 'success',
            title: 'Payment Received',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-check',       // Optional (any icon)
            css: 'alert-success',                  // Optional (daisyUI classes)
            timeout: 10000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }

    public function saveCustomerDetails()
    {
        $this->orderCustomerForm->validate();
        $this->orderCustomerForm->update($this->order);

        $this->editCustomerModal = false;

        $this->toast(
            type: 'success',
            title: 'Customer Details Updated',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-check',       // Optional (any icon)
            css: 'alert-success',                  // Optional (daisyUI classes)
            timeout: 10000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }

    public function render()
    {
        return view('livewire.order.invoices', [
            'order' => $this->order,
            'items' => $this->items,
            'payment_sources' => PaymentSource::where('slug', '!=', 'refund')->get(),
        ]);
    }
}
