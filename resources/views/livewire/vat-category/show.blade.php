<x-foodservice-page
    :title="$category->name"
    icon="heroicon-o-banknotes"
    :breadcrumbs="[
        ['label' => 'MwSt-Kategorien', 'href' => route('foodservice.vat-categories.index')],
    ]"
>
    <x-slot name="actions">
        <x-ui-button variant="secondary-outline" :href="route('foodservice.vat-categories.index')" wire:navigate>
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
                <x-ui-badge :variant="$category->is_active ? 'success' : 'secondary'" size="sm">
                    {{ $category->is_active ? 'Aktiv' : 'Inaktiv' }}
                </x-ui-badge>
            </div>

            <x-ui-input-checkbox
                model="category.is_active"
                checked-label="Aktiv"
                unchecked-label="Inaktiv"
                size="md"
                block="true"
            />

            <x-ui-confirm-button 
                action="deleteCategory" 
                text="Löschen" 
                confirmText="Wirklich löschen?" 
                variant="danger-outline"
                :icon="@svg('heroicon-o-trash', 'w-4 h-4')->toHtml()"
            />
        </div>
    </x-slot>

    <x-slot name="activity">
        <livewire:activity-log.index
            :model="$category"
            :key="get_class($category) . '_' . $category->id"
        />
    </x-slot>

    <div class="space-y-8">
        <x-ui-panel title="Meta">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui-input-text 
                    name="category.name"
                    label="Name"
                    wire:model.live="category.name"
                    required
                    :errorKey="'category.name'"
                />
            </div>
            <div class="mt-4">
                <x-ui-input-textarea 
                    name="category.description"
                    label="Beschreibung"
                    wire:model.live="category.description"
                    rows="4"
                    :errorKey="'category.description'"
                />
            </div>
        </x-ui-panel>

        <x-ui-panel title="Sätze">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-[var(--ui-muted)]">Verwalte Mehrwertsteuersätze</p>
                <x-ui-button size="sm" variant="secondary" wire:click="openRateModal(null)">
                    @svg('heroicon-o-plus', 'w-4 h-4')
                    Neu
                </x-ui-button>
            </div>

            <div class="space-y-2">
                @forelse($rates as $rate)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-[var(--ui-border)]/40 bg-[var(--ui-muted-5)]">
                        <div class="text-sm">
                            <strong>{{ number_format($rate->rate_percent, 2) }} %</strong>
                            <span class="text-[var(--ui-muted)]">ab {{ optional($rate->valid_from)?->format('Y-m-d') }}</span>
                            @if($rate->valid_until)
                                <span class="text-[var(--ui-muted)]">bis {{ optional($rate->valid_until)?->format('Y-m-d') }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <x-ui-badge variant="{{ $rate->is_active ? 'success' : 'secondary' }}" size="xs">
                                {{ $rate->is_active ? 'Aktiv' : 'Inaktiv' }}
                            </x-ui-badge>
                            <x-ui-button size="sm" variant="secondary-outline" wire:click="openRateModal({{ $rate->id }})">
                                Bearbeiten
                            </x-ui-button>
                        </div>
                    </div>
                @empty
                    <x-ui-empty-state
                        icon="heroicon-o-receipt-percent"
                        title="Keine Sätze hinterlegt"
                        message="Füge den ersten MwSt-Satz hinzu."
                    >
                        <x-ui-button size="sm" variant="primary" wire:click="openRateModal(null)">
                            Satz hinzufügen
                        </x-ui-button>
                    </x-ui-empty-state>
                @endforelse
            </div>
        </x-ui-panel>
    </div>

    <x-ui-modal model="rateModalShow" size="md">
        <x-slot name="header">{{ $editingRateId ? 'MwSt-Satz bearbeiten' : 'MwSt-Satz anlegen' }}</x-slot>

        <form wire:submit.prevent="saveRate" class="space-y-4">
            <x-ui-input-text name="rate_percent" label="Satz (%)" wire:model.live="rate_percent" required />
            <div class="grid grid-cols-2 gap-3">
                <x-ui-input-date name="valid_from" label="Gültig ab" wire:model.live="valid_from" required />
                <x-ui-input-date name="valid_until" label="Gültig bis" wire:model.live="valid_until" :nullable="true" />
            </div>
            <x-ui-input-checkbox model="rate_is_active" checked-label="Aktiv" unchecked-label="Inaktiv" />
        </form>

        <x-slot name="footer">
            <div class="flex justify-between items-center gap-2">
                <div class="flex-shrink-0">
                    @if($editingRateId)
                        <x-ui-confirm-button 
                            action="deleteRate"
                            wire:args="[{{ $editingRateId }}]"
                            text="Löschen" 
                            confirmText="Wirklich löschen?" 
                            variant="danger-outline"
                            :icon="@svg('heroicon-o-trash', 'w-4 h-4')->toHtml()"
                        />
                    @endif
                </div>
                <div class="flex gap-2">
                    <x-ui-button type="button" variant="secondary-outline" wire:click="closeRateModal">Abbrechen</x-ui-button>
                    <x-ui-button type="button" variant="primary" wire:click="saveRate">Speichern</x-ui-button>
                </div>
            </div>
        </x-slot>
    </x-ui-modal>
</x-foodservice-page>

