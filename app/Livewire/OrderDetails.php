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

class OrderDetails extends Component
{
    use WithPagination;
    use Toast;

    public ?Order $order;
    public $online_order;
    public Collection $items;
    public bool $editCustomerModal;
    public bool $receivePaymentModal;
    public bool $paymentDetailsModal = false;
    public Payment $recentPayment;
    public bool $amountDisabled = false;

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

        if ($this->order->initiatedPayment) {
            if ($this->order->initiatedPayment->covers_full_balance == 1) {
                $this->amountDisabled = true;
            }
            $this->amountDue = $this->order->initiatedPayment->amount_due / 100;
        }
    }

    #[On('order-payment-initiated')]
    public function paymentInitiated()
    {
    }

    public function changeToPartPayment()
    {
        $this->amountDisabled = false;
        $invoice = $this->order->initiatedPayment;
        $invoice->covers_full_balance = 0;
        $invoice->save();
    }

    public function changeToFullPayment()
    {

        $this->amountDisabled = true;
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
                if ($this->order->initiatedPayment) {
                    $this->amountDue = $this->order->initiatedPayment->amount_due / 100;
                }
            }
        }

        $this->validate([
            'tender' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value <= $this->amountDue) {
                        $fail('The ' . $attribute . ' must be greater than the amount due.');
                    }
                },
            ],
            'amountDue' => [
                'required',
                'numeric',
                'max:' . $this->order->balance / 100,
                function ($attribute, $value, $fail) {
                    if ($value >= $this->tender) {
                        $fail('The ' . $attribute . ' must be less than the tender.');
                    }
                },
            ],
            'source' => 'required',
        ]);
               

        //dd('Tender -> ' . $this->tender . '; AmountDue ->' . $this->amountDue);
        //dd('Tender -> '. $this->tender);

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
        $payment->change_amount = $change_amount * 100;
        $payment->status = 'RECEIVED';
        $payment->receiving_cashier = auth()->user()->id;
        $payment->location_id = auth()->user()->login_location_id;
        $payment->opening_balance = $this->order->balance;
        $payment->closing_balance = $closing_balance;
        $payment->initiated_by = $invoice->initiated_by;
        $payment->received_amount_datetime = Carbon::now();

        try {
            if ($payment->save()) {
                //update invoice
                $invoice->amount_due = $payment->received_amount;
                $invoice->status = "PAID";
                $invoice->payment_datetime = Carbon::now();
                $invoice->save();
                $this->recentPayment = $payment;
                $this->paymentDetailsModal = true;

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
        return view('livewire.order.show', [
            'order' => $this->order,
            'items' => $this->items,
            'payment_sources' => PaymentSource::where('slug', '!=', 'refund')->get(),
        ]);
    }
}
