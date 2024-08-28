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
                <div>
                    <x-mary-icon name="o-map-pin" class="text-orange-500" :label="$report->location->alias" /><br>
                    <x-mary-icon name="o-user" class="text-green-500" :label="auth()->user()->name" />
                </div>
                <br>
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-4">
                    {{-- <a href="#" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $report->formattedOB() }}" label="Opening Balance" icon="banknotes" iconColor="orange-600"/>
                    </a>
                    <a href="#" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $report->formattedCB() }}" label="Closing Balance" icon="banknotes" iconColor="green-400" />
                    </a> --}}
                    <a href="#" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $report->formattedDisbursed() }}" label="Disbursed" icon="banknotes" iconColor="purple-400" />
                    </a>
                    <a href="#" wire:navigate>
                        <x-dashboard.basic-card stat="{{ $report->formattedReceived() }}" label="Received" icon="banknotes" iconColor="violet-600" />
                    </a>

                </div>
            </div>
        </div>

    </div>
</div>