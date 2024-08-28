<?php

namespace App\Livewire\Forms;

use App\Models\Order;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class OrderCustomerForm extends Form
{
    #[Rule('required')]
    public ?string $customerName;
    #[Rule('required')]
    public ?string $customerEmail;
    #[Rule('required')]
    public ?string $customerPhone;

    public function update(Order $order)
    {
        $order->update([
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'customer_phone' => $this->customerPhone,
        ]);
    }
}
