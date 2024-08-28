@props(['order', 'label'])
<div>
    <x-mary-button :label="$label" class="btn-primary btn-sm" onclick="initPayment{{ $order->id }}.showModal()" />
    <x-mary-modal id="initPayment{{ $order->id }}" title="Payment Initiation">
        <form wire:submit.prevent="submit" class="flex-col space-y-4 p-4">
            <div class="flex-col space-y-4 lg:flex">

                <div class="sm:flex-col md:flex space-y-4">
                    <div class="flex-grow">

                        <div class="flex items-center mb-4">
                            <input id="selectedPaymentOption-1" wire:model="selectedPaymentOption" type="radio"
                                name="paymentOption" value="Full"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="selectedPaymentOption-1"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Full Amount</label>
                        </div>
                        <div class="flex items-center">
                            <input id="selectedPaymentOption-2" wire:model="selectedPaymentOption" type="radio"
                                value="Part" name="paymentOption"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="selectedPaymentOption-2"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Part Amount</label>
                        </div>

                    </div>

                    @if ($selectedPaymentOption === 'Part')
                        <div class="flex-grow">
                            <x-label for="received_amount" value="{{ __('To Be Paid') }}" />
                            <x-input id="received_amount" type="number" name="received_amount" min="1"
                                step="1" class="mt-1 block w-full" wire:model="received_amount" autofocus />
                            <x-input-error for="received_amount" class="mt-2" />
                        </div>
                    @endif


                    {{-- 
                    <div class="flex-grow">
                        <x-label for="payable_slug" value="{{ __('Location') }}" />
                        <select id="payable_slug"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full flex-grow"
                            wire:model="payable_slug">
                            <option value="">Destination</option>
                            @foreach ($payables as $payable)
                                <option value="{{ $payable->slkug }}">
                                    {{ $payable->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="payable_slug" class="mt-2" />
                    </div> --}}
                </div>

            </div>
        </form>

        <x-slot:actions>
            {{-- Notice `onclick` is HTML --}}
            <x-mary-button label="Cancel" onclick="initPayment{{ $order->id }}.close()" />
            <x-mary-button label="Confirm & Initiate" class="btn-success"
                onclick="initPayment{{ $order->id }}.close()" wire:click="initiatePayment()" />
        </x-slot:actions>
    </x-mary-modal>
</div>
