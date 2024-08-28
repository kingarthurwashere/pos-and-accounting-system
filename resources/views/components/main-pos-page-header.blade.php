<x-slot name="header">
    <div class="flex h-6 space-between w-full items-center">
        <div class="w-full">
            <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hidden sm:flex ml-0">
                {{ __('Point Of Sale') }}
            </h3>
            <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex sm:hidden">
                {{ __('POS') }}
            </h3>
        </div>
        <div class="w-full flex justify-end">

            <livewire:cart-preview />


        </div>
    </div>
</x-slot>
