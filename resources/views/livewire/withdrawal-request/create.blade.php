@can('create withdrawal request')
<x-mary-form wire:submit="save" class="p-4">
    <div class="w-full">
        <x-label for="email" value="{{ __('Email') }}" />
        <x-input id="email" type="text" class="mt-1 block w-full flex-grow" wire:model="form.email" autofocus />
        <x-input-error for="form.email" class="mt-2" />
    </div>

    <div class="flex space-x-2">
        <div class="flex-grow">
            <x-label for="type" value="{{ __('Type') }}" />
            <select id="type" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full flex-grow" wire:model="form.type">
                <option value="">Select Type</option>
                @foreach ($types as $type)
                <option value="{{ $type->slug }}">
                    {{ $type->name }}
                </option>
                @endforeach
            </select>
            <x-input-error for="form.type" class="mt-2" />
        </div>

        <div class="flex-grow">
            <x-label for="amount" value="{{ __('Amount') }}" />
            <x-input id="amount" type="text" class="mt-1 block w-full flex-grow" wire:model="form.amount" autofocus />
            <x-input-error for="form.amount" class="mt-2" />
        </div>
    </div>

    <div class="w-full mt-4">
        <x-label for="description" value="{{ __('Description') }}" />
        <textarea id="description" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" wire:model="form.description" rows="4"></textarea>
        <x-input-error for="form.description" class="mt-2" />
    </div>

    <button type="submit" class="bg-blue-500 text-white py-2 px-4 mt-4 rounded-lg font-medium disabled:opacity-50">
        <span wire:loading.delay.long>
            Loading
        </span>
        <span wire:loading.remove.delay.long>
            Submit
        </span>
    </button>
</x-mary-form>
@endcan