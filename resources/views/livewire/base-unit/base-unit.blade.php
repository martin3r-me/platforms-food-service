<div class="d-flex h-full">
    <!-- Linke Spalte -->
    <div class="flex-grow-1 d-flex flex-col">
        <!-- Header oben (fix) -->
        <div class="border-top-1 border-bottom-1 border-muted border-top-solid border-bottom-solid p-2 flex-shrink-0">
            <div class="d-flex gap-1">
                <div class="d-flex">
                    <a href="{{ route('foodservice.base-units.index') }}" class="d-flex px-3 border-right-solid border-right-1 border-right-muted underline" wire:navigate>
                        Base Units
                    </a>
                </div>
                <div class="flex-grow-1 text-right d-flex items-center justify-end gap-2">
                    <span>{{ $baseUnit->name }}</span>
                    @if($this->isDirty)
                        <x-ui-button 
                            variant="primary" 
                            size="sm"
                            wire:click="save"
                        >
                            <div class="d-flex items-center gap-2">
                                @svg('heroicon-o-check', 'w-4 h-4')
                                Save
                            </div>
                        </x-ui-button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Haupt-Content -->
        <div class="flex-grow-1 overflow-y-auto p-4">
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-secondary">Meta</h3>
                <div class="grid grid-cols-2 gap-4">
                    <x-ui-input-text 
                        name="baseUnit.name"
                        label="Name"
                        wire:model.live="baseUnit.name"
                        required
                        :errorKey="'baseUnit.name'"
                    />
                    <x-ui-input-text 
                        name="baseUnit.short_name"
                        label="Short Name"
                        wire:model.live="baseUnit.short_name"
                        required
                        :errorKey="'baseUnit.short_name'"
                    />
                </div>
                <div class="mt-4">
                    <x-ui-input-textarea 
                        name="baseUnit.description"
                        label="Description"
                        wire:model.live="baseUnit.description"
                        rows="4"
                        :errorKey="'baseUnit.description'"
                    />
                </div>
            </div>
            
        </div>

        <!-- Aktivitäten -->
        <div x-data="{ open: false }" class="flex-shrink-0 border-t border-muted">
            <div 
                @click="open = !open" 
                class="cursor-pointer border-top-1 border-top-solid border-top-muted border-bottom-1 border-bottom-solid border-bottom-muted p-2 text-center d-flex items-center justify-center gap-1 mx-2 shadow-lg"
            >
                ACTIVITIES
                <span class="text-xs">
                    {{$baseUnit->activities->count()}}
                </span>
                <x-heroicon-o-chevron-double-down 
                    class="w-3 h-3" 
                    x-show="!open"
                />
                <x-heroicon-o-chevron-double-up 
                    class="w-3 h-3" 
                    x-show="open"
                />
            </div>
            <div x-show="open" class="p-2 max-h-xs overflow-y-auto">
                <livewire:activity-log.index
                    :model="$baseUnit"
                    :key="get_class($baseUnit) . '_' . $baseUnit->id"
                />
            </div>
        </div>
    </div>

    <!-- Rechte Spalte -->
    <div class="min-w-80 w-80 d-flex flex-col border-left-1 border-left-solid border-left-muted">
        <div class="d-flex gap-2 border-top-1 border-bottom-1 border-muted border-top-solid border-bottom-solid p-2 flex-shrink-0">
            <x-heroicon-o-cog-6-tooth class="w-6 h-6"/>
            Settings
        </div>
        <div class="flex-grow-1 overflow-y-auto p-4">
            <div class="mb-4 p-3 bg-muted-5 rounded-lg">
                <h4 class="font-semibold mb-2 text-secondary">Overview</h4>
                <div class="space-y-1 text-sm">
                    <div><strong>Name:</strong> {{ $baseUnit->name }}</div>
                    <div><strong>Short:</strong> {{ $baseUnit->short_name }}</div>
                    <div><strong>Category:</strong> {{ $baseUnit->parent?->name ?? 'Top Level' }}</div>
                    <div><strong>Conversion Factor:</strong> {{ number_format($baseUnit->conversion_factor, 6) }}</div>
                    <div><strong>Base Unit:</strong> {{ $baseUnit->is_base_unit ? 'Yes' : 'No' }}</div>
                </div>
            </div>

            <div class="mb-4">
                <h4 class="font-semibold mb-2">Category</h4>
                <div class="space-y-2">
                    <x-ui-input-select
                        name="baseUnit.parent_id"
                        :options="$this->parentOptions"
                        optionValue="id"
                        optionLabel="name"
                        :nullable="true"
                        nullLabel="– None (Top Level) –"
                        wire:model.live="baseUnit.parent_id"
                    />
                    @if($baseUnit->parent)
                        <div class="text-sm">
                            Aktuell: 
                            <a href="{{ route('foodservice.base-units.show', ['baseUnit' => $baseUnit->parent]) }}" class="text-primary underline" wire:navigate>
                                {{ $baseUnit->parent->name }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <x-ui-input-number 
                    name="baseUnit.conversion_factor" 
                    label="Conversion Factor" 
                    wire:model.live="baseUnit.conversion_factor" 
                    step="0.000001"
                    min="0.000001"
                    required 
                />
                <x-ui-input-number 
                    name="baseUnit.decimal_places" 
                    label="Decimal Places" 
                    wire:model.live="baseUnit.decimal_places" 
                    min="0"
                    max="6"
                    required 
                />
            </div>

            @if($baseUnit->children->count() > 0)
                <div class="mb-4">
                    <h4 class="font-semibold mb-2">Units in this Category</h4>
                    <div class="space-y-1">
                        @foreach($baseUnit->children as $child)
                            <a href="{{ route('foodservice.base-units.show', ['baseUnit' => $child]) }}" class="block text-sm text-primary underline" wire:navigate>
                                {{ $child->name }} ({{ $child->short_name }})
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <x-ui-input-checkbox
                model="baseUnit.is_base_unit"
                checked-label="Base Unit"
                unchecked-label="Base Unit"
                size="md"
                block="true"
            />

            <x-ui-input-checkbox
                model="baseUnit.is_active"
                checked-label="Active"
                unchecked-label="Inactive"
                size="md"
                block="true"
            />

            <hr>

            <x-ui-confirm-button 
                action="deleteItem" 
                text="Delete" 
                confirmText="Wirklich löschen?" 
                variant="danger-outline"
                :icon="@svg('heroicon-o-trash', 'w-4 h-4')->toHtml()"
            />
        </div>
    </div>
</div>
