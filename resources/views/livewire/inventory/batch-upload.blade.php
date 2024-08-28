<div>
    @can('add to inventory')
    <form wire:submit.prevent="submit" class="flex-col space-y-4 p-4">
       
        <div class="flex-col space-y-4 lg:flex">
            <div class="col-span-6 lg:col-span-6">
                <x-mary-file wire:model.change="file" id="file" label="Inventory File" hint="Only Excel (.xlsx)" 
                    accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
            </div>
        </div>

        @if(!$ready)
            <x-mary-button  type="submit">Check Batch</x-mary-button>
     
        @endif

    </form>

    @if($ready)
    <form wire:submit.prevent="process" class="flex-col space-y-4 p-4">
        <x-mary-button class="btn-success" type="submit">Start Upload Process</x-mary-button>
    </form>
    @endif

    @endcan




    @if(count($previewItems) > 0)
    <x-mary-card>
        <table class="table table-bordered mt-3">

            <tr>

                <th colspan="3">

                    Preview

                </th>

            </tr>

            <tr>

                <th>Location</th>

                <th>Name</th>

                <th>Category</th>

                <th>Price</th>

            </tr>

            @foreach($previewItems as $item)

            <tr>
                <td>{{ $item[1] }}</td>
                <td>{{ $item[4] }}</td>
                <td>{{ $item[3] }}</td>
                <td>{{ $item[7] }}</td>

            </tr>

            @endforeach

        </table>
    </x-mary-card>

    @endif

</div>
