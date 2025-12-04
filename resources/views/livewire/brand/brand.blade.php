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
        <div class="space-y-3">
            <div class="p-3 rounded border border-[var(--ui-border)]/40 bg-[var(--ui-muted-5)]">
                <p class="text-xs text-[var(--ui-muted)] uppercase tracking-wider">Status</p>
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

<div class="d-flex h-full">
    <!-- Linke Spalte -->
    <div class="flex-grow-1 d-flex flex-col">
        <!-- Haupt-Content -->
        <div class="flex-grow-1 overflow-y-auto p-4">
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-secondary">Meta</h3>
                <div class="grid grid-cols-2 gap-4">
                    <x-ui-input-text 
                        name="brand.name"
                        label="Name"
                        wire:model.live="brand.name"
                        required
                        :errorKey="'brand.name'"
                    />
                </div>
                <div class="mt-4">
                    <x-ui-input-textarea 
                        name="brand.description"
                        label="Description"
                        wire:model.live="brand.description"
                        rows="4"
                        :errorKey="'brand.description'"
                    />
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-secondary">Manufacturers</h3>
                @if($brand->manufacturers->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
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
                    <div class="text-muted">No manufacturers assigned</div>
                @endif
            </div>
        </div>

        <div class="flex-shrink-0 border-t border-muted"></div>
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
                    <div><strong>Name:</strong> {{ $brand->name }}</div>
                    <div><strong>Status:</strong> {{ $brand->is_active ? 'Active' : 'Inactive' }}</div>
                </div>
            </div>

            <div class="text-sm text-[var(--ui-muted)]">
                Weitere Einstellungen können später ergänzt werden.
            </div>
        </div>
    </div>
</div>
</x-foodservice-page>