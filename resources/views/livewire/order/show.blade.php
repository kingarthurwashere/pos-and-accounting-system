<?php

use Illuminate\Support\Str;
?>
{{-- @props(['order', 'items', 'name']) --}}
<x-slot name="header">
    <div class="flex h-6 space-between w-full items-center">
        <div class="w-full">
            <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hidden sm:flex ml-0">
                {{ $order->source == 'INVENTORY' ? 'INVENTORY ORDER #' . $order->id : 'ONLINE ORDER #' . $order->order_id }}

                @if ($order->source === 'ONLINE' && $online_order['express_delivery'])
                    <x-mary-badge class="bg-success" value="EXPRESS DELIVERY"></x-mary-badge>
                @endif
            </h3>
        </div>

        <div class="w-full flex justify-end">
            <livewire:order-status-in-navigation :order="$order" />
        </div>
    </div>
</x-slot>

<div class="pt-2 pb-12 w-full">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        <livewire:order-navigation :order="$order" />

        @if ($order->initiatedPayment)
            <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-bold">Invoice Generated!</span> Payment to be received
                </div>
            </div>
        @endif


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg space-y-4">
            <div class="space-y-4 xl:space-y-0 xl:flex xl:justify-between xl:space-x-4">
                <x-mary-card class="flow-root flex-grow">
                    <div class="flex space-between w-full items-center mb-4">
                        <span class="flex-grow font-bold">Customer</span>
                        <span>
                            <x-mary-button icon="o-pencil" class="btn-info" wire:click="$toggle('editCustomerModal')" />

                            <x-modal wire:model="editCustomerModal" title="Update Dustonmer Details" subtitle=""
                                separator>

                                <x-mary-form wire:submit="saveCustomerDetails" class="p-4">
                                    {{-- Full error bag --}}
                                    {{-- All attributes are optional, remove it and give a try --}}
                                    <x-mary-errors title="Oops!" description="Please, fix them." icon="o-face-frown" />

                                    <x-mary-input label="Name" value="{{ $order->customer_name }}"
                                        wire:model="orderCustomerForm.customerName" />
                                    <x-mary-input label="E-mail" value="{{ $order->customer_email }}" type="email"
                                        wire:model="orderCustomerForm.customerEmail" />
                                    <x-mary-input label="Phone" value="{{ $order->customer_phone }}"
                                        wire:model="orderCustomerForm.customerPhone" />

                                    <x-slot:actions>
                                        <x-mary-button label="UPDATE" class="btn-success" type="submit"
                                            spinner="saveCustomerDetails" />
                                    </x-slot:actions>
                                </x-mary-form>
                            </x-modal>
                        </span>
                    </div>
                    <dl class="-my-3 divide-y divide-gray-100 text-sm">
                        <div class="grid grid-cols-1 gap-1 py-3 even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                            <dt class="font-bold text-gray-100">Name</dt>
                            <dd class=" sm:col-span-2">{{ $order->customer_name }}</dd>
                        </div>
                        <div class="grid grid-cols-1 gap-1 py-3 even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                            <dt class="font-bold text-gray-100">Email</dt>
                            <dd class=" sm:col-span-2">{{ $order->customer_email }}</dd>
                        </div>
                        <div class="grid grid-cols-1 gap-1 py-3 even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                            <dt class="font-bold text-gray-100">Phone Number</dt>
                            <dd class=" sm:col-span-2">{{ $order->customer_phone }}</dd>
                        </div>
                    </dl>
                </x-mary-card>
                @if ($order->agent)
                    <x-mary-card class="flow-root flex-grow" title="Agent Details">
                        <dl class="-my-3 divide-y divide-gray-100 text-sm">
                            <div class="grid grid-cols-1 gap-1 py-3 even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                                <dt class="font-bold text-gray-100">Name</dt>
                                <dd class=" sm:col-span-2">{{ $order->agent->name }}</dd>
                            </div>
                            <div class="grid grid-cols-1 gap-1 py-3 even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                                <dt class="font-bold text-gray-100">Email</dt>
                                <dd class=" sm:col-span-2">{{ $order->agent->email }}</dd>
                            </div>
                            <div class="grid grid-cols-1 gap-1 py-3 even:bg-gray-800 sm:grid-cols-3 sm:gap-4">
                                <dt class="font-bold text-gray-100">Phone Number</dt>
                                <dd class=" sm:col-span-2">{{ $order->agent->phone }}</dd>
                            </div>
                        </dl>
                    </x-mary-card>
                @endif
            </div>

            @if ($order->source === 'INVENTORY')
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Price
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Total
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Product ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                SKU
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Type
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $i)
                            <tr key="py{{ $i->id }}"
                                class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $i->id }}
                                </th>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ Str::limit($i->name, 20) }}
                                </th>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $i->formattedPrice() }}
                                </th>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-teal-900 whitespace-nowrap dark:text-green-200">
                                    {{ $i->quantity }}
                                </th>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-green-200">
                                    {{ $i->formattedTotal() }}
                                </th>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $i->product_id == null ? '(N/A)' : $i->product_id }}
                                </th>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $i->sku == null ? '(N/A)' : $i->sku }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $i->is_inventory == '0' ? 'ONLINE' : 'INVENTORY' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if ($order->source === 'ONLINE')
                <div
                    class="w-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">

                    <div class="flow-root">
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">

                            @foreach ($online_order['ordered_items'] as $i)
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <img class="w-8 h-8 rounded-full" src="{{ $i['details']['image'] }}"
                                                alt="Neil image">
                                        </div>
                                        <div class="flex-1 min-w-0 ms-4">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                {{ Str::limit($i['details']['name'], 100) }}
                                            </p>
                                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                {{ $i['details']['quantity'] }} X {{ $i['details']['price'] }}
                                            </p>
                                        </div>
                                        <div
                                            class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            ${{ $i['details']['subtotal'] / 100 }}
                                        </div>
                                    </div>
                                </li>

                                <div class="space-y-2 mb-4">
                                    
                                    <details
                                        class="overflow-hidden rounded border border-gray-300 dark:border-gray-600 [&_summary::-webkit-details-marker]:hidden">
                                        <summary
                                            class="flex cursor-pointer items-center justify-between gap-2 bg-white p-4 text-gray-900 transition dark:bg-gray-900 dark:text-white">
                                            <span class="text-sm font-medium"> More Details </span>

                                            <span class="transition group-open:-rotate-180">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </span>
                                        </summary>

                                        <div
                                            class="border-t border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">

                                            <div class="px-4 my-4">
                                                <ol class="items-center sm:flex">
                                                    @if(in_array($i['status_id'], [2, 4, 5, 6, 7]) )
                                                        <li class="relative mb-6 sm:mb-0">
                                                            <div class="flex items-center">
                                                                <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                                                                    @if(in_array($i['status_id'], [2, 4, 5, 6, 7]) )
                                                                        <x-mary-icon name="o-check" class="text-cyan-500" />
                                                                    @else
                                                                        <x-mary-icon name="o-clock" class="text-cyan-500" />
                                                                    @endif
                                                                </div>
                                                                <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div>
                                                            </div>
                                                            <div class="mt-3 sm:pe-8">
                                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Available</h3>
                                                                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$i['new_at']}}</time>
                                                                <p class="text-base font-normal text-gray-500 dark:text-gray-400"></p>
                                                            </div>
                                                        </li>
                                                    @endif
                                                   

                                                    @if($i['status_id'] === 3)
                                                    <li class="relative mb-6 sm:mb-0">
                                                        <div class="flex items-center">
                                                            <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                                                                <x-mary-icon name="o-check" class="text-cyan-500" />
                                                            </div>
                                                            {{-- <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div> --}}
                                                        </div>
                                                        <div class="mt-3 sm:pe-8">
                                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Unavailable</h3>
                                                          
                                                            <p class="text-base font-normal text-gray-500 dark:text-gray-400"></p>
                                                        </div>
                                                    </li>
                                                    @endif

                                                    <li class="relative mb-6 sm:mb-0">
                                                        <div class="flex items-center">
                                                            <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                                                                @if(in_array($i['status_id'], [4, 5, 6, 7]) )
                                                                    <x-mary-icon name="o-check" class="text-cyan-500" />
                                                                @endif
                                                            </div>
                                                            <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div>
                                                        </div>
                                                        <div class="mt-3 sm:pe-8">
                                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Purchased</h3>
                                                                <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$i['purchased_at']}}</time>
                                                        
                                                            <p class="text-base font-normal text-gray-500 dark:text-gray-400"></p>
                                                        </div>
                                                    </li>

                                                    <li class="relative mb-6 sm:mb-0">
                                                        <div class="flex items-center">
                                                            <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                                                                @if(in_array($i['status_id'], [5, 6, 7]) && ($i['shipped_at'] !== '(Not Set)') )
                                                                    <x-mary-icon name="o-check" class="text-cyan-500" />
                                                                @endif
                                                            </div>
                                                            <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div>
                                                        </div>
                                                        <div class="mt-3 sm:pe-8">
                                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Checked</h3>
                                                                <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$i['checked_at']}}</time>
                                                           
                                                            <p class="text-base font-normal text-gray-500 dark:text-gray-400"></p>
                                                        </div>
                                                    </li>

                                                    <li class="relative mb-6 sm:mb-0">
                                                        <div class="flex items-center">
                                                            <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                                                                @if(in_array($i['status_id'], [6, 7]) && ($i['shipped_at'] !== '(Not Set)') )
                                                                    <x-mary-icon name="o-check" class="text-cyan-500" />
                                                                @endif
                                                            </div>
                                                            <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div>
                                                        </div>
                                                        <div class="mt-3 sm:pe-8">
                                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Packed</h3>
                                                                <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$i['packed_at']}}</time>
                                                      
                                                            <p class="text-base font-normal text-gray-500 dark:text-gray-400"></p>
                                                        </div>
                                                    </li>

                                                    <li class="relative mb-6 sm:mb-0">
                                                        <div class="flex items-center">
                                                            <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                                                                @if(in_array($i['status_id'], [7]) && ($i['shipped_at'] !== '(Not Set)'))
                                                                    <x-mary-icon name="o-check" class="text-cyan-500" />
                                                                @endif
                                                            </div>
                                                            <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div>
                                                        </div>
                                                        <div class="mt-3 sm:pe-8">
                                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Shipped</h3>
                                                                <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{$i['shipped_at']}}</time>
                                                       
                                                            <p class="text-base font-normal text-gray-500 dark:text-gray-400"></p>
                                                        </div>
                                                    </li>

                                                    {{-- @if(in_array($i['status_id'], [8, 9]) )
                                                    <li class="relative mb-6 sm:mb-0">
                                                        <div class="flex items-center">
                                                            <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                                                                <x-mary-icon name="o-check" class="text-cyan-500" />
                                                            </div>
                                                        </div>
                                                        <div class="mt-3 sm:pe-8">
                                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Arrived</h3>
                                                            <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">December 2, 2021</time>
                                                            <p class="text-base font-normal text-gray-500 dark:text-gray-400"></p>
                                                        </div>
                                                    </li>
                                                    @endif --}}

                                                  
                                                    {{-- @if(in_array($i['status_id'], [8, 9]) )
                                                    <li class="relative mb-6 sm:mb-0">
                                                        <div class="flex items-center">
                                                            <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                                                                <x-mary-icon name="o-check" class="text-cyan-500" />
                                                            </div>
                                                        </div>
                                                        <div class="mt-3 sm:pe-8">
                                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Arrived</h3>
                                                            <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">December 2, 2021</time>
                                                            <p class="text-base font-normal text-gray-500 dark:text-gray-400"></p>
                                                        </div>
                                                    </li>
                                                    @endif --}}
                                                </ol>
                                            </div>

                                            <header class="flex items-center justify-between p-4">

                                                <div class="flow-root w-full">
                                                    <div class="flex space-between space-x-4">
                                                        <div class="flex-shrink-0 hidden lg:flex">
                                                            <img class=" w-80 rounded-md"
                                                                src="{{ $i['details']['image'] }}" alt="Neil image">
                                                        </div>
                                                        <dl
                                                            class="-my-3 flex-grow divide-y divide-gray-100 text-sm dark:divide-gray-700">
                                                            <div
                                                                class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                                                                <dt class="font-medium text-gray-900 dark:text-white">
                                                                    Buyer
                                                                </dt>
                                                                <dd
                                                                    class="text-gray-700 sm:col-span-2 dark:text-gray-200">
                                                                    {{ $i['buyer_details']['full_name'] }}</dd>
                                                            </div>

                                                            <div
                                                                class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                                                                <dt class="font-medium text-gray-900 dark:text-white">
                                                                    
                                                                    Price</dt>
                                                                <dd
                                                                    class="text-gray-700 sm:col-span-2 dark:text-gray-200">
                                                                    USD{{ $i['details']['usd_price'] }}</dd>
                                                            </div>

                                                            <div
                                                                class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                                                                <dt class="font-medium text-gray-900 dark:text-white">
                                                                    Last
                                                                    Payment Date</dt>
                                                                <dd
                                                                    class="text-gray-700 sm:col-span-2 dark:text-gray-200">
                                                                    {{ $i['details']['last_payment_date'] }}</dd>
                                                            </div>
                                                            <div
                                                                class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                                                                <dt class="font-medium text-gray-900 dark:text-white">
                                                                    Extra
                                                                    Battery Incl.</dt>
                                                                <dd
                                                                    class="text-gray-700 sm:col-span-2 dark:text-gray-200">
                                                                    {{ $i['has_extra_battery'] ? 'Yes' : 'No' }}</dd>
                                                            </div>

                                                            <div
                                                                class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                                                                <dt class="font-medium text-gray-900 dark:text-white">
                                                                    SKU
                                                                </dt>
                                                                <dd
                                                                    class="text-gray-700 sm:col-span-2 dark:text-gray-200">
                                                                    {{ $i['details']['sku'] == '' || $i['details']['sku'] == null ? '(N/A)' : $i['details']['sku'] }}</dd>
                                                            </div>

                                                            <div
                                                                class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                                                                <dt class="font-medium text-gray-900 dark:text-white">
                                                                    View
                                                                    Online</dt>
                                                                <dd
                                                                    class="text-gray-700 sm:col-span-2 dark:text-gray-200">
                                                                    <a href="{{ $i['details']['item_link'] }}"
                                                                        class="text-green-300 font-bold"
                                                                        target="_blank">Link</a>
                                                                </dd>
                                                            </div>

                                                            <div
                                                                class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                                                                <dt class="font-medium text-gray-900 dark:text-white">
                                                                    Available For Purchase</dt>
                                                                <dd
                                                                    class="text-gray-700 sm:col-span-2 dark:text-gray-200">
                                                                    {{ $i['new_at'] === '(Not Set)' ? '(To Be Actioned)' : $i['new_at'] }}
                                                                </dd>
                                                            </div>

                                                            <div
                                                                class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                                                                <dt class="font-medium text-gray-900 dark:text-white">
                                                                    Shipped</dt>
                                                                <dd
                                                                    class="text-gray-700 sm:col-span-2 dark:text-gray-200">
                                                                    {{ $i['shipped_at'] === '(Not Set)' ? 'Not Yet' : $i['shipped_at'] }}
                                                                </dd>
                                                            </div>
                                                            <div
                                                                class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                                                                <dt class="font-medium text-gray-900 dark:text-white">
                                                                    Purchased</dt>
                                                                <dd
                                                                    class="text-gray-700 sm:col-span-2 dark:text-gray-200">
                                                                    {{ $i['purchased_at'] === '(Not Set)' ? 'Not Yet' : $i['purchased_at'] }}
                                                                </dd>
                                                            </div>

                                                        </dl>
                                                    </div>
                                                </div>
                                            </header>
                                        </div>
                                    </details>
                                </div>
                                <hr>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif


        </div>

        <div class="w-full items-center mt-4">
            <div class="py-0.5">
                <p class="whitespace-nowrap text-xl">Order Total:
                    USD{{ $order->formattedTotal() }}</p>
            </div>
            <div class="py-0.5">
                <p class="whitespace-nowrap text-xl">Total Paid:
                    USD{{ $order->formattedPaidAmount() }}</p>
            </div>
        </div>
        @if ($order->balance > 0 && $order->refunded === 0)
            <div class="flex space-between w-full items-center mt-4">
                <span class="inline-flex items-center justify-start rounded-full py-0.5 flex-grow">
                    <p class="whitespace-nowrap text-3xl text-green-200">USD{{ $order->formattedBalance() }}</p>
                </span>

                @if ($order->initiatedPayment)
                    <span>
                        @can('receive payment')
                            <x-mary-button label="Receive Payment" class="btn-success btn-sm"
                                wire:click="$toggle('receivePaymentModal')" />
                        @endcan

                        <x-modal wire:model="receivePaymentModal" title="Update Customer Details" subtitle="xxxx"
                            separator persistent>

                            <x-mary-card title="Receive Payment">

                                <div class="text-2xl font-bold text-green-200 mb-4">
                                    @if ($order->initiatedPayment->covers_full_balance)
                                        ${{ $order->formattedBalance() }}
                                    @endif

                                </div>
                                <x-mary-form wire:submit="receivePayment">
                                    {{-- Full error bag --}}
                                    {{-- All attributes are optional, remove it and give a try --}}
                                    <x-mary-errors title="Oops!" description="Please, fix them."
                                        icon="o-face-frown" />

                                    <x-mary-select :options="$payment_sources" option-value="slug"
                                        option-label="name" placeholder="Select method" wire:model.change="source" />

                                    @if ($source == 'CASH')
                                        <x-mary-input placeholder="Tender" type="number" wire:model="tender" />
                                    @endif

                                    @if ($order->initiatedPayment->covers_full_balance == 0)
                                        <x-mary-input placeholder="Amount" wire:model="amountDue" :disabled="$amountDisabled" />
                                    @endif

                                    <x-slot:actions>
                                        @if ($order->initiatedPayment->covers_full_balance == 1)
                                            <x-mary-button label="Switch To Part Payment" class="btn-warning btn-sm"
                                                wire:click="changeToPartPayment" spinner="save2" />
                                        @else
                                            <x-mary-button label="Switch To Full Payment" class="btn-warning btn-sm"
                                                wire:click="changeToFullPayment" spinner="save2" />
                                        @endif

                                        <x-mary-button label="RECEIVE" class="btn-success btn-sm" type="submit"
                                            spinner="save2" />
                                    </x-slot:actions>
                                </x-mary-form>
                            </x-mary-card>


                        </x-modal>
                    </span>
                @else
                    @can('initiate payment')
                        <livewire:initiate-payment-button :order="$order" />
                    @endcan
                @endif
            </div>
        @else
            <x-mary-alert icon="o-check" class="alert-success mt-8">
                ORDER FULLY PAID
            </x-mary-alert>
        @endif

        @if ($order->refunded === 1)
            <x-mary-alert icon="o-information-circle" class="alert-warning mt-8">
                ORDER WAS REFUNDED
            </x-mary-alert>
        @endif


        <x-modal wire:model="paymentDetailsModal" class="backdrop-blur" title="Payment Details" subtitle="xxxx"
                            separator persistent>

                            @if($recentPayment)
                            <x-mary-card title="Payment Received">

                                <div class="text-3xl font-bold text-green-200 mb-4">
                                    
                                    <div class="flow-root rounded-lg border border-gray-100 py-3 shadow-sm dark:border-gray-700">
                                        <dl class="-my-3 divide-y divide-gray-100 text-sm dark:divide-gray-700">
                                            @if ($order->source === 'INVENTORY')
                                            <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                                <dt class="font-medium text-gray-900 dark:text-white">Order ID (POS)</dt>
                                                <dd class="text-gray-700 sm:col-span-2 dark:text-gray-200">{{$order->id}}</dd>
                                              </div>

                                            @endif
                                            @if ($order->source === 'ONLINE')
                                            <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                                <dt class="font-medium text-gray-900 dark:text-white">Order ID (POS/AGENTX)</dt>
                                                <dd class="text-gray-700 sm:col-span-2 dark:text-gray-200">{{$order->id}}/{{$order->agentx_order_id}}</dd>
                                              </div>
                                            <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                                <dt class="font-medium text-gray-900 dark:text-white">Order Number</dt>
                                                <dd class="text-gray-700 sm:col-span-2 dark:text-gray-200">{{$order->order_id}}</dd>
                                              </div>

                                            @endif
                                          
                                      
                                          <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                            <dt class="font-medium text-gray-900 dark:text-white">Received By</dt>
                                            <dd class="text-gray-700 sm:col-span-2 dark:text-gray-200">{{$recentPayment->cashier->name}}</dd>
                                          </div>
                                      
                                          <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                            <dt class="font-medium text-gray-900 dark:text-white">Transaction Time</dt>
                                            <dd class="text-gray-700 sm:col-span-2 dark:text-gray-200">{{$recentPayment->received_amount_datetime}}</dd>
                                          </div>
                                      
                                          <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                            <dt class="font-medium text-gray-900 dark:text-white">Tendered</dt>
                                            <dd class="text-gray-700 sm:col-span-2 dark:text-gray-200">${{$recentPayment->formattedTenderAmount()}}</dd>
                                          </div>

                                      
                                          <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                            <dt class="font-medium text-gray-900 dark:text-white">Received</dt>
                                            <dd class="text-gray-700 sm:col-span-2 dark:text-gray-200">${{$recentPayment->formattedReceivedAmount()}}</dd>
                                          </div>
                                      
                                          <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                            <dt class="font-medium text-gray-900 dark:text-white">Change</dt>
                                            <dd class="text-gray-700 sm:col-span-2 dark:text-gray-200">${{$recentPayment->formattedChangeAmount()}}</dd>
                                          </div>
                                      
                                        </dl>
                                      </div>
                                      
                                </div>
                       
                            </x-mary-card>

                            @endif

                        </x-modal>
    </div>
</div>
