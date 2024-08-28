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


            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            SKU
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Quantity
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Price Approval
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Location
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Created
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $p)
                        <tr
                            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $p->name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ empty($p->sku) ? '(NO SKU)' : $p->sku }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $p->formattedPrice() }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $p->stock_quantity }}
                            </td>

                            <td class="px-6 py-4">
                                <x-mary-badge value="{{ $p->price_approved == '1' ? 'APPROVED' : 'PENDING' }}"
                                    class="{{ $p->price_approved == '1' ? 'bg-teal-500 text-gray-900' : 'bg-red-500 text-white' }}" />
                            </td>
                            <td class="px-6 py-4">

                                <x-mary-badge value="{{ $p->location->city->name }}" class="batch-secondary" />
                                {{ $p->location->alias }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $p->created_at }}
                            </td>
                            <td class="px-6 py-4 flex space-x-2">
                                <x-mary-button icon="o-eye" class="btn-primary btn-sm text-white"
                                    onclick="modalView{{ $p->id }}.showModal()" />

                                <x-mary-modal id="modalView{{ $p->id }}" title="Product Details">
                                    <div class="flow-root">
                                        <dl class="-my-3 divide-y divide-gray-100 text-sm">


                                            <div
                                                class="grid grid-cols-1 gap-1 py-3 even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                                                <dt class="font-bold text-gray-100">Name</dt>
                                                <dd class=" sm:col-span-2">{{ $p->name }}</dd>
                                            </div>
                                            <div
                                                class="grid grid-cols-1 gap-1 py-3 dark:even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                                                <dt class="font-bold text-gray-100">Slug</dt>
                                                <dd class=" sm:col-span-2">{{ $p->slug }}</dd>
                                            </div>
                                            <div
                                                class="grid grid-cols-1 gap-1 py-3 dark:even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                                                <dt class="font-bold text-gray-100">SKU</dt>
                                                <dd class=" sm:col-span-2">{{ $p->sku }}</dd>
                                            </div>
                                            <div
                                                class="grid grid-cols-1 gap-1 py-3 dark:even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                                                <dt class="font-bold text-gray-100">Price</dt>
                                                <dd class=" sm:col-span-2">USD{{ $p->formattedPrice() }}</dd>
                                            </div>
                                            <div
                                                class="grid grid-cols-1 gap-1 py-3 dark:even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                                                <dt class="font-bold text-gray-100">Quantity</dt>
                                                <dd class=" sm:col-span-2">{{ $p->stock_quantity }}</dd>
                                            </div>


                                        </dl>
                                    </div>


                                    <x-slot:actions>
                                        {{-- Notice `onclick` is HTML --}}
                                        <x-mary-button label="Close" onclick="modalView{{ $p->id }}.close()" />
                                    </x-slot:actions>
                                </x-mary-modal>

                                @if ($p->price_approved == 0)
                                    <x-mary-button label="" icon="o-check" class="btn-success btn-sm"
                                        onclick="confirmApprove{{ $p->id }}.showModal()" />

                                    <x-mary-modal id="confirmApprove{{ $p->id }}" title="Are you sure?">
                                        This action will approve the price.

                                        <x-slot:actions>
                                            {{-- Notice `onclick` is HTML --}}
                                            <x-mary-button label="Cancel"
                                                onclick="confirmApprove{{ $p->id }}.close()" />
                                            <x-mary-button label="Confirm" class="btn-primary"
                                                wire:click="approve({{ $p->id }})" />
                                        </x-slot:actions>
                                    </x-mary-modal>
                                @else
                                    <x-mary-button label="" icon="o-x-mark" class="btn-warning btn-sm"
                                        onclick="confirmDisapprove{{ $p->id }}.showModal()" />

                                    <x-mary-modal id="confirmDisapprove{{ $p->id }}" title="Are you sure?">
                                        This action will reverse the approval on the price.

                                        <x-slot:actions>
                                            {{-- Notice `onclick` is HTML --}}
                                            <x-mary-button label="Cancel"
                                                onclick="confirmDisapprove{{ $p->id }}.close()" />
                                            <x-mary-button label="Confirm" class="btn-primary"
                                                wire:click="disapprove({{ $p->id }})" />
                                        </x-slot:actions>
                                    </x-mary-modal>
                                @endif

                                <x-mary-button label="" icon="o-trash" class="btn-error btn-sm"
                                    onclick="confirmTrash{{ $p->id }}.showModal()" />

                                <x-mary-modal id="confirmTrash{{ $p->id }}" title="Are you sure?">
                                    This action will delete the record.

                                    <x-slot:actions>
                                        {{-- Notice `onclick` is HTML --}}
                                        <x-mary-button label="Cancel"
                                            onclick="confirmTrash{{ $p->id }}.close()" />
                                        <x-mary-button label="Confirm" class="btn-primary"
                                            wire:click="trash({{ $p->id }})" />
                                    </x-slot:actions>
                                </x-mary-modal>


                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($products->count())
                {{ $products->links() }}
            @endif

        </div>

    </div>



</div>
