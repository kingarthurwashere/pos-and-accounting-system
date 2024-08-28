<?php

namespace App\Livewire;

use App\Livewire\Forms\StockProductForm;
use App\Models\Location;
use App\Models\StockProduct;
use App\Models\StockProductCategory;
use Livewire\Component;
use Mary\Traits\Toast;

class StockProductEdit extends Component
{
    use Toast;
    public StockProductForm $form;
    public StockProduct $product;
    public $locations;
    public $categories;
    public $name;

    public function mount()
    {
        if ($this->product) {
            $this->form->fillForm($this->product);
        }
    }

    public function submit()
    {
        $this->form->update($this->product->id);
        //$this->dispatch('stock-product.updated');

        // Toast
        $this->toast(
            type: 'success',
            title: '`' . $this->form->name . '` updated',
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
        return view('livewire.inventory.edit');
    }
}
