<div>
    <button class="bg-orange-500 btn-sm text-white py-2 px-4 rounded-lg" wire:click="$toggle('myModal')">{{$label}}</button>

    <x-modal wire:model="myModal" title="Refund" separator>
        
        <x-mary-card>
            <div class="flow-root">
                <h2 class="font-bold mb-4">Refund Details</h2>
                <dl class="-my-3 divide-y text-sm">
                  <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium">Amount</dt>
                    <dd class=" sm:col-span-2">{{ $payment->formattedReceivedAmount() }}</dd>
                  </div>
              
                  <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium">Notes</dt>
                    <dd class=" sm:col-span-2">
                        {{ $refund->notes }}
                    </dd>
                  </div>

                  <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium">Disburse Method</dt>
                    <dd class=" sm:col-span-2">
                        {{ $refund->method->name }}
                    </dd>
                  </div>

                  <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium">Initiated By</dt>
                    <dd class=" sm:col-span-2">
                        {{ $refund->initiator->name }}
                    </dd>
                  </div>

                  <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium">Approved By</dt>
                    <dd class=" sm:col-span-2">
                        {{ $refund->approver->name }}
                    </dd>
                  </div>
                </dl>

               <div class="flex space-x-4">
                <x-mary-button label="DISBURSE" wire:click="disburse()"  class="btn-success text-white flex-grow my-4" />
               </div>
              </div>
        </x-mary-card>

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.myModal = false" />
            <x-button label="Confirm" class="btn-primary" />
        </x-slot:actions>
    </x-modal>


</div>