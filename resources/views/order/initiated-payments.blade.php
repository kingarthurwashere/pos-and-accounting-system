<x-app-layout>
    <x-slot name="header">
        <div class="flex h-6 space-between w-full items-center">
            <div class="w-full">
                <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hidden sm:flex ml-0">
                    {{ __('Orders Pending Payment') }}
                </h3>
                <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex sm:hidden">
                    {{ __('Orders') }}
                </h3>
            </div>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <livewire:order-initiated-payments />
            </div>
        </div>
    </div>
</x-app-layout>
