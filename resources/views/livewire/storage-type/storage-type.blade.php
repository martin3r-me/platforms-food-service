<x-foodservice-page
    :title="$storageType->name"
    icon="heroicon-o-archive-box"
    :breadcrumbs="[
        ['label' => 'Lagerarten', 'href' => route('foodservice.storage-types.index')],
    ]"
>
    <x-slot name="actions">
        <x-ui-button variant="secondary-outline" :href="route('foodservice.storage-types.index')" wire:navigate>
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
                <x-ui-badge :variant="$storageType->is_active ? 'success' : 'secondary'" size="sm">
                    {{ $storageType->is_active ? 'Aktiv' : 'Inaktiv' }}
                </x-ui-badge>
            </div>

            <x-ui-input-checkbox
                model="storageType.is_active"
                checked-label="Aktiv"
                unchecked-label="Inaktiv"
                size="md"
                block="true"
            />

            @if($storageType->children->count() > 0)
                <div>
                    <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-2">Kinder</h4>
                    <div class="space-y-1 text-sm">
                        @foreach($storageType->children as $child)
                            <a href="{{ route('foodservice.storage-types.show', ['storageType' => $child]) }}" class="text-[var(--ui-primary)] hover:underline" wire:navigate>
                                {{ $child->name }}
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
            :model="$storageType"
            :key="get_class($storageType) . '_' . $storageType->id"
        />
    </x-slot>

    <div class="space-y-6">
        <x-ui-panel title="Stammdaten">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui-input-text name="storageType.name" label="Name" wire:model.live="storageType.name" required :errorKey="'storageType.name'" />
                <x-ui-input-select
                    name="storageType.parent_id"
                    label="Parent"
                    :options="$this->parentOptions"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– None –"
                    wire:model.live="storageType.parent_id"
                />
            </div>

            <div class="mt-4">
                <x-ui-input-textarea 
                    name="storageType.description"
                    label="Beschreibung"
                    wire:model.live="storageType.description"
                    rows="4"
                    :errorKey="'storageType.description'"
                />
            </div>
        </x-ui-panel>

        <x-ui-panel title="Beziehungen">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-2">Parent</h4>
                    @if($storageType->parent)
                        <a href="{{ route('foodservice.storage-types.show', ['storageType' => $storageType->parent]) }}" class="text-[var(--ui-primary)] hover:underline" wire:navigate>
                            {{ $storageType->parent->name }}
                        </a>
                    @else
                        <p class="text-sm text-[var(--ui-muted)]">Kein Parent zugewiesen.</p>
                    @endif
                </div>
            </div>
        </x-ui-panel>
    </div>
</x-foodservice-page>

