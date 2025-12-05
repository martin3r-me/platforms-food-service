<x-foodservice-page
    :title="$brand->name"
    icon="heroicon-o-star"
    :breadcrumbs="[
        ['label' => 'Marken', 'href' => route('foodservice.brands.index')],
    ]"
>
    <x-slot name="actions">
        <x-ui-button variant="secondary-outline" :href="route('foodservice.brands.index')" wire:navigate>
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
                <x-ui-badge :variant="$brand->is_active ? 'success' : 'secondary'" size="sm">
                    {{ $brand->is_active ? 'Aktiv' : 'Inaktiv' }}
                </x-ui-badge>
            </div>

            <x-ui-input-checkbox
                model="brand.is_active"
                checked-label="Aktiv"
                unchecked-label="Inaktiv"
                size="md"
                block="true"
            />

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
            :model="$brand"
            :key="'brand_' . $brand->id"
        />
    </x-slot>

    <div class="space-y-6">
        <x-ui-panel title="Meta">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui-input-text 
                    name="brand.name"
                    label="Name"
                    wire:model.live="brand.name"
                    required
                    :errorKey="'brand.name'"
                />
                <x-ui-input-textarea 
                    name="brand.description"
                    label="Beschreibung"
                    wire:model.live="brand.description"
                    rows="4"
                    :errorKey="'brand.description'"
                    class="md:col-span-2"
                />
            </div>
        </x-ui-panel>

        <x-ui-panel title="Hersteller">
            @if($brand->manufacturers->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($brand->manufacturers as $manufacturer)
                        <x-ui-badge 
                            variant="{{ $manufacturer->pivot->is_primary ? 'primary' : 'secondary' }}" 
                            size="md"
                        >
                            {{ $manufacturer->name }}
                            @if($manufacturer->pivot->is_primary)
                                <span class="ml-1">★</span>
                            @endif
                        </x-ui-badge>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-[var(--ui-muted)]">Keine Hersteller verknüpft.</p>
            @endif
        </x-ui-panel>
    </div>
</x-foodservice-page>

