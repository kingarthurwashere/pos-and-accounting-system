<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg space-y-4">


            <form class="mx-auto">
                <label for="default-search"
                    class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" id="default-search" wire:model.live.debounce.300ms="q"
                        class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search..." required />

                </div>
            </form>




            <div class="space-y-4">
                @php
                    $headers = [
                        ['key' => 'id', 'label' => '#'],
                        ['key' => 'name', 'label' => 'Name', 'sortable' => true],
                        ['key' => 'price', 'label' => 'Price'],
                        ['key' => 'category_original_name', 'label' => 'Category'],
                        ['key' => 'sku', 'label' => 'SKU'],
                    ];
                @endphp
                @if ($products->count())
                    <x-mary-table :headers="$headers" :rows="$products" :sort-by="$sortBy" striped>
                        {{-- @scope('actions', $product)
                            <x-badge :value="$product->name" class="badge-primary" />
                        @endscope --}}

                        @scope('cell_price', $product)
                            {{ $product->formattedPrice() }}
                        @endscope
                        @scope('actions', $product)
                            @can('add to cart')
                                <x-mary-button icon="o-plus" 
                                wire:click="addToCart({{ $product->id }}, '1')" 
                                type="submit" 
                                class="btn-warning"
                                spinner="addToCart({{ $product->id }}, '1')"
                                  />
                          
                            @endcan
                        @endscope

                    </x-mary-table>

                    {{ $products->links() }}
                @endif
            </div>

        </div>

    </div>



</div>
