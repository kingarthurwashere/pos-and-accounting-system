<div class="max-w-7xl mx-auto bg-slate-900">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg space-y-4">

        {{-- <form class="mx-auto">
            <label for="default-search"
                class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Searchxx</label>
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
        </form> --}}

        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Online Order
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        POS
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Source
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Customer
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
                @forelse ($orders as $o)
                    <tr key="or{{$o->id}}"
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $o->order_id == null ? '(N/A)' : $o->order_id }}
                        </th>
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-green-200">
                            {{ $o->formattedTotal() }}
                        </th>
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                            @if ($o->balance == '0')
                                <x-mary-badge value="PAID" class="badge-success font-bold" />
                            @endif

                            @if ($o->balance === $o->total)
                                <x-mary-badge value="NOT PAID" class="bg-red-800 font-bold" />
                            @endif

                            @if ($o->balance > 0 && $o->balance < $o->total)
                                <x-mary-badge value="PARTIALLY PAID" class="badge-info font-bold" />
                            @endif
                        </th>
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">

                            @if ($o->initiatedPayment)
                                <x-mary-badge value="INITIATED" class="badge-success font-bold" />
                            @else
                                @if ($o->balance > 0)
                                    <x-mary-badge value="IDLE" class="badge-secondary font-bold" />
                                @else
                                    <x-mary-badge value="N/A" class="font-bold" />
                                @endif
                            @endif
                        </th>
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $o->source }}
                        </th>
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $o->customer_name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $o->created_at }}
                        </td>
                        <td class="px-6 py-4 flex space-x-2">
                            <!----Order---->
                            <a href="{{ route('order.payments', $o->id) }}" wire:navigate>
                                <x-mary-button label="Details" class="btn-info btn-sm text-white" /></a>

                            <!----Customer---->
                            {{-- <x-mary-button label="Customer" class="btn-primary btn-sm text-white"
                                onclick="customerDetailsView{{ $o->id }}.showModal()" />

                            <x-mary-modal id="customerDetailsView{{ $o->id }}" title="Customer Details">
                                <div class="flow-root">
                                    <dl class="-my-3 divide-y divide-gray-100 text-sm">
                                        <div
                                            class="grid grid-cols-1 gap-1 py-3 even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                                            <dt class="font-bold text-gray-100">Name</dt>
                                            <dd class=" sm:col-span-2">{{ $o->customer_name }}</dd>
                                        </div>
                                        <div
                                            class="grid grid-cols-1 gap-1 py-3 even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                                            <dt class="font-bold text-gray-100">Email</dt>
                                            <dd class=" sm:col-span-2">{{ $o->customer_email }}</dd>
                                        </div>
                                        <div
                                            class="grid grid-cols-1 gap-1 py-3 even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                                            <dt class="font-bold text-gray-100">Phone Number</dt>
                                            <dd class=" sm:col-span-2">{{ $o->customer_phone }}</dd>
                                        </div>
                                    </dl>
                                </div>

                                <x-slot:actions>
                                    <x-mary-button label="Close"
                                        onclick="customerDetailsView{{ $o->id }}.close()" />
                                </x-slot:actions>
                            </x-mary-modal> --}}


                        </td>
                    </tr>
                @empty
                <tr>
                    <td class="pl-6 py-4 text-gray-900 dark:text-white">
                        No items found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if ($orders->count())
            {{ $orders->links() }}
        @endif
    </div>
</div>
