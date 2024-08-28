@props(['order', 'items', 'name'])
<x-slot name="header">
    <div class="flex h-6 space-between w-full items-center">
        <div class="w-full">
            <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hidden sm:flex ml-0">
                {{ $order->source == 'INVENTORY' ? 'INVENTORY ORDER #' . $order->id : 'ONLINE ORDER #' . $order->order_id }}
            </h3>
        </div>

        <div class="w-full flex justify-end">

            <livewire:order-status-in-navigation :order="$order" />


        </div>
    </div>
</x-slot>

<div class="pt-2 w-full">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <livewire:order-navigation :order="$order" />
        <livewire:order-invoice-history :order="$order" />
    </div>
</div>
</div>
