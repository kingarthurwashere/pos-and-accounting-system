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
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Amount
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Method
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Location
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <!-- This can be left empty if it's intended -->
                        </th>
                    </tr>
                </thead>
                <tbody> <!-- Added a default poll time, you can adjust this -->
                    @forelse ($items as $i)
                    <tr key="oph{{$i->id}}" class="bg-white dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $i->id }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $i->formattedReceivedAmount() }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $i->paymentSource ? $i->paymentSource->name : 'Not Set' }}
                        </td>
                        <td class="px-6 py-4">
                            <b>{{ strtoupper($i->payable_slug ) }}</b>
                        </td>
                        <td class="px-6 py-4">
                            <x-mary-badge class="btn-secondary text-gray-50" value="{{ $i->location->city->name }}"></x-mary-badge> <br>{{ $i->location->alias }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $i->created_at->diffForHumans() }} <!-- Assuming you want a formatted date -->
                        </td>
                        <td class="px-6 py-4 flex space-x-2">
                            
                                
                            @if($i->payable_slug === 'order' )
                                <a href="{{ route('order.show', $i->payable_identifier) }}" wire:navigate>
                                    <x-mary-button class="btn-sm btn-info" icon="o-eye" />
                                </a>
                            @endif

                            @if($i->status === 'REFUNDED')
                                <x-mary-badge value="REFUNDED" class="badge-info"/>
                            @endif

                            @if($i->status === 'RECEIVED' && !$i->refundInitiated && !$i->refundApproved)
                                @can('initiate refund')
                                    <livewire:refund-button :payment="$i" :key="'refund-button-' . $i->id" />
                                @endcan
                            @endif
                            
                            @if($i->refundInitiated)
                                @can('initiate refund')
                                    <livewire:refund-action :refund="$i->refundInitiated" :wire:key="'refund-action-' . $i->id" label="Action" :payment="$i" /> 
                                @endcan
                            @endif
        
                            @if($i->refundApproved)
                                @can('disburse refund amount')
                                    <livewire:refund-disburse :refund="$i->refundApproved" :wire:key="'refund-disburse-' . $i->id" label="Disburse" :payment="$i" /> 
                                @endcan
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

            @if ($items->count())
                {{ $items->links() }}
            @endif
        </div>

    </div>



</div>
