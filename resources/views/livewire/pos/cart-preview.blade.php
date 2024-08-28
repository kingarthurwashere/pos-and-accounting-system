<div class="inline-flex rounded-md shadow-sm" role="group">
    <button type="button" wire:click="$toggle('showDrawer')" @class([
        'inline-flex items-center px-4 py-2 text-sm font-medium rounded-s-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:bg-gray-50',
        'bg-white border border-gray-200 dark:hover:text-white dark:hover:bg-gray-700 dark:bg-gray-50' => !request()->routeIs(
            'inventory.create'),
        'text-white dark:bg-gray-900 dark:bg-slate-900 border border-green-500 focus:ring-green-500 focus:text-white' => request()->routeIs(
            'inventory.create'),
    ])>
        {{ $total_items }} {{ $words }}
    </button>

    <button type="button" wire:click="$toggle('showDrawer')" @class([
        'inline-flex items-center px-4 py-2 text-sm font-medium rounded-e-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white',
        'bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700' => !request()->routeIs(
            'inventory'),
        'text-white dark:bg-gray-900 dark:bg-slate-900 border border-green-500 focus:text-white' => request()->routeIs(
            'inventory'),
    ])>
        ${{ $total_price }}
    </button>

    <style>
        .card {
            padding: 0;
        }
    </style>

    {{-- Notice `wire:model` --}}
    <x-mary-drawer wire:model="showDrawer" class="lg:w-1/3 dark:bg-gray-800 dark:border-gray-700">

        <div
            class="w-full bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Cart Items</h5>
                {{-- <a href="#" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                    Close
                </a> --}}
            </div>
            <div class="flow-root">
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($items as $i)
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-0 ms-4">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        {{ $i->product->name }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400 text-lg">
                                        ${{ $i->product->formattedPrice() }} x {{ $i->quantity }}
                                    </p>
                                </div>
                                <div class="">
                                    <div
                                        class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        ${{ number_format(($i->product->price * $i->quantity) / 100, 2) }}
                                    </div>
                                    <div
                                        class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">

                                        <div class="flex flex-1 items-center justify-end gap-2">

                                            <label for="Line1Qty" class="sr-only"> Quantity </label>

                                            <input type="number" min="1" value="{{ $i->quantity }}"
                                                id="input-{{ $i->id }}"
                                                wire:model.blur="items.{{ $i->id }}.value"
                                                wire:change="handleQuantityChange($event.target.value, '{{ $i->id }}')"
                                                class="h-8 w-12 rounded border-gray-200 bg-gray-50 p-0 text-center text-xs text-gray-600 [-moz-appearance:_textfield] focus:outline-none [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none" />


                                            <x-mary-button label="" icon="o-trash" class="btn-error btn-sm"
                                                onclick="confirmTrash{{ $i->id }}.showModal()" />

                                            <x-mary-modal id="confirmTrash{{ $i->id }}" title="Are you sure?">
                                                This action will remove item from cart.

                                                <x-slot:actions>
                                                    {{-- Notice `onclick` is HTML --}}
                                                    <x-mary-button label="Cancel"
                                                        onclick="confirmTrash{{ $i->id }}.close()" />
                                                    <x-mary-button label="Confirm" class="btn-primary"
                                                        wire:click="trash({{ $i->id }})" />
                                                </x-slot:actions>
                                            </x-mary-modal>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                @if ($items->isEmpty())
                    <h2>Cart has no items</h2>
                @endif
            </div>
        </div>

        <div>
            <h2 class="text-center text-3xl mt-4 text-green-200">${{ $total_price }}</h2>
        </div>


       
        @if($items->count() > 0)
        <x-mary-button label="Clear" icon="o-no-symbol" wire:click="clear()" spinner="clear()"
            class="btn btn-error mt-8 ml-8 text-white" />
        @endif
        <x-mary-button label="Close" icon="o-x-mark" wire:click="$toggle('showDrawer')" class="btn-info mt-8 ml-8"  />
        @if($items->count() > 0)
        <x-mary-button label="Confirm Order" icon="o-check" wire:click="confirmOrder()" spinner="confirmOrder()"
            class="btn btn-outline mt-8 ml-8 text-white" />
        @endif
    </x-mary-drawer>



</div>
