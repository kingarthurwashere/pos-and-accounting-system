<?php

namespace App\Livewire;

use App\Livewire\Traits\OrderLiveUtils;
use App\Models\Order;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class OrderApprovedRefunds extends Component
{
    use WithPagination;
    use Toast;

    #[Url(as: 'q')]
    public string $q;

    #[Url(as: 'page')]
    public string $page;

    public function __construct()
    {
        $this->q = '';
        $this->page = '';
    }

    public function updatedQ($value)
    {
        $this->resetPage();
    }

    public function render()
    {

        $orders = Order::when(filled(trim($this->q)), function ($query) {
            $query->where('customer_name', 'LIKE', '%' . trim($this->q) . '%')
                ->orWhere('customer_email', 'LIKE', '%' . trim($this->q) . '%')
                ->orWhere('customer_phone', 'LIKE', '%' . trim($this->q) . '%')
                ->orWhere('order_id', 'LIKE', '%' . trim($this->q) . '%')
                ->orWhere('total', 'LIKE', '%' . trim(((float)$this->q * 100)) . '%')
                ->orWhereHas('items', function ($query) {
                    $query->where('name', 'LIKE', '%' . trim($this->q) . '%');
                });
        })->whereHas('payments', function ($query) {
            $query->whereColumn('payable_identifier', 'orders.id')
                ->where('status', 'RECEIVED')
                ->whereHas('refundApproved');
        })
            ->latest()
            ->paginate(6);
        return view('livewire.order.approved-refunds', [
            'orders' => $orders,
        ]);
    }
}
