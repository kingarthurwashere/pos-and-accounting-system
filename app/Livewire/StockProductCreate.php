<?php

namespace App\Livewire;

use App\Livewire\Forms\StockProductForm;
use App\Models\Location;
use App\Models\StockProductCategory;
use Livewire\Component;
use Mary\Traits\Toast;

class StockProductCreate extends Component
{
    use Toast;
    public StockProductForm $form;

    public function submit()
    {

        $product = $this->form->store();

        $this->dispatch('stock-product.created');

        // Toast
        $this->toast(
            type: 'success',
            title: '`' . $product->name . '` added',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-success',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );

        // // Shortcuts
        // $this->success();
        // $this->dispatch('alert', [
        //     'body' => 'Stock product `' . $product->name . '` was created'
        // ]);
    }


    public function render()
    {
        $locations = Location::get();
        $categories = StockProductCategory::get();
        return view('livewire.inventory.create', compact('locations', 'categories'));
    }
}
