<x-foodservice-page
    :title="$allergen->name"
    icon="heroicon-o-exclamation-triangle"
    :breadcrumbs="[
        ['label' => 'Allergene', 'href' => route('foodservice.allergens.index')],
    ]"
>
    <x-slot name="actions">
        <x-ui-button variant="secondary-outline" :href="route('foodservice.allergens.index')" wire:navigate>
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
            <div class="p-3 rounded border border-[var(--ui-border)]/50 bg-[var(--ui-muted-5)]">
                <p class="text-xs text-[var(--ui-muted)] uppercase tracking-wider">Status</p>
                <x-ui-badge :variant="$allergen->is_active ? 'success' : 'secondary'" size="sm">
                    {{ $allergen->is_active ? 'Aktiv' : 'Inaktiv' }}
                </x-ui-badge>
            </div>
            <div class="space-y-2">
                <x-ui-input-checkbox
                    model="allergen.is_strict"
                    checked-label="Streng (hard)"
                    unchecked-label="Locker"
                    size="md"
                    block="true"
                />
                <x-ui-input-checkbox
                    model="allergen.is_active"
                    checked-label="Aktiv"
                    unchecked-label="Inaktiv"
                    size="md"
                    block="true"
                />
            </div>
        </div>
    </x-slot>

    <x-slot name="activity">
        <livewire:activity-log.index
            :model="$allergen"
            :key="get_class($allergen) . '_' . $allergen->id"
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
                        name="allergen.name"
                        label="Name"
                        wire:model.live="allergen.name"
                        required
                        :errorKey="'allergen.name'"
                    />
                </div>
                <div class="mt-4">
                    <x-ui-input-select
                        name="allergen.parent_id"
                        label="Parent"
                        :options="$this->parentOptions"
                        optionValue="id"
                        optionLabel="name"
                        :nullable="true"
                        nullLabel="– None –"
                        wire:model.live="allergen.parent_id"
                    />
                </div>

                <div class="mt-4">
                    <x-ui-input-textarea 
                        name="allergen.description"
                        label="Description"
                        wire:model.live="allergen.description"
                        rows="4"
                        :errorKey="'allergen.description'"
                    />
                </div>
            </div>
            
        </div>

        <!-- Aktivitäten -->
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
                    <div><strong>Name:</strong> {{ $allergen->name }}</div>
                    <div><strong>Strict:</strong> {{ $allergen->is_strict ? 'Hard' : 'Soft' }}</div>
                </div>
            </div>

            <x-ui-input-checkbox
                model="allergen.is_strict"
                checked-label="Strict (hard)"
                unchecked-label="Strict (hard)"
                size="md"
                block="true"
            />

            <div class="mb-4">
                <h4 class="font-semibold mb-2">Parent</h4>
                <div class="space-y-2">
                    <x-ui-input-select
                        name="allergen.parent_id"
                        :options="$this->parentOptions"
                        optionValue="id"
                        optionLabel="name"
                        :nullable="true"
                        nullLabel="– None –"
                        wire:model.live="allergen.parent_id"
                    />
                    @if($allergen->parent)
                        <div class="text-sm">
                            Aktuell: 
                            <a href="{{ route('foodservice.allergens.show', ['allergen' => $allergen->parent]) }}" class="text-primary underline" wire:navigate>
                                {{ $allergen->parent->name }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if($allergen->children->count() > 0)
                <div class="mb-4">
                    <h4 class="font-semibold mb-2">Children</h4>
                    <div class="space-y-1">
                        @foreach($allergen->children as $child)
                            <a href="{{ route('foodservice.allergens.show', ['allergen' => $child]) }}" class="block text-sm text-primary underline" wire:navigate>
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <x-ui-input-checkbox
                model="allergen.is_active"
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
</x-foodservice-page>