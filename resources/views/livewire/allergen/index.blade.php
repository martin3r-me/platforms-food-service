<div>
    <div class="d-flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Allergens</h1>
        <x-ui-button variant="primary" wire:click="openCreateModal">New Allergen</x-ui-button>
    </div>

    @if($items->count() > 0)
        <x-ui-table compact="true">
            <x-ui-table-header>
                <x-ui-table-header-cell compact="true">Name</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Parent</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Strict</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Status</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true" align="right">Action</x-ui-table-header-cell>
            </x-ui-table-header>

            <x-ui-table-body>
                @foreach($items as $item)
                    <x-ui-table-row compact="true" clickable="true" :href="route('foodservice.allergens.show', ['allergen' => $item])" wire:navigate>
                        <x-ui-table-cell compact="true">{{ $item->name }}</x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            {{ $item->parent?->name ?? '–' }}
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            <x-ui-badge variant="secondary" size="sm">{{ $item->is_strict ? 'Hard' : 'Soft' }}</x-ui-badge>
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            <x-ui-badge variant="{{ $item->is_active ? 'success' : 'secondary' }}" size="sm">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </x-ui-badge>
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true" align="right">
                            <x-ui-button size="sm" variant="secondary" :href="route('foodservice.allergens.show', ['allergen' => $item])" wire:navigate>
                                Open
                            </x-ui-button>
                        </x-ui-table-cell>
                    </x-ui-table-row>
                @endforeach
            </x-ui-table-body>
        </x-ui-table>
    @else
        <div class="text-center py-8 text-sm text-muted">No allergens yet</div>
    @endif

    <x-ui-modal wire:model="modalShow" size="md">
        <x-slot name="header">Create Allergen</x-slot>

        <form wire:submit.prevent="createItem" class="space-y-4">
            <x-ui-input-text name="name" label="Name" wire:model.live="name" required />
            <x-ui-input-textarea name="description" label="Description" wire:model.live="description" rows="3" />
            <x-ui-input-checkbox model="is_strict" checked-label="Strict (hard)" unchecked-label="Strict (hard)" />
        </form>

        <x-slot name="footer">
            <div class="d-flex justify-end gap-2">
                <x-ui-button type="button" variant="secondary-outline" @click="$wire.closeCreateModal()">Cancel</x-ui-button>
                <x-ui-button type="button" variant="primary" wire:click="createItem">Create</x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</div>


