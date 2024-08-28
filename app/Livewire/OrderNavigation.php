<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderNavigation extends Component
{
    public Order $order;
    public function render()
    {
        $order = $this->order;
        return view('livewire.order.order-navigation', compact('order'));
    }
}
