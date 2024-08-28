<div>
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

            <div class="flex h-6 space-between w-full items-center">
                <div class="w-full">
                    <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight hidden sm:flex ml-0">
                        {{ __('Daily Report') }}
                    </h3>
                    <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex sm:hidden">
                        {{ __('Report') }}
                    </h3>
                    @if ($report->status == 'NOT_CONFIRMED')
                    <x-mary-badge value="Not Confirmed" class="badge-warning" />
                    
                    @endif

                    @if ($report->status == 'CONFIRMED')
                    <x-mary-badge value="Confirmed" class="badge-info" />
                    @endif

                    @if ($report->status == 'VERIFIED')
                    <x-mary-badge value="Verified" class="bg-green-500 text-gray-900" />
                    @endif
                    @if ($report->status == 'CLOSED')
                    <x-mary-badge value="CLOSED" />
                    @endif
                </div>
                <div class="w-full flex justify-end">
                    <div class="inline-flex rounded-md shadow-sm" role="group">
                        @if (Auth::user()->can('confirm', $report))
                            <livewire:daily-report-confirm-button />
                        @endif

                        @if (Auth::user()->can('verify', $report))
                        <x-mary-button label="Verify" icon="o-check" class="btn-info btn-sm" onclick="confirmReport.showModal()" />

                        <x-mary-modal id="confirmReport" title="Are you sure?">
                            This action will VERIFY that the details stated are accurate.

                            <x-slot:actions>
                                {{-- Notice `onclick` is HTML --}}
                                <x-mary-button label="Cancel" onclick="confirmReport.close()" />
                                <x-mary-button label="VERIFY" class="btn-info" wire:click="verifyDailyReport" />
                            </x-slot:actions>
                        </x-mary-modal>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="mt-8 pb-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class=" overflow-hidden sm:rounded-lg">
                <div class="flex justify-between">
                    <div>
                        <x-mary-icon name="o-map-pin" class="text-orange-500" :label="$report->location->alias" /><br>
                        <x-mary-icon name="o-user" class="text-green-500" :label="auth()->user()->name" />
                    </div>
                    <div>
                        <x-mary-icon name="o-banknotes" class="text-orange-500" label="Cash In Hand" /><br>
                        <x-mary-icon name="o-arrow-long-right" class="text-green-500" label="USD{{$cashInHand}}" />
                    </div>
                </div>
                <br>
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-4">
                
                    <x-dashboard.basic-card stat="{{ $report->formattedOB() }}" label="Opening Balance" icon="banknotes" iconColor="orange-600"/>
            
                    <x-dashboard.basic-card stat="{{ $report->formattedCB() }}" label="Closing Balance" icon="banknotes" iconColor="green-400" />
                    
                    <x-dashboard.basic-card stat="{{ $report->formattedDisbursed() }}" label="Disbursed" icon="banknotes" iconColor="purple-400" />
        
                    <x-dashboard.basic-card stat="{{ $report->formattedReceived() }}" label="Received" icon="banknotes" iconColor="violet-600" />
                
                    {{-- <a href="{{route('order.initiated-refunds')}}" wire:navigate>
                    <x-dashboard.basic-card stat="{{ $initiated_refunds }}" label="Initiated Refunds" icon="receipt-refund" />
                    </a>
                    <a href="{{route('order.approved-refunds')}}" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $approved_refunds }}" label="Approved Refunds" icon="receipt-refund" />
                    </a>
                    <a href="{{route('inventory.pending')}}" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $pending_stock_items }}" label="Pending Stock Items" icon="clipboard-document-check" />
                    </a> --}}
                </div>
            </div>
        </div>

        <div class="max-w-7xl mt-8 mx-auto sm:px-6 lg:px-8 space-y-4">
            {{-- Notice `wire:model` --}}
            <x-mary-tabs wire:model="selectedTab">
                <x-mary-tab name="received-tab" label="Received" icon="o-arrow-trending-up">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Source
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Time
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($received as $r)
                                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$r['source']}}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{$r['amount']}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$r['created_at']}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-900 dark:text-white">
                                        No items found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-mary-tab>
                <x-mary-tab name="disbursed-tab" label="Disbursed" icon="o-arrow-trending-down">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Source
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Time
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($disbursed as $d)
                                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{$d['source']}}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{$d['amount']}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$d['created_at']}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-900 dark:text-white">
                                        No items found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-mary-tab>
            </x-mary-tabs>
        </div>
    </div>
</div>