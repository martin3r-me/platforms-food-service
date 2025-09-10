<div>
    <div class="d-flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Brands</h1>
        <x-ui-button variant="primary" wire:click="openCreateModal">New Brand</x-ui-button>
    </div>

    @if($items->count() > 0)
        <x-ui-table compact="true">
            <x-ui-table-header>
            <x-ui-table-header-cell compact="true">Name</x-ui-table-header-cell>
            <x-ui-table-header-cell compact="true">Manufacturers</x-ui-table-header-cell>
            <x-ui-table-header-cell compact="true">Status</x-ui-table-header-cell>
            <x-ui-table-header-cell compact="true" align="right">Action</x-ui-table-header-cell>
            </x-ui-table-header>

            <x-ui-table-body>
                @foreach($items as $item)
                    <x-ui-table-row compact="true" clickable="true" :href="route('foodservice.brands.show', ['brand' => $item])" wire:navigate>
                        <x-ui-table-cell compact="true">{{ $item->name }}</x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            @if($item->manufacturers->count() > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($item->manufacturers as $manufacturer)
                                        <x-ui-badge 
                                            variant="{{ $manufacturer->pivot->is_primary ? 'primary' : 'secondary' }}" 
                                            size="xs"
                                        >
                                            {{ $manufacturer->name }}
                                            @if($manufacturer->pivot->is_primary)
                                                <span class="ml-1">★</span>
                                            @endif
                                        </x-ui-badge>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted text-sm">–</span>
                            @endif
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            <x-ui-badge variant="{{ $item->is_active ? 'success' : 'secondary' }}" size="sm">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </x-ui-badge>
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true" align="right">
                            <x-ui-button size="sm" variant="secondary" :href="route('foodservice.brands.show', ['brand' => $item])" wire:navigate>
                                Open
                            </x-ui-button>
                        </x-ui-table-cell>
                    </x-ui-table-row>
                @endforeach
            </x-ui-table-body>
        </x-ui-table>
    @else
        <div class="text-center py-8 text-sm text-muted">No brands yet</div>
    @endif

    <x-ui-modal wire:model="modalShow" size="md">
        <x-slot name="header">Create Brand</x-slot>

        <form wire:submit.prevent="createItem" class="space-y-4">
            <x-ui-input-text name="name" label="Name" wire:model.live="name" required />
            <x-ui-input-textarea name="description" label="Description" wire:model.live="description" rows="3" />
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Manufacturers</label>
                <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-200 rounded-md p-2">
                    @forelse($manufacturers as $manufacturer)
                        <div class="d-flex items-center gap-2">
                            <x-ui-input-checkbox 
                                wire:model.live="selectedManufacturers" 
                                value="{{ $manufacturer->id }}"
                                size="sm"
                            />
                            <span class="text-sm">{{ $manufacturer->name }}</span>
                            @if(in_array($manufacturer->id, $selectedManufacturers))
                                <x-ui-input-checkbox 
                                    wire:model.live="primaryManufacturer" 
                                    value="{{ $manufacturer->id }}"
                                    size="sm"
                                    class="ml-auto"
                                />
                                <span class="text-xs text-muted">Primary</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-sm text-muted">No manufacturers available</div>
                    @endforelse
                </div>
            </div>
            
            <x-ui-input-checkbox model="is_active" checked-label="Active" unchecked-label="Inactive" />
        </form>

        <x-slot name="footer">
            <div class="d-flex justify-end gap-2">
                <x-ui-button type="button" variant="secondary-outline" @click="$wire.closeCreateModal()">Cancel</x-ui-button>
                <x-ui-button type="button" variant="primary" wire:click="createItem">Create</x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</div>
