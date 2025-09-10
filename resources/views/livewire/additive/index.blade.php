<div>
    <div class="d-flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Additives</h1>
        <x-ui-button variant="primary" wire:click="openCreateModal">New Additive</x-ui-button>
    </div>

    <div class="space-y-2">
        @foreach($items as $item)
            <a href="{{ route('foodservice.additives.show', ['additive' => $item]) }}"
               class="block p-2 rounded hover:bg-primary-10" wire:navigate>
                {{ $item->name }}
            </a>
        @endforeach
    </div>

    <x-ui-modal wire:model="modalShow" size="md">
        <x-slot name="header">Create Additive</x-slot>

        <form wire:submit.prevent="createItem" class="space-y-4">
            <x-ui-input-text label="Name" wire:model.live="name" required />
            <x-ui-input-textarea label="Description" wire:model.live="description" rows="3" />
            <x-ui-input-checkbox label="Strict (hard)" wire:model.live="is_strict" />
        </form>

        <x-slot name="footer">
            <div class="d-flex justify-end gap-2">
                <x-ui-button type="button" variant="secondary-outline" @click="$wire.closeCreateModal()">Cancel</x-ui-button>
                <x-ui-button type="button" variant="primary" wire:click="createItem">Create</x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</div>


