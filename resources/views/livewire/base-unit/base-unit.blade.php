<x-foodservice-page
    :title="$baseUnit->name"
    icon="heroicon-o-scale"
    :breadcrumbs="[
        ['label' => 'Basiseinheiten', 'href' => route('foodservice.base-units.index')],
    ]"
>
    <x-slot name="actions">
        <x-ui-button variant="secondary-outline" :href="route('foodservice.base-units.index')" wire:navigate>
            @svg('heroicon-o-arrow-left','w-4 h-4')
            Übersicht
        </x-ui-button>
        @if($this->isDirty)
            <x-ui-button variant="primary" wire:click="save">
                @svg('heroicon-o-check','w-4 h-4')
                Speichern
            </x-ui-button>
        @endif
    </x-slot>

    <x-slot name="sidebar">
        <div class="space-y-6">
            <div class="p-4 rounded-xl border border-[var(--ui-border)]/50 bg-[var(--ui-muted-5)]">
                <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-3">Status</h4>
                <x-ui-badge :variant="$baseUnit->is_active ? 'success' : 'secondary'" size="sm">
                    {{ $baseUnit->is_active ? 'Aktiv' : 'Inaktiv' }}
                </x-ui-badge>
            </div>

            <div class="space-y-2">
                <x-ui-input-checkbox
                    model="baseUnit.is_base_unit"
                    checked-label="Basiseinheit"
                    unchecked-label="Basiseinheit"
                    size="md"
                    block="true"
                />
                <x-ui-input-checkbox
                    model="baseUnit.is_active"
                    checked-label="Aktiv"
                    unchecked-label="Inaktiv"
                    size="md"
                    block="true"
                />
            </div>

            @if($baseUnit->children->count() > 0)
                <div>
                    <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-2">Einheiten in dieser Kategorie</h4>
                    <div class="space-y-1 text-sm">
                        @foreach($baseUnit->children as $child)
                            <a href="{{ route('foodservice.base-units.show', ['baseUnit' => $child]) }}" class="text-[var(--ui-primary)] hover:underline" wire:navigate>
                                {{ $child->name }} ({{ $child->short_name }})
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <x-ui-confirm-button 
                action="deleteItem" 
                text="Löschen" 
                confirmText="Wirklich löschen?" 
                variant="danger-outline"
                :icon="@svg('heroicon-o-trash', 'w-4 h-4')->toHtml()"
            />
        </div>
    </x-slot>

    <x-slot name="activity">
        <livewire:activity-log.index
            :model="$baseUnit"
            :key="get_class($baseUnit) . '_' . $baseUnit->id"
        />
    </x-slot>

    <div class="space-y-6">
        <x-ui-panel title="Stammdaten">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui-input-text name="baseUnit.name" label="Name" wire:model.live="baseUnit.name" required :errorKey="'baseUnit.name'" />
                <x-ui-input-text name="baseUnit.short_name" label="Kurzname" wire:model.live="baseUnit.short_name" required :errorKey="'baseUnit.short_name'" />
            </div>
            <div class="mt-4">
                <x-ui-input-textarea name="baseUnit.description" label="Beschreibung" wire:model.live="baseUnit.description" rows="4" :errorKey="'baseUnit.description'" />
            </div>
        </x-ui-panel>

        <x-ui-panel title="Klassifizierung & Faktoren">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui-input-select
                    name="baseUnit.parent_id"
                    label="Kategorie (Parent)"
                    :options="$this->parentOptions"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– Top Level –"
                    wire:model.live="baseUnit.parent_id"
                />
                <div class="space-y-2">
                    @if($baseUnit->parent)
                        <p class="text-sm text-[var(--ui-muted)]">Aktuell:</p>
                        <a href="{{ route('foodservice.base-units.show', ['baseUnit' => $baseUnit->parent]) }}" class="text-[var(--ui-primary)] hover:underline" wire:navigate>
                            {{ $baseUnit->parent->name }}
                        </a>
                    @endif
                </div>
                <x-ui-input-number 
                    name="baseUnit.conversion_factor" 
                    label="Umrechnungsfaktor" 
                    wire:model.live="baseUnit.conversion_factor" 
                    step="0.000001"
                    min="0.000001"
                    required 
                />
                <x-ui-input-number 
                    name="baseUnit.decimal_places" 
                    label="Dezimalstellen" 
                    wire:model.live="baseUnit.decimal_places" 
                    min="0"
                    max="6"
                    required 
                />
            </div>
        </x-ui-panel>
    </div>
</x-foodservice-page>

