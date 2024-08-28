<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg space-y-4">

            <form class="mx-auto">
                <label for="default-search"
                    class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
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
            </form>

            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Reference
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Funded City
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Amount
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $r)
                        <tr
                            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $r->reference }}
                            </th>
                            <td class="px-6 py-4">
                                <x-mary-badge value="{{ $r->receiver_name }}" />
                            </td>
                            <td class="px-6 py-4">
                                <x-mary-badge class="badge-warning font-bold" value="{{ $r->fundedCity->name }}" />
                            </td>
                            <td class="px-6 py-4">
                                {{ $r->formattedReceivable() }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badgeClass = match ($r->status) {
                                        'ACCEPTED' => 'bg-teal-500 text-gray-900',
                                        'REJECTED' => 'bg-red-500 text-white',
                                        'AWAITING PICKUP' => 'badge-warning',
                                        'DISBURSED' => '',
                                        default => 'bg-gray-500 text-white',
                                    };
                                @endphp
                                <x-mary-badge value="{{ $r->status }}" class="{{ $badgeClass }}" />
                            </td>
                            <td class="px-6 py-4">
                                <x-mary-button label="View" class="btn-primary btn-sm text-white"
                                    onclick="modalView{{ $r->id }}.showModal()" />

                                <x-mary-modal id="modalView{{ $r->id }}" title="{{ $r->email }}">
                                    <h2 class="text-xl font-bold text-success mb-4">USD{{ $r->formattedReceivable() }}</h2>
                                    <div class="ml-4">
                                        <x-mary-timeline-item title="Due for Collection"
                                            description="{{ $r->due_at->diffForHumans() }}" first
                                            icon="o-paper-airplane" />

                                        @if ($r->status === 'REJECTED')
                                            <x-mary-timeline-item title="Rejected" last
                                                description="{{ $r->rejected_at->diffForHumans() }} by {{ $r->rejected_by == auth()->user()->id ? 'You' : $r->rejecter->name }}"
                                                icon="o-no-symbol" />
                                        @else
                                            @if ($r->status === 'DISBURSED' || $r->status === 'ACCEPTED')
                                                <x-mary-timeline-item title="ACCEPTED"
                                                    description="{{ $r->accepted_at->diffForHumans() }} by {{ $r->accepted_by == auth()->user()->id ? 'You' : $r->acceptor->name }}"
                                                    icon="o-check" />
                                            @else
                                                <x-mary-timeline-item title="ACCEPTED" description="(Pending)" pending
                                                    last icon="o-check" />
                                            @endif

                                            @if ($r->status === 'DISBURSED')
                                                <x-mary-timeline-item title="Disbursed" last
                                                    description="{{ $r->disbursed_at->diffForHumans() }} by {{ $r->disbursed_by == auth()->user()->id ? 'You' : $r->disburser->name }} as {{ $r->method->name }}"
                                                    icon="o-banknotes" />
                                            @else
                                                <x-mary-timeline-item title="Disbursed" description="(Pending)" pending
                                                    last last icon="o-banknotes" />
                                            @endif
                                        @endif




                                    </div>

                                    <x-slot:actions>
                                        {{-- Notice `onclick` is HTML --}}
                                        <x-mary-button label="Close" onclick="modalView{{ $r->id }}.close()" />
                                    </x-slot:actions>
                                </x-mary-modal>

                                @if ($r->status === 'AWAITING PICKUP')
                                    @can('accept remittances')

                                        <x-mary-button label="Accept" class="btn-success btn-sm"
                                            onclick="confirmApprove{{ $r->id }}.showModal()" />

                                        <x-mary-modal id="confirmApprove{{ $r->id }}" title="Accept to acknowledge funds were received?">
                                            Click "cancel" or press ESC to exit.

                                            <x-slot:actions>
                                                {{-- Notice `onclick` is HTML --}}
                                                <x-mary-button label="Cancel"
                                                    onclick="confirmApprove{{ $r->id }}.close()" />
                                                <x-mary-button label="Confirm" class="btn-primary" onclick="confirmApprove{{ $r->id }}.close()"
                                                    wire:click="approve({{ $r->id }})" />
                                            </x-slot:actions>
                                        </x-mary-modal>

                                    @endcan
                                @endif

                                @if ($r->status === 'ACCEPTED')
                                    @can('disburse remittance cash')
                                        <x-mary-button label="Disburse" class="btn-info btn-sm"
                                            onclick="confirmDisburse{{ $r->id }}.showModal()" />

                                        <x-mary-modal id="confirmDisburse{{ $r->id }}" title="Are you sure?">

                                            <div class="ml-4">
                                                <x-mary-timeline-item title="Due for Collection"
                                                    description="{{ $r->created_at->diffForHumans() }}" first
                                                    icon="o-paper-airplane" />

                                                <x-mary-timeline-item title="ACCEPTED"
                                                    description="{{ $r->accepted_at->diffForHumans() }} by {{ $r->accepted_by == auth()->user()->id ? 'You' : $r->acceptor->name }}"
                                                    icon="o-check" />

                                                <x-mary-timeline-item title="Disbursed" description="(Pending)" pending last
                                                    icon="o-banknotes" />
                                            </div>

                                            {{-- <label class="form-control w-full">
                                                <div class="label">
                                                    <span class="label-text">Disbursement Method*</span>
                                                </div>
                                                <select class="select select-bordered w-full" wire:model="method">
                                                    @foreach ($methods as $method)
                                                        <option value="{{ $method->slug }}">{{ $method->name }}</option>
                                                    @endforeach
                                                </select>
                                            </label> --}}

                                            <x-slot:actions>
                                                {{-- Notice `onclick` is HTML --}}
                                                <x-mary-button label="Cancel"
                                                    onclick="confirmDisburse{{ $r->id }}.close()" />
                                                <x-mary-button label="Confirm & Disburse" 
                                                onclick="confirmDisburse{{ $r->id }}.close()"
                                                class="btn-success text-white"
                                                    wire:click="disburse({{ $r->id }})" />
                                            </x-slot:actions>
                                        </x-mary-modal>
                                    @endcan
                                @endif

                                @if ($r->status === 'AWAITING PICKUP')
                                    <x-mary-button label="Reject" class="btn-error btn-sm text-white"
                                        onclick="confirmReject{{ $r->id }}.showModal()" />

                                    <x-mary-modal id="confirmReject{{ $r->id }}" title="Are you sure?">

                                        <x-mary-textarea label="Message*" wire:model="rejection_message"
                                            placeholder="Rejection justification ..." hint="Max 1000 chars"
                                            rows="5" inline />

                                        <x-slot:actions>
                                            {{-- Notice `onclick` is HTML --}}
                                            <x-mary-button label="Cancel"
                                                onclick="confirmReject{{ $r->id }}.close()" />
                                            <x-mary-button label="Confirm & Reject" class="btn-error text-white"
                                            onclick="confirmReject{{ $r->id }}.close()" 
                                            wire:click="reject({{ $r->id }})" />
                                        </x-slot:actions>
                                    </x-mary-modal>
                                @endif

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

            @if ($requests->count())
                {{ $requests->links() }}
            @endif
        </div>

    </div>



</div>
