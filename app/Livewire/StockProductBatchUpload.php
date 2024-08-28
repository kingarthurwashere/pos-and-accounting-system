<?php

namespace App\Livewire;

use App\Imports\InventoryImport;
use App\Livewire\Forms\StockProductForm;
use App\Models\Location;
use App\Models\StockProduct;
use App\Models\StockProductCategory;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Mary\Traits\Toast;
use Illuminate\Support\Str;

class StockProductBatchUpload extends Component
{
    use Toast, WithFileUploads;
    public StockProductForm $form;

    #[Rule('required')]
    public $file;
    public string $uploadedFileName;
    public bool $ready = false;
    public $previewItems = [];

    public function submit()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        $this->uploadedFileName = $this->file->store('inventory');


        $data = Excel::toCollection(new InventoryImport, $this->uploadedFileName);
        $data = $data->first();
        $headers = $data->shift();

        //dd($headers);

        $preview_items = $data->take(5)->all();

        // dd($preview_items);

        if ($headers->contains('SKU Number')) {
            $this->ready = true;
            $this->previewItems = $preview_items;

            $this->toast(
                type: 'info',
                title: 'Provided data is valid',
                description: null,
                position: 'toast-top toast-end',
                icon: 'o-information-circle',
                css: 'alert-info',
                timeout: 2000,
                redirectTo: null
            );
            // Excel::import(new InventoryImport, $this->uploadedFileName);
        } else {
            $this->ready = false;
            $this->toast(
                type: 'error',
                title: 'Invalid Data',
                description: null,
                position: 'toast-top toast-end',
                icon: 'o-information-circle',
                css: 'alert-error',
                timeout: 3000,
                redirectTo: null
            );
        }

        $this->file = null;
    }



    public function process()
    {
        $this->toast(
            type: 'info',
            title: 'Process Started ...',
            description: null,
            position: 'toast-top toast-end',
            icon: 'o-information-circle',
            css: 'alert-info',
            timeout: 2000,
            redirectTo: null
        );
        try {
            Excel::import(new InventoryImport, $this->uploadedFileName);
            $this->toast(
                type: 'success',
                title: 'Processed successfully',
                description: null,
                position: 'toast-top toast-end',
                icon: 'o-information-circle',
                css: 'alert-success',
                timeout: 3000,
                redirectTo: null
            );

            //Seed missing categories
            $categories = StockProduct::whereNotNull('category_original_name')->distinct('category_original_name')->pluck('category_original_name');
            Log::info('STOCK CATEGORIES');
            Log::info($categories);
            $this->seedMissingCategories($categories);

            $this->reset();
        } catch (\Throwable $th) {
            $this->toast(
                type: 'error',
                title: 'Something went wrong',
                description: null,
                position: 'toast-top toast-end',
                icon: 'o-information-circle',
                css: 'alert-error',
                timeout: 3000,
                redirectTo: null
            );
        }
    }

    private function seedMissingCategories($categories)
    {
        foreach ($categories as $c) {
            $exists = StockProductCategory::where('name', $c)->first();

            if (!$exists) {
                StockProductCategory::create([
                    'name' => $c,
                    'slug' => Str::slug($c),
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.inventory.batch-upload', [
            'previewItems' => $this->previewItems
        ]);
    }
}
