<x-foodservice-page
    title="Attribute"
    icon="heroicon-o-adjustments-horizontal"
    description="Verwalte produktspezifische Attribute"
>
    <x-slot name="sidebar">
        <div class="space-y-3">
            <h3 class="text-sm font-semibold text-[var(--ui-secondary)] uppercase tracking-wider">Aktionen</h3>
            <x-ui-button variant="primary" size="sm" class="w-full justify-center" wire:click="openCreateModal">
                @svg('heroicon-o-plus','w-4 h-4')
                <span>Neues Attribut</span>
            </x-ui-button>
        </div>
    </x-slot>

    <x-slot name="activity">
        <p class="text-sm text-[var(--ui-muted)]">Keine Aktivitäten verfügbar.</p>
    </x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-[var(--ui-muted)]">Übersicht aller Attribute</p>
        <x-ui-button variant="primary" wire:click="openCreateModal">
            @svg('heroicon-o-plus','w-4 h-4')
            Neues Attribut
        </x-ui-button>
    </div>

    @if($items->count() > 0)
        <x-ui-table compact="true">
            <x-ui-table-header>
                <x-ui-table-header-cell compact="true">Name</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Parent</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Strenge</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Status</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true" align="right">Aktion</x-ui-table-header-cell>
            </x-ui-table-header>

            <x-ui-table-body>
                @foreach($items as $item)
                    <x-ui-table-row compact="true" clickable="true" :href="route('foodservice.attributes.show', ['attribute' => $item])" wire:navigate>
                        <x-ui-table-cell compact="true">{{ $item->name }}</x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            {{ $item->parent?->name ?? '–' }}
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            <x-ui-badge variant="secondary" size="sm">{{ $item->is_strict ? 'Streng' : 'Locker' }}</x-ui-badge>
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            <x-ui-badge variant="{{ $item->is_active ? 'success' : 'secondary' }}" size="sm">
                                {{ $item->is_active ? 'Aktiv' : 'Inaktiv' }}
                            </x-ui-badge>
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true" align="right">
                            <x-ui-button size="sm" variant="secondary" :href="route('foodservice.attributes.show', ['attribute' => $item])" wire:navigate>
                                Öffnen
                            </x-ui-button>
                        </x-ui-table-cell>
                    </x-ui-table-row>
                @endforeach
            </x-ui-table-body>
        </x-ui-table>
    @else
        <x-ui-empty-state
            icon="heroicon-o-adjustments-horizontal"
            title="Noch keine Attribute"
            description="Lege Attribute an, um Artikel fein zu klassifizieren."
        >
            <x-ui-button variant="primary" wire:click="openCreateModal">
                Attribut erstellen
            </x-ui-button>
        </x-ui-empty-state>
    @endif

    <x-ui-modal wire:model="modalShow" size="md">
        <x-slot name="header">Attribut erstellen</x-slot>

        <form wire:submit.prevent="createItem" class="space-y-4">
            <x-ui-input-text name="name" label="Name" wire:model.live="name" required />
            <x-ui-input-textarea name="description" label="Beschreibung" wire:model.live="description" rows="3" />
            <x-ui-input-checkbox model="is_strict" checked-label="Streng (hard)" unchecked-label="Locker" />
            <x-ui-input-select
                name="parent_id"
                label="Parent"
                :options="\Platform\FoodService\Models\FsAttribute::orderBy('name')->get()"
                optionValue="id"
                optionLabel="name"
                :nullable="true"
                nullLabel="– Kein Parent –"
                wire:model.live="parent_id"
            />
        </form>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-ui-button type="button" variant="secondary-outline" @click="$wire.closeCreateModal()">Abbrechen</x-ui-button>
                <x-ui-button type="button" variant="primary" wire:click="createItem">Speichern</x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</x-foodservice-page>