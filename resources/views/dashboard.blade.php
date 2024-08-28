<x-app-layout>
    <x-slot name="header">
        <div class="flex h-6 space-between w-full items-center">
            <div class="w-full">
                <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hidden sm:flex ml-0">
                    {{ __('Dashboard') }}
                </h3>
                <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex sm:hidden">
                    {{ __('Dashboard') }}
                </h3>
            </div>
            <div class="w-full flex justify-end">
                <div class="inline-flex rounded-md shadow-sm" role="group">
                    @can('generate daily report')
                    <a href="{{ route('daily-report') }}" class="href">
                        <button type="button" @class([ 'inline-flex items-center px-4 py-2 text-sm font-medium rounded-s-lg focus:z-10 focus:ring-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:bg-gray-50' , 'bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-blue-700 focus:text-blue-700 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white dark:bg-gray-50'=> !request()->routeIs(
                            'daily-report'),
                            'text-white dark:bg-gray-900 dark:bg-slate-900 border border-green-500 focus:ring-green-500 focus:text-white' => request()->routeIs(
                            'daily-report'),
                            ])>
                            Daily Report
                        </button>
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 pb-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <livewire:PostTime />

          
            <livewire:DailyReportVerificationAlert />
       
            
        
            <div>
                <h2 class="font-bold mb-2 mt-4">Overview</h2>
            </div>
            @role('cashier')
            <div class=" overflow-hidden sm:rounded-lg mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <x-dashboard.basic-card-no-border stat="${{ $location->formattedBalance() }}" label="{{ $location->alias }}" icon="map-pin" iconColor="green-600" />
                    <x-dashboard.basic-card-no-border stat="${{ $location->city->balance->formatted() }}" label="{{ $location->city->name }}" icon="map-pin" iconColor="green-400" />
                    <x-dashboard.basic-card-no-border stat="${{ $overall_balance }}" label="Overall" icon="map-pin" iconColor="green-600" />
                </div>
            </div>
            @endrole

            <div class=" overflow-hidden sm:rounded-lg">
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-4">
                    @can('receive payment')
                    <a href="{{ route('order.initiated-payments') }}" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $initiated_payments }}" label="Initiated Payments" icon="banknotes" />
                    </a>
                    @endcan

                    @can('approve refund')
                    <a href="{{ route('order.initiated-refunds') }}" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $initiated_refunds }}" label="Initiated Refunds" icon="receipt-refund" />
                    </a>
                    @endcan

                    @can('disburse refund amount')
                    <a href="{{ route('order.approved-refunds') }}" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $approved_refunds }}" label="Approved Refunds" icon="receipt-refund" />
                    </a>
                    @endcan


                </div>
            </div>

            <div class=" overflow-hidden sm:rounded-lg">
                <div>
                    <h2 class="font-bold mb-2 mt-4 text">Inventory</h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-4">
                    @can('approve inventory prices')
                    <a href="{{ route('inventory.index') }}" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $stock_items }}" label="Stock Items" icon="clipboard-document-check" />
                    </a>
                    @endcan

                    @can('approve inventory prices')
                    <a href="{{ route('inventory.pending') }}" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $pending_stock_items }}" label="Pending Price Confirmation" icon="clipboard-document-check" />
                    </a>
                    @endcan
                </div>
            </div>

            <div class=" overflow-hidden sm:rounded-lg">
                <div>
                    <h2 class="font-bold mb-2 mt-4 text">Withdrawal Requests</h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-4">

                    @can('approve withdrawal request')
                    <a href="{{ route('withdrawal-request.pending') }}" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $pending_withdrawals }}" label="Pending Approval" icon="arrows-up-down" />
                    </a>
                    @endcan

                    @can('disburse withdrawal request')
                    <a href="{{ route('withdrawal-request.approved') }}" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $approval_withdrawals }}" label="Awaiting Disbursement" icon="arrows-up-down" />
                    </a>
                    @endcan
                </div>
            </div>

            <div class=" overflow-hidden sm:rounded-lg">
                <div>
                    <h2 class="font-bold mb-2 mt-4 text">Instabucks Remittances</h2>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-3 gap-4">

                    @can('disburse remittance cash')
                    <a href="{{ route('remittance.index') }}" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $remittances }}" label="All Remittances" icon="arrows-up-down" />
                    </a>
                    @endcan

                    @can('accept remittances')
                    <a href="{{ route('remittance.due') }}" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $remittance_awaiting_pickup }}" label="Due for Collection" icon="arrows-up-down" />
                    </a>
                    @endcan

                    @can('disburse remittance cash')
                    <a href="{{ route('remittance.accepted') }}" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $remittance_accepted }}" label="Ready For Disbursement" icon="arrows-up-down" />
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="max-w-7xl mt-8 mx-auto sm:px-6 lg:px-8 space-y-4">
            {{-- <livewire:my-test /> --}}
        </div>
    </div>


</x-app-layout>