<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderStatusInNavigation extends Component
{
    public Order $order;

    protected $listeners = [
        'payment.received' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.order-status-in-navigation', [
            'order' => $this->order,
        ]);
    }
}
