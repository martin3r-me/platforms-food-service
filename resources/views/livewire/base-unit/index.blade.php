<div>
    <div class="d-flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Base Units</h1>
        <x-ui-button variant="primary" wire:click="openCreateModal">New Category</x-ui-button>
    </div>

    @if($items->count() > 0)
        <x-ui-table compact="true">
            <x-ui-table-header>
                <x-ui-table-header-cell compact="true">Name</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Short Name</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Category</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Conversion Factor</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Base Unit</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Status</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true" align="right">Actions</x-ui-table-header-cell>
            </x-ui-table-header>

            <x-ui-table-body>
                @foreach($items as $item)
                    <x-ui-table-row compact="true">
                        <x-ui-table-cell compact="true">
                            <div class="d-flex items-center gap-2">
                                @if(isset($item->level) && $item->level > 0)
                                    <span class="text-muted" style="margin-left: {{ ($item->level - 1) * 20 }}px;">
                                        @if($item->level == 1)
                                            └─
                                        @else
                                            &nbsp;&nbsp;└─
                                        @endif
                                    </span>
                                @endif
                                <a href="{{ route('foodservice.base-units.show', ['baseUnit' => $item]) }}" class="underline" wire:navigate>
                                    {{ $item->name }}
                                </a>
                            </div>
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true">{{ $item->short_name }}</x-ui-table-cell>
                        <x-ui-table-cell compact="true">{{ $item->parent?->name ?? '–' }}</x-ui-table-cell>
                        <x-ui-table-cell compact="true">{{ number_format($item->conversion_factor, 6) }}</x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            @if($item->is_base_unit)
                                <x-ui-badge variant="primary" size="sm">Base</x-ui-badge>
                            @else
                                –
                            @endif
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            <x-ui-badge variant="{{ $item->is_active ? 'success' : 'secondary' }}" size="sm">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </x-ui-badge>
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true" align="right">
                            <div class="d-flex gap-1 justify-end">
                                @if(!$item->parent_id)
                                    <x-ui-button 
                                        size="sm" 
                                        variant="primary-outline" 
                                        wire:click="openCreateModal({{ $item->id }})"
                                        title="Add Unit to Category"
                                    >
                                        @svg('heroicon-o-plus', 'w-4 h-4')
                                    </x-ui-button>
                                @endif
                                <x-ui-button 
                                    size="sm" 
                                    variant="secondary" 
                                    :href="route('foodservice.base-units.show', ['baseUnit' => $item])" 
                                    wire:navigate
                                >
                                    Open
                                </x-ui-button>
                            </div>
                        </x-ui-table-cell>
                    </x-ui-table-row>
                @endforeach
            </x-ui-table-body>
        </x-ui-table>
    @else
        <div class="text-center py-8 text-sm text-muted">No base units yet</div>
    @endif

    <x-ui-modal wire:model="modalShow" size="md">
        <x-slot name="header">
            @if($selectedParentId)
                Create Unit
            @else
                Create Category
            @endif
        </x-slot>

        <form wire:submit.prevent="createItem" class="space-y-4">
            <x-ui-input-text name="name" label="Name" wire:model.live="name" required />
            <x-ui-input-text name="short_name" label="Short Name" wire:model.live="short_name" required />
            <x-ui-input-textarea name="description" label="Description" wire:model.live="description" rows="3" />
            
            @if($selectedParentId)
                {{-- Parent ausgewählt - Parent ist bereits gesetzt --}}
                <div class="p-3 bg-muted rounded">
                    <div class="text-sm text-muted mb-1">Category:</div>
                    <div class="font-medium">{{ \Platform\FoodService\Models\FsBaseUnit::find($selectedParentId)?->name }}</div>
                </div>
            @else
                {{-- Neue Kategorie - Parent auswählen --}}
                <x-ui-input-select
                    name="parent_id"
                    label="Parent Category (optional)"
                    :options="$this->parentOptions"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– None (Top Level Category) –"
                    wire:model.live="parent_id"
                />
            @endif
            
            <div class="grid grid-cols-2 gap-4">
                <x-ui-input-number 
                    name="conversion_factor" 
                    label="Conversion Factor" 
                    wire:model.live="conversion_factor" 
                    step="0.000001"
                    min="0.000001"
                    required 
                />
                <x-ui-input-number 
                    name="decimal_places" 
                    label="Decimal Places" 
                    wire:model.live="decimal_places" 
                    min="0"
                    max="6"
                    required 
                />
            </div>
            
            <x-ui-input-checkbox model="is_base_unit" checked-label="Base Unit" unchecked-label="Base Unit" />
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
