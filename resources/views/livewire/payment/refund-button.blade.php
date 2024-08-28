<div>


    <x-mary-button label="Refund" class="btn-warning btn-sm" wire:click="$toggle('myModal')" />

    <x-modal wire:model="myModal" title="Refund" separator>
        <x-mary-form wire:submit.prevent="refund({{$payment}})" class="p-4">
            <x-mary-errors title="Oops!" description="Please, fix them." icon="o-face-frown" />

            <x-mary-select label="Refund Method" :options="$refund_methods" option-value="slug" option-label="name" value="CASH" wire:model="refundRequestForm.method" />

            <x-mary-textarea label="Notes" wire:model="refundRequestForm.notes" placeholder="Refund notes ..." hint="Max 1000 chars" rows="5" inline />

            <x-slot:actions>
                <x-mary-button wire:click="$toggle('myModal')" label="Cancel" />
                <x-mary-button label="Submit" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-mary-form>

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.myModal = false" />
            <x-button label="Confirm" class="btn-primary" />
        </x-slot:actions>
    </x-modal>


</div>