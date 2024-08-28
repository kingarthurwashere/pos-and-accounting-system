<div>
    @can('add to inventory')

    <form wire:submit.prevent="submit" class="flex-col space-y-4 p-4">
        <div class="flex-col space-y-4 lg:flex">
            <div class="col-span-6 lg:col-span-6">
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full flex-grow" wire:model="form.name"
                    autofocus />
                <x-input-error for="form.name" class="mt-2" />
            </div>

            <div class="sm:flex-col md:flex space-y-4">
                <div class="flex-grow">
                    <x-label for="price" value="{{ __('Price') }}" />
                    <x-input id="price" type="text" class="mt-1 block w-full" wire:model="form.price"
                        autofocus />
                    <x-input-error for="form.price" class="mt-2" />
                </div>

            </div>

            <div class="flex space-x-2">
                <div class="flex-grow">
                    <x-label for="location_id" value="{{ __('Location') }}" />
                    <select id="location_id"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full flex-grow"
                        wire:model="form.location_id">
                        <option value="">Select a location</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}">
                                {{ $location->city->name }} - {{ $location->alias }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="form.location_id" class="mt-2" />
                </div>
          
                <div class="flex-grow">
                    <x-label for="category" value="{{ __('Category') }}" />
                    <select id="category"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full flex-grow"
                        wire:model="form.category">
                        <option value="">Select a category</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->slug }}">
                                {{ $c->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="form.category" class="mt-2" />
                </div>
            </div>

            <div class="flex space-x-2">
                <div class="flex-grow">
                    <x-label for="stock_quantity" value="{{ __('Quantity') }}" />
                    <x-input id="stock_quantity" type="number" class="mt-1 block w-full"
                        wire:model="form.stock_quantity" autofocus />
                    <x-input-error for="form.stock_quantity" class="mt-2" />
                </div>

                <div class="flex-grow">
                    <x-label for="sku" value="{{ __('SKU (Optional)') }}" />
                    <x-input id="sku" type="text" class="mt-1 block w-full" wire:model="form.sku" autofocus />
                    <x-input-error for="form.sku" class="mt-2" />
                </div>
            </div>

            <div class="flex space-x-2">
                <div class="flex-grow">
                    <x-label for="size" value="{{ __('Size (Optional)') }}" />
                    <x-input id="size" type="text" class="mt-1 block w-full"
                        wire:model="form.size" autofocus />
                    <x-input-error for="form.size" class="mt-2" />
                </div>

                <div class="flex-grow">
                    <x-label for="color" value="{{ __('Color (Optional)') }}" />
                    <x-input id="color" type="text" class="mt-1 block w-full" wire:model="form.color" autofocus />
                    <x-input-error for="form.color" class="mt-2" />
                </div>
            </div>
        </div>

        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg font-medium disabled:opacity-50">
            <span wire:loading.delay.long>
                Loading
            </span>

            <span wire:loading.remove.delay.long>
                Submit
            </span>
        </button>
    </form>
    @endcan


</div>
