<div>
 

        <div class="shadow rounded p-4 border bg-white w-full">
            <livewire:livewire-line-chart
                key="{{ $multiLineChartModel->reactiveKey() }}"
                :line-chart-model="$multiLineChartModel"
            />
        </div>
</div>
