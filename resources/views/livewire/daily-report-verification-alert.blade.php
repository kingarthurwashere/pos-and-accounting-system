<div wire:poll.5s>
    @if($reportForVerification)
        <x-mary-alert title="VERIFY `{{ $reportForVerification->cashier->name }}`'s daily report before the day ends"  description="Tap to preview and action this request" icon="o-envelope" class="alert-info">
            <x-slot:actions>
                <a href="{{ route('daily-report.view', $reportForVerification->id) }}" wire:navigate>
                    <x-mary-button label="Open" />
                </a>
                
            </x-slot:actions>
        </x-mary-alert>
    @endif
</div>
