<x-foodservice-page
    :title="$additive->name"
    icon="heroicon-o-beaker"
    :breadcrumbs="[
        ['label' => 'Zusatzstoffe', 'href' => route('foodservice.additives.index')],
    ]"
>
    <x-slot name="actions">
        <x-ui-button variant="secondary-outline" :href="route('foodservice.additives.index')" wire:navigate>
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
        <div class="space-y-4">
            <div class="p-3 rounded-lg border border-[var(--ui-border)]/40 bg-[var(--ui-muted-5)]">
                <p class="text-xs text-[var(--ui-muted)] uppercase tracking-wider mb-1">Status</p>
                <x-ui-badge :variant="$additive->is_active ? 'success' : 'secondary'" size="sm">
                    {{ $additive->is_active ? 'Aktiv' : 'Inaktiv' }}
                </x-ui-badge>
            </div>

            <div class="space-y-2">
                <x-ui-input-checkbox
                    model="additive.is_strict"
                    checked-label="Streng (hard)"
                    unchecked-label="Locker"
                    size="md"
                    block="true"
                />
                <x-ui-input-checkbox
                    model="additive.is_active"
                    checked-label="Aktiv"
                    unchecked-label="Inaktiv"
                    size="md"
                    block="true"
                />
            </div>

            <div>
                <h4 class="text-sm font-semibold mb-2">Kinder</h4>
                <div class="space-y-1 text-sm">
                    @forelse($additive->children as $child)
                        <a href="{{ route('foodservice.additives.show', ['additive' => $child]) }}" class="text-[var(--ui-primary)] hover:underline" wire:navigate>
                            {{ $child->name }}
                        </a>
                    @empty
                        <p class="text-[var(--ui-muted)] text-sm">Keine Kinder vorhanden.</p>
                    @endforelse
                </div>
            </div>

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
            :model="$additive"
            :key="get_class($additive) . '_' . $additive->id"
        />
    </x-slot>

    <div class="space-y-6">
        <x-ui-panel title="Stammdaten">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui-input-text 
                    name="additive.name"
                    label="Name"
                    wire:model.live="additive.name"
                    required
                    :errorKey="'additive.name'"
                />
                <x-ui-input-select
                    name="additive.parent_id"
                    label="Übergeordneter Zusatzstoff"
                    :options="$this->parentOptions"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– Kein Parent –"
                    wire:model.live="additive.parent_id"
                />
            </div>

            <div class="mt-4">
                <x-ui-input-textarea 
                    name="additive.description"
                    label="Beschreibung"
                    wire:model.live="additive.description"
                    rows="4"
                    :errorKey="'additive.description'"
                />
            </div>
        </x-ui-panel>

        <x-ui-panel title="Beziehungen & Hinweise">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-2">Parent</h4>
                    @if($additive->parent)
                        <a href="{{ route('foodservice.additives.show', ['additive' => $additive->parent]) }}" class="text-[var(--ui-primary)] hover:underline" wire:navigate>
                            {{ $additive->parent->name }}
                        </a>
                    @else
                        <p class="text-sm text-[var(--ui-muted)]">Kein Parent zugewiesen.</p>
                    @endif
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-2">Strenge</h4>
                    <p class="text-sm">{{ $additive->is_strict ? 'Streng (hard)' : 'Locker' }}</p>
                </div>
            </div>
        </x-ui-panel>
    </div>
</x-foodservice-page>