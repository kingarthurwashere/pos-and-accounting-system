<?php

namespace App\Livewire;

use App\Models\StockProduct;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class StockProductPending extends Component
{
    use WithPagination;
    use Toast;
    public string $rejection_message = '';
    public string $method = 'CASH';

    #[Url(as: 'q')]
    public string $q;

    #[Url(as: 'page')]
    public string $page;

    public function __construct()
    {
        $this->q = '';
    }

    public function updatedQ($value)
    {
        $this->page = 1;
        $this->resetPage();
    }

    public function trash(int $id)
    {
        $product = StockProduct::find($id);

        try {
            if ($product->delete()) {

                $this->toast(
                    type: 'success',
                    title: 'Product deleted successfully',
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
                    title: 'Deletion failed',
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

    public function approve(int $id)
    {
        $product = StockProduct::find($id);
        $product->price_approved = 1;
        $product->price_approved_by = auth()->user()->id;
        $product->approved_at = Carbon::now();

        try {
            if ($product->save()) {

                $this->toast(
                    type: 'success',
                    title: 'Price approved successfully',
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
                    title: 'Price approval failed',
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
                title: 'Price approval failed',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-error',                  // Optional (daisyUI classes)
                timeout: 5000,                      // optional (ms)
                redirectTo: null                    // optional (uri)
            );
        }
    }

    public function disapprove(int $id)
    {
        $product = StockProduct::find($id);
        $product->price_approved = 0;
        $product->price_approved_by = auth()->user()->id;
        $product->approved_at = Carbon::now();

        try {
            if ($product->save()) {

                $this->toast(
                    type: 'success',
                    title: 'Price disapproved successfully',
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
                    title: 'Price disapproval failed',
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
        $products = StockProduct::when(filled(trim($this->q)), function ($query) {
            $query->where('name', 'LIKE', '%' . trim($this->q) . '%')
                ->orWhere('sku', 'LIKE', '%' . trim($this->q) . '%')
                ->orWhereHas('location', function ($query) {
                    $query->where('alias', 'LIKE', '%' . trim($this->q) . '%');
                });
        })->where('price_approved', 0)
            ->latest()
            ->paginate(6);

        return view('livewire.inventory.pending', [
            'products' => $products,
        ]);
    }
}
