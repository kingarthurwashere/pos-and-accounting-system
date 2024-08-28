<x-app-layout>
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

                <div class="inline-flex rounded-md shadow-sm" role="group">
                    <button onclick='window.location="/pos/initiate-payment"' type="button" @class([ 'inline-flex items-center px-4 py-2 text-sm font-medium rounded-s-lg focus:z-10 focus:ring-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:bg-gray-50' , 'bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white dark:bg-gray-50'=> !request()->routeIs('payment.create'),
                        'text-white dark:bg-gray-900 dark:bg-green-500 border border-green-500 focus:ring-green-500 focus:text-white' => request()->routeIs('payment.create'),
                        ])>
                        Initiate Payment
                    </button>
                    <button onclick='window.location="/pos/payments"' type="button" @class([ 'inline-flex items-center px-4 py-2 text-sm font-medium border-t border-b focus:z-10 focus:ring-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white' , 'bg-white text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white'=> !request()->routeIs('payment.index'),
                        'text-white dark:bg-gray-900 dark:bg-green-500 border border-green-500 focus:ring-green-500 focus:text-white' => request()->routeIs('payment.index'),
                        ])>
                        Payment History
                    </button>
                    <button onclick='window.location="/pos/inventory"' type="button" @class([ 'inline-flex items-center px-4 py-2 text-sm font-medium rounded-e-lg focus:z-10 focus:ring-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white' , 'bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white'=> !request()->routeIs('inventory'),
                        'text-white dark:bg-gray-900 dark:bg-green-500 border border-green-500 focus:ring-green-500 focus:text-white' => request()->routeIs('inventory'),
                        ])>
                        Inventory
                    </button>
                </div>


            </div>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                Create
            </div>
        </div>
    </div>
</x-app-layout>