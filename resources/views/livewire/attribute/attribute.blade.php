<x-foodservice-page
    :title="$attribute->name"
    icon="heroicon-o-adjustments-horizontal"
    :breadcrumbs="[
        ['label' => 'Attribute', 'href' => route('foodservice.attributes.index')],
    ]"
>
    <x-slot name="actions">
        <x-ui-button variant="secondary-outline" :href="route('foodservice.attributes.index')" wire:navigate>
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
                <x-ui-badge :variant="$attribute->is_active ? 'success' : 'secondary'" size="sm">
                    {{ $attribute->is_active ? 'Aktiv' : 'Inaktiv' }}
                </x-ui-badge>
            </div>

            <div class="space-y-2">
                <x-ui-input-checkbox
                    model="attribute.is_strict"
                    checked-label="Streng (hard)"
                    unchecked-label="Locker"
                    size="md"
                    block="true"
                />
                <x-ui-input-checkbox
                    model="attribute.is_active"
                    checked-label="Aktiv"
                    unchecked-label="Inaktiv"
                    size="md"
                    block="true"
                />
            </div>

            <div>
                <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-2">Kinder</h4>
                <div class="space-y-1 text-sm">
                    @forelse($attribute->children as $child)
                        <a href="{{ route('foodservice.attributes.show', ['attribute' => $child]) }}" class="text-[var(--ui-primary)] hover:underline" wire:navigate>
                            {{ $child->name }}
                        </a>
                    @empty
                        <p class="text-[var(--ui-muted)]">Keine Kinder vorhanden.</p>
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
            :model="$attribute"
            :key="get_class($attribute) . '_' . $attribute->id"
        />
    </x-slot>

    <div class="space-y-6">
        <x-ui-panel title="Meta">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui-input-text 
                    name="attribute.name"
                    label="Name"
                    wire:model.live="attribute.name"
                    required
                    :errorKey="'attribute.name'"
                />
                <x-ui-input-select
                    name="attribute.parent_id"
                    label="Parent"
                    :options="$this->parentOptions"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– None –"
                    wire:model.live="attribute.parent_id"
                />
            </div>

            <div class="mt-4">
                <x-ui-input-textarea 
                    name="attribute.description"
                    label="Beschreibung"
                    wire:model.live="attribute.description"
                    rows="4"
                    :errorKey="'attribute.description'"
                />
            </div>
        </x-ui-panel>

        <x-ui-panel title="Beziehungen & Hinweise">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-2">Parent</h4>
                    @if($attribute->parent)
                        <a href="{{ route('foodservice.attributes.show', ['attribute' => $attribute->parent]) }}" class="text-[var(--ui-primary)] hover:underline" wire:navigate>
                            {{ $attribute->parent->name }}
                        </a>
                    @else
                        <p class="text-sm text-[var(--ui-muted)]">Kein Parent zugewiesen.</p>
                    @endif
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-2">Strenge</h4>
                    <p class="text-sm">{{ $attribute->is_strict ? 'Streng (hard)' : 'Locker' }}</p>
                </div>
            </div>
        </x-ui-panel>
    </div>
</x-foodservice-page>

