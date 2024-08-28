<?php

namespace App\Livewire;

use App\Livewire\Forms\RefundRequestForm;
use App\Models\Order;
use App\Models\Payment;
use App\Models\RefundMethod;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class PaymentIndex extends Component
{
    use WithPagination;
    use Toast;

    public RefundRequestForm $refundRequestForm;

    protected $listeners = [
        'refund.changed' => '$refresh',
    ];

    #[Url(as: 'q')]
    public string $q;

    public function __construct()
    {
        $this->q = '';
    }

    public function updatedQ($value)
    {
        $this->resetPage();
    }

    public function render()
    {
        $payments = Payment::when(filled($this->q), function ($query) {
            $searchTermInCents = is_numeric($this->q) ? $this->q * 100 : null;
            $query->where('status', 'LIKE', '%' . $this->q . '%')
                ->orWhere('payable_slug', 'LIKE', '%' . $this->q . '%')
                ->orWhere('source', 'LIKE', '%' . $this->q . '%');
            if ($searchTermInCents !== null) {
                $query->orWhere('received_amount', '=', $searchTermInCents);
            }
        })
            ->latest()
            ->paginate(6);
        return view('livewire.payment.index', [
            'items' => $payments,
            'methods' => RefundMethod::get(),
        ]);
    }
}
