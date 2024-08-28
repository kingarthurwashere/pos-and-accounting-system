<?php

namespace App\Livewire;

use App\Enums\OrderSource;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\StockProduct;
use Livewire\Component;
use Illuminate\Support\Str;
use Mary\Traits\Toast;

class CartPreview extends Component
{
    public bool $showDrawer = false;
    use Toast;

    protected $listeners = [
        'cart_item.added' => '$refresh',
        'cart_item.updated' => '$refresh',
        'cart.cleared' => '$refresh'
    ];

    public function handleQuantityChange(int $qty, int $index)
    {
        try {
            $cart = Cart::find($index);

            if (is_int($qty) && $qty >= 1 && $cart) {

                $cart->update([
                    'quantity' => $qty,
                    'subtotal' => $cart->product->price * $qty,
                ]);
            }
        } catch (\Throwable $th) {
            $this->toast(
                type: 'warning',
                title: 'Please enter a valid quantity',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-warning',                  // Optional (daisyUI classes)
                timeout: 2000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        }
    }

    public function clear()
    {
        auth()->user()->cartItems->each(function ($i) {
            $i->delete();
        });

        $this->dispatch('cart.cleared');

        $this->showDrawer = false;
    }

    public function confirmOrder()
    {
        $total = auth()->user()->cartItems->sum('subtotal');

        $order = auth()->user()->createdOrders()->create([
            'total' => $total,
            'balance' => $total,
            'source' => OrderSource::INVENTORY,
        ]);

        auth()->user()->cartItems->each(function ($item) use ($order) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'slug' => Str::slug($item->product->name),
                'price' => $item->price,
                'quantity' => $item->quantity,
                'total' => $item->subtotal,
                'sku' => $item->sku,
                'is_inventory' => 1,
            ]);
        });

        $this->clear();
        $this->dispatch('cart.cleared');

        //$this->showDrawer = false;

        $this->toast(
            type: 'success',
            title: 'Order Created',
            description: null,
            position: 'toast-top toast-end',
            icon: 'o-information-circle',
            css: 'alert-success',
            timeout: 10000,
            redirectTo: null
        );

        redirect()->to("/orders/{$order->id}");
    }


    public function trash(int $id)
    {
        $product = Cart::find($id);

        try {
            if ($product->delete()) {

                $this->toast(
                    type: 'success',
                    title: 'Removed from cart',
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-information-circle',       // Optional (any icon)
                    css: 'alert-success',                  // Optional (daisyUI classes)
                    timeout: 10000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
            } else {
                $this->toast(
                    type: 'error',
                    title: 'Removal failed',
                    description: null,                  // optional (text)
                    position: 'toast-top toast-end',    // optional (daisyUI classes)
                    icon: 'o-information-circle',       // Optional (any icon)
                    css: 'alert-error',                  // Optional (daisyUI classes)
                    timeout: 5000,                      // optional (ms)
                    redirectTo: null                    // optional (uri)
                );
            }
        } catch (\Throwable $th) {
            $this->toast(
                type: 'error',
                title: 'Something went wrong',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-error',                  // Optional (daisyUI classes)
                timeout: 5000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        }
    }

    public function render()
    {
        $items = auth()->user()->cartItems;

        // Calculate the total items based on quantity
        $total_items = $items->sum('quantity');
        $total_price = $items->sum('subtotal') == 0 ? '0.00' : number_format($items->sum('subtotal') / 100, 2);
        $words = Str::plural('item', $total_items);

        return view('livewire.pos.cart-preview', [
            'items' => $items,
            'total_items' => $total_items,
            'total_price' => $total_price,
            'words' => $words
        ]);
    }
}
