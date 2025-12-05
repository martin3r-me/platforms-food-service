<x-foodservice-page
    title="Zusatzstoffe"
    icon="heroicon-o-beaker"
    description="Pflege alle Zusatzstoffe inklusive Hierarchien"
>
    <x-slot name="sidebar">
        @php
            $totalAdditives = $items->count();
            $activeAdditives = $items->where('is_active', true)->count();
            $strictAdditives = $items->where('is_strict', true)->count();
        @endphp
        <div class="space-y-6">
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-[var(--ui-secondary)] uppercase tracking-wider">Aktionen</h3>
                <x-ui-button variant="primary" size="sm" class="w-full justify-center" wire:click="openCreateModal">
                    @svg('heroicon-o-plus','w-4 h-4')
                    <span>Neuer Zusatzstoff</span>
                </x-ui-button>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-[var(--ui-secondary)] uppercase tracking-wider mb-3">Kurzstatistik</h3>
                <div class="space-y-2">
                    <div class="p-3 rounded-lg border border-[var(--ui-border)]/50 bg-[var(--ui-muted-5)]">
                        <p class="text-xs text-[var(--ui-muted)]">Gesamt</p>
                        <p class="text-lg font-semibold text-[var(--ui-secondary)]">{{ $totalAdditives }}</p>
                    </div>
                    <div class="p-3 rounded-lg border border-[var(--ui-border)]/50 bg-[var(--ui-muted-5)]">
                        <p class="text-xs text-[var(--ui-muted)]">Aktiv</p>
                        <p class="text-lg font-semibold text-[var(--ui-secondary)]">{{ $activeAdditives }}</p>
                    </div>
                    <div class="p-3 rounded-lg border border-[var(--ui-border)]/50 bg-[var(--ui-muted-5)]">
                        <p class="text-xs text-[var(--ui-muted)]">Streng (Hard)</p>
                        <p class="text-lg font-semibold text-[var(--ui-secondary)]">{{ $strictAdditives }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="activity">
        @php
            $recentAdditives = $items
                ->sortByDesc(fn($item) => $item->updated_at ?? $item->created_at)
                ->take(5);
        @endphp
        <div class="space-y-3">
            <h3 class="text-sm font-semibold text-[var(--ui-secondary)] uppercase tracking-wider">Zuletzt bearbeitet</h3>
            <div class="space-y-2">
                @forelse($recentAdditives as $recent)
                    <a
                        href="{{ route('foodservice.additives.show', ['additive' => $recent]) }}"
                        wire:navigate
                        class="block p-3 rounded-lg border border-[var(--ui-border)]/40 hover:border-[var(--ui-primary)]/60 transition-colors"
                    >
                        <p class="font-medium text-[var(--ui-secondary)]">{{ $recent->name }}</p>
                        <p class="text-xs text-[var(--ui-muted)]">{{ optional($recent->updated_at ?? $recent->created_at)->diffForHumans() }}</p>
                    </a>
                @empty
                    <p class="text-sm text-[var(--ui-muted)]">Noch keine Einträge.</p>
                @endforelse
            </div>
        </div>
    </x-slot>

        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm text-[var(--ui-muted)]">Übersicht aller gepflegten Zusatzstoffe</p>
            </div>
            <x-ui-button variant="primary" wire:click="openCreateModal">
                @svg('heroicon-o-plus','w-4 h-4')
                Neuer Zusatzstoff
            </x-ui-button>
        </div>

        @if($items->count() > 0)
            <x-ui-table compact="true">
                <x-ui-table-header>
                    <x-ui-table-header-cell compact="true">Name</x-ui-table-header-cell>
                    <x-ui-table-header-cell compact="true">Parent</x-ui-table-header-cell>
                    <x-ui-table-header-cell compact="true">Strenge</x-ui-table-header-cell>
                    <x-ui-table-header-cell compact="true">Status</x-ui-table-header-cell>
                    <x-ui-table-header-cell compact="true" align="right">Aktionen</x-ui-table-header-cell>
                </x-ui-table-header>

                <x-ui-table-body>
                    @foreach($items as $item)
                        <x-ui-table-row compact="true" clickable="true" :href="route('foodservice.additives.show', ['additive' => $item])" wire:navigate>
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
                                <x-ui-button size="sm" variant="secondary" :href="route('foodservice.additives.show', ['additive' => $item])" wire:navigate>
                                    Öffnen
                                </x-ui-button>
                            </x-ui-table-cell>
                        </x-ui-table-row>
                    @endforeach
                </x-ui-table-body>
            </x-ui-table>
        @else
            <x-ui-empty-state
                icon="heroicon-o-beaker"
                title="Noch keine Zusatzstoffe"
                description="Leg den ersten Zusatzstoff an, um Allergene präzise zu kennzeichnen."
            >
                <x-ui-button variant="primary" wire:click="openCreateModal">
                    Zusatzstoff erstellen
                </x-ui-button>
            </x-ui-empty-state>
        @endif

        <x-ui-modal wire:model="modalShow" size="md">
            <x-slot name="header">Zusatzstoff erstellen</x-slot>

            <form wire:submit.prevent="createItem" class="space-y-4">
                <x-ui-input-text name="name" label="Name" wire:model.live="name" required />
                <x-ui-input-textarea name="description" label="Beschreibung" wire:model.live="description" rows="3" />
                <x-ui-input-checkbox model="is_strict" checked-label="Streng (hard)" unchecked-label="Locker" />
                <x-ui-input-select
                    name="parent_id"
                    label="Übergeordneter Zusatzstoff"
                    :options="\Platform\FoodService\Models\FsAdditive::orderBy('name')->get()"
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
    </div>
</x-foodservice-page>