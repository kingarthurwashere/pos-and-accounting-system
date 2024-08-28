<x-slot name="header">
    <div class="flex h-6 space-between w-full items-center">
        <div class="w-full">
            <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hidden sm:flex ml-0">
                {{ __('Inventory') }}
            </h3>
            <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex sm:hidden">
                {{ __('Inventory') }}
            </h3>
        </div>
        <div class="w-full flex justify-end">

            <div class="inline-flex rounded-md shadow-sm" role="group">
                @can('add to inventory')
                    <a href="{{ route('inventory.create') }}" wire:navigate class="href">
                        <button type="button" @class([
                            'inline-flex items-center px-4 py-2 text-sm font-medium rounded-s-lg focus:z-10 focus:ring-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:bg-gray-50',
                            'bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white dark:bg-gray-50' => !request()->routeIs(
                                'inventory.create'),
                            'text-white dark:bg-gray-900 dark:bg-slate-900 border border-green-500 focus:ring-green-500 focus:text-white' => request()->routeIs(
                                'inventory.create'),
                        ])>
                            Add Item
                        </button>
                    </a>
                

                    <a href="{{ route('inventory.batch-upload') }}" wire:navigate class="href">
                        <button type="button" @class([
                            'inline-flex items-center px-4 py-2 text-sm font-medium rounded-s-lg focus:z-10 focus:ring-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:bg-gray-50',
                            'bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white dark:bg-gray-50' => !request()->routeIs(
                                'inventory.batch-upload'),
                            'text-white dark:bg-gray-900 dark:bg-slate-900 border border-green-500 focus:ring-green-500 focus:text-white' => request()->routeIs(
                                'inventory.batch-upload'),
                        ])>
                            Batch Upload
                        </button>
                    </a>
                @endcan

                <a href="{{ route('inventory.index') }}" wire:navigate class="href">
                    <button type="button" @class([
                        'inline-flex items-center px-4 py-2 text-sm font-medium rounded-e-lg focus:z-10 focus:ring-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white',
                        'bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white' => !request()->routeIs(
                            'inventory.index'),
                        'text-white dark:bg-gray-900 dark:bg-slate-900 border border-green-500 focus:ring-green-500 focus:text-white' => request()->routeIs(
                            'inventory.index'),
                    ])>
                        View Stock
                    </button>
                </a>



            </div>


        </div>
    </div>
</x-slot>
