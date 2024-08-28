
    <div>
        <x-mary-button label="Confirm" icon="o-check" class="btn-success btn-sm"   wire:click="$toggle('myModal')" />

        <x-mary-modal title="Are you sure?" wire:model="myModal" separator>
        This action will CONFIRM that the details stated are accurate.
            <x-mary-form wire:submit="confirmDailyReport" class="p-4">
                <x-mary-errors title="Oops!" description="Please, fix them." icon="o-face-frown" />

                <x-mary-choices-offline
                label="Choose A Verifying Agent"
                    wire:model="requestForm.assignedVerifier"
                    :options="$users"
                    option-label="name"
                    option-sub-label="email"
                    single
                    searchable />

                    <x-slot:actions>
                        <x-mary-button wire:click="$toggle('myModal')" label="Cancel" />
                        <x-mary-button label="Submit" type="submit" class="btn-primary" />
                    </x-slot:actions>
            </x-mary-form>
        </x-mary-modal>
    </div>
