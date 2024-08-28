<div class="mt-4 flex flex-col space-y-4">
    <h2>Payment History</h2>
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
                    {{ $i->created_at->format('m/d/Y') }} <!-- Assuming you want a formatted date -->
                </td>
                <td class="px-6 py-4">
                    @if($i->status === 'REFUNDED')
                        <x-mary-badge value="REFUNDED" class="badge-info"/>
                    @endif

                    @if($i->status === 'RECEIVED' && !$i->refundInitiated && !$i->refundApproved)
                        @can('initiate refund')
                            <livewire:refund-button :payment="$i" :key="'refund-button-' . $i->id" />
                        @endcan
                    @endif

                    @if($i->refundInitiated)
                        @can('approve refund')
                            <livewire:refund-action :refund="$i->refundInitiated" :wire:key="'refund-action-' . $i->id" label="Action" :payment="$i" /> 
                        @endcan
                    @endif

                    @if($i->refundApproved)
                        @can('disburse refund amount')
                            <livewire:refund-disburse :refund="$i->refundApproved" :wire:key="'refund-disburse-' . $i->id" label="Disburse" :payment="$i" /> 
                        @endif
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td class="pl-6 py-4 text-gray-900 dark:text-white">
                    No items found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
