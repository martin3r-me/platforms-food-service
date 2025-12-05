<x-foodservice-page
    :title="$supplier->name"
    icon="heroicon-o-truck"
    :breadcrumbs="[
        ['label' => 'Lieferanten', 'href' => route('foodservice.suppliers.index')],
    ]"
>
    <x-slot name="actions">
        <x-ui-button variant="secondary-outline" :href="route('foodservice.suppliers.index')" wire:navigate>
            @svg('heroicon-o-arrow-left','w-4 h-4')
            Übersicht
        </x-ui-button>
        <x-ui-button variant="primary" wire:click="$set('settingsModalShow', true)">
            @svg('heroicon-o-cog-6-tooth','w-4 h-4')
            Einstellungen
        </x-ui-button>
    </x-slot>

    <x-slot name="sidebar">
        <div class="space-y-6">
            <div class="p-4 rounded-xl border border-[var(--ui-border)]/50 bg-[var(--ui-muted-5)]">
                <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-3">Status</h4>
                <x-ui-badge :variant="$supplier->is_active ? 'success' : 'secondary'" size="sm">
                    {{ $supplier->is_active ? 'Aktiv' : 'Inaktiv' }}
                </x-ui-badge>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-2">Beschreibung</h4>
                <p class="text-sm text-[var(--ui-muted)]">
                    {{ $supplier->description ?: 'Keine Beschreibung hinterlegt.' }}
                </p>
            </div>
        </div>
    </x-slot>

    <x-slot name="activity">
        <div class="space-y-4">
            <h4 class="text-sm font-semibold text-[var(--ui-secondary)] uppercase tracking-wider">Aktivitäten</h4>
            <p class="text-sm text-[var(--ui-muted)]">Aktivitätstracking für Lieferanten wird später ergänzt.</p>
        </div>
    </x-slot>

    <div class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-ui-dashboard-tile title="Artikel gesamt" :count="$this->stats['total_articles']" icon="cube" variant="primary" size="sm" />
            <x-ui-dashboard-tile title="Aktive Artikel" :count="$this->stats['active_articles']" icon="check-circle" variant="success" size="sm" />
        </div>

        <x-ui-panel title="Informationen">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-[var(--ui-muted)]">Name</dt>
                    <dd class="font-medium text-[var(--ui-secondary)]">{{ $supplier->name }}</dd>
                </div>
                <div>
                    <dt class="text-[var(--ui-muted)]">Status</dt>
                    <dd>
                        <x-ui-badge :variant="$supplier->is_active ? 'success' : 'secondary'" size="sm">
                            {{ $supplier->is_active ? 'Aktiv' : 'Inaktiv' }}
                        </x-ui-badge>
                    </dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-[var(--ui-muted)] mb-1">Beschreibung</dt>
                    <dd class="text-[var(--ui-secondary)]">
                        {{ $supplier->description ?: 'Keine Beschreibung hinterlegt.' }}
                    </dd>
                </div>
            </dl>
        </x-ui-panel>

        <x-ui-panel title="Artikel">
            <div class="text-sm text-[var(--ui-muted)] mb-4">
                Artikel-Verknüpfungen werden zu einem späteren Zeitpunkt implementiert.
            </div>
            <x-ui-empty-state
                icon="heroicon-o-cube"
                title="Keine Artikel vorhanden"
                message="Sobald Artikel verknüpft sind, erscheinen sie hier."
            />
        </x-ui-panel>
    </div>

    <x-ui-modal wire:model="settingsModalShow" size="md">
        <x-slot name="header">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-cog-6-tooth', 'w-6 h-6 text-[var(--ui-primary)]')
                Einstellungen
            </div>
        </x-slot>

        <form wire:submit.prevent="saveSettings" class="space-y-6">
            <x-ui-input-text
                name="supplier.name"
                label="Name"
                wire:model.live="supplier.name"
                required
            />

            <x-ui-input-textarea
                name="settingsForm.description"
                label="Beschreibung"
                wire:model.live="settingsForm.description"
                rows="3"
            />

            <x-ui-input-checkbox
                model="settingsForm.is_active"
                checked-label="Aktiv"
                unchecked-label="Inaktiv"
            />
        </form>

        <x-slot name="footer">
            <div class="flex justify-end gap-3">
                <x-ui-button type="button" variant="secondary-outline" @click="$wire.settingsModalShow = false">
                    Abbrechen
                </x-ui-button>
                <x-ui-button type="button" variant="primary" wire:click="saveSettings">
                    Speichern
                </x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</x-foodservice-page>

