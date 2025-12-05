<x-foodservice-page
    :title="$manufacturer->name"
    icon="heroicon-o-building-office"
    :breadcrumbs="[
        ['label' => 'Hersteller', 'href' => route('foodservice.manufacturers.index')],
    ]"
>
    <x-slot name="actions">
        <x-ui-button variant="secondary-outline" :href="route('foodservice.manufacturers.index')" wire:navigate>
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
                <x-ui-badge :variant="$manufacturer->is_active ? 'success' : 'secondary'" size="sm">
                    {{ $manufacturer->is_active ? 'Aktiv' : 'Inaktiv' }}
                </x-ui-badge>
            </div>

            <x-ui-input-checkbox
                model="manufacturer.is_active"
                checked-label="Aktiv"
                unchecked-label="Inaktiv"
                size="md"
                block="true"
            />

            <div>
                <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-2">Marken</h4>
                <div class="space-y-1 text-sm">
                    @forelse($manufacturer->brands as $brand)
                        <a href="{{ route('foodservice.brands.show', ['brand' => $brand]) }}" class="text-[var(--ui-primary)] hover:underline" wire:navigate>
                            {{ $brand->name }}
                        </a>
                    @empty
                        <p class="text-[var(--ui-muted)]">Keine Marken verknüpft.</p>
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
            :model="$manufacturer"
            :key="get_class($manufacturer) . '_' . $manufacturer->id"
        />
    </x-slot>

    <div class="space-y-6">
        <x-ui-panel title="Meta">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui-input-text 
                    name="manufacturer.name"
                    label="Name"
                    wire:model.live="manufacturer.name"
                    required
                    :errorKey="'manufacturer.name'"
                />
                <x-ui-input-textarea 
                    name="manufacturer.description"
                    label="Beschreibung"
                    wire:model.live="manufacturer.description"
                    rows="4"
                    :errorKey="'manufacturer.description'"
                    class="md:col-span-2"
                />
            </div>
        </x-ui-panel>

        <x-ui-panel title="Marken">
            @if($manufacturer->brands->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($manufacturer->brands as $brand)
                        <x-ui-badge 
                            variant="{{ $brand->pivot->is_primary ? 'primary' : 'secondary' }}" 
                            size="md"
                        >
                            {{ $brand->name }}
                            @if($brand->pivot->is_primary)
                                <span class="ml-1">★</span>
                            @endif
                        </x-ui-badge>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-[var(--ui-muted)]">Keine Marken verknüpft.</p>
            @endif
        </x-ui-panel>
    </div>
</x-foodservice-page>

