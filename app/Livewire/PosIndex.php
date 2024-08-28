<?php

namespace App\Livewire;

use App\Models\StockProduct;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class PosIndex extends Component
{
    use WithPagination;
    use Toast;
    public string $rejection_message = '';
    public string $method = 'CASH';

    #[Url(as: 'q')]
    public string $q;

    #[Url(as: 'page')]
    public string $page;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public function __construct()
    {
        $this->q = '';
    }

    public function updatedQ($value)
    {
        $this->page = 1;
        $this->resetPage();
    }


    public function addToCart($product_id, $qty)
    {
        $product = StockProduct::find($product_id);
        $cart_item = auth()->user()->cartItems()->where('product_id', $product->id)->first();

        if ($cart_item) {
            $new_qty = $cart_item->quantity + $qty;

            $subtotal = $new_qty * $product->price;


            try {
                $cart_item->update([
                    'quantity' => $new_qty,
                    'subtotal' => $subtotal
                ]);

                $this->dispatch('cart_item.updated');

                $this->toast(
                    type: 'success',
                    title: 'Cart updated',
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-information-circle',       // Optional (any icon)
                    css: 'alert-success',                  // Optional (daisyUI classes)
                    timeout: 10000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
            } catch (\Throwable $th) {

                $this->toast(
                    type: 'error',
                    title: $th->getMessage(),
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-information-circle',       // Optional (any icon)
                    css: 'alert-error',                  // Optional (daisyUI classes)
                    timeout: 5000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
            }
        } else {
            try {
                $subtotal = $qty * $product->price;
                auth()->user()->cartItems()->create([
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ]);

                $this->dispatch('cart_item.added');
                $this->toast(
                    type: 'success',
                    title: 'Added to cart',
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-information-circle',       // Optional (any icon)
                    css: 'alert-success',                  // Optional (daisyUI classes)
                    timeout: 10000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
            } catch (\Throwable $th) {
                $this->toast(
                    type: 'error',
                    title: $th->getMessage(),
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-information-circle',       // Optional (any icon)
                    css: 'alert-error',                  // Optional (daisyUI classes)
                    timeout: 5000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
            }
        }
    }

    public function render()
    {
        $products = StockProduct::query()
            ->orderBy(...array_values($this->sortBy))->when(filled(trim($this->q)), function ($query) {
                $query->where('name', 'LIKE', '%' . trim($this->q) . '%')
                    ->orWhere('sku', 'LIKE', '%' . trim($this->q) . '%')
                    ->orWhereHas('location', function ($query) {
                        $query->where('alias', 'LIKE', '%' . trim($this->q) . '%');
                    });
            })
            ->where('price', '>', 0)
            ->latest()
            ->paginate(6);

        return view('livewire.pos.index', [
            'products' => $products,
        ]);
    }
}
