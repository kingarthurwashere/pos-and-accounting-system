<?php

namespace App\Livewire\Forms;

use App\Models\StockProduct;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Form;
use Illuminate\Support\Str;
use Mary\Traits\Toast;

class StockProductForm extends Form
{
    use Toast;

    public ?StockProduct $product;

    #[Rule('required', message: ':attribute is required', as: 'Name of the product')]
    public string $name = '';

    #[Rule('required', message: ':attribute is required', as: 'Price of the product')]
    public string $price = '';

    #[Rule('required', message: ':attribute is required', as: 'Location')]
    public ?string $location_id = null;

    #[Rule('required', message: ':attribute is required', as: 'Available stock quantity')]
    public string $stock_quantity = '';

    #[Rule('nullable')]
    public ?string $sku = '';

    #[Rule('nullable')]
    public ?string $size = '';

    #[Rule('nullable')]
    public ?string $color = '';

    #[Rule('nullable')]
    public ?string $category = '';

    public function mount(?StockProduct $product = null)
    {
        $this->location_id = auth()->user()->login_location_id;
        if ($product) {
            $this->fillForm($product);
        }
    }

    public function fillForm(StockProduct $product)
    {
        // Adjust the price if necessary
        $product->price = $product->price / 100;

        // Fill the form with the product attributes
        $this->fill([
            'name' => $product->name,
            'price' => number_format($product->price / 100, 2),
            'location_id' => $product->location_id,
            'category' => $product->category,
            'stock_quantity' => $product->stock_quantity,
            'sku' => $product->sku,
            'size' => $product->size,
            'color' => $product->color,
        ]);
    }

    public function update($id)
    {
        $this->validate();

        $update_data = $this->only('name', 'price', 'location_id', 'category', 'stock_quantity', 'sku', 'size', 'color');
        $update_data['slug'] = Str::slug($this->name);
        $update_data['sku'] = $this->sku === '' ? null : $this->sku;

        StockProduct::find($id)->update(
            $update_data
        );
    }

    public function store()
    {
        $this->validate();

        $data = $this->only('name', 'price', 'location_id', 'stock_quantity', 'sku', 'category', 'size', 'color');
        $data['initial_stock_taker'] = auth()->user()->id;
        $data['slug'] = Str::slug($this->name);
        $data['sku'] = $this->sku === '' ? null : $this->sku;

        $product = auth()->user()->uploadedStockProducts()->create($data);

        $this->reset();

        return $product;
    }
}
