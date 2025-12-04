<x-foodservice-page
    title="Lieferanten"
    icon="heroicon-o-truck"
    :description="now()->translatedFormat('l, d.m.Y')"
>
    <x-slot name="sidebar">
        <div class="space-y-3">
            <h3 class="text-sm font-semibold text-[var(--ui-secondary)] uppercase tracking-wider">Aktionen</h3>
            <x-ui-button variant="primary" size="sm" class="w-full justify-center" wire:click="openCreateModal">
                @svg('heroicon-o-plus','w-4 h-4')
                Neuer Lieferant
            </x-ui-button>
        </div>
    </x-slot>

    <x-slot name="activity">
        <p class="text-sm text-[var(--ui-muted)]">Keine Aktivitäten verfügbar.</p>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <x-ui-dashboard-tile
                title="Gesamt"
                :count="$this->stats['total']"
                icon="truck"
                variant="primary"
                size="lg"
            />
            
            <x-ui-dashboard-tile
                title="Aktiv"
                :count="$this->stats['active']"
                icon="check-circle"
                variant="success"
                size="lg"
            />
            
            <x-ui-dashboard-tile
                title="Inaktiv"
                :count="$this->stats['inactive']"
                icon="x-circle"
                variant="danger"
                size="lg"
            />
        </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-500 border-b border-gray-200 text-xs uppercase tracking-wide">
                        <th class="px-6 py-3">
                            <button wire:click="sortBy('name')" class="d-flex items-center gap-1 hover:text-gray-700">
                                Name
                                @if($sortField === 'name')
                                    @if($sortDirection === 'asc')
                                        @svg('heroicon-o-chevron-up', 'w-4 h-4')
                                    @else
                                        @svg('heroicon-o-chevron-down', 'w-4 h-4')
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3">Beschreibung</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Erstellt</th>
                        <th class="px-6 py-3 text-right">Aktionen</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($this->suppliers as $supplier)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ $supplier->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">
                                    {{ $supplier->description ?? 'Keine Beschreibung' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <x-ui-badge 
                                    variant="{{ $supplier->is_active ? 'success' : 'secondary' }}" 
                                    size="sm"
                                >
                                    {{ $supplier->is_active ? 'Aktiv' : 'Inaktiv' }}
                                </x-ui-badge>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $supplier->created_at->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="d-flex justify-end gap-2">
                                    <x-ui-button 
                                        size="sm" 
                                        variant="secondary" 
                                        :href="route('foodservice.suppliers.show', ['supplier' => $supplier])" 
                                        wire:navigate
                                    >
                                        Öffnen
                                    </x-ui-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="d-flex flex-col items-center">
                                    @svg('heroicon-o-truck', 'w-12 h-12 text-gray-300 mb-4')
                                    <p class="text-lg font-medium mb-2">Keine Lieferanten vorhanden</p>
                                    <p class="text-sm">Erstellen Sie Ihren ersten Lieferanten, um loszulegen.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-ui-modal wire:model="modalShow" size="lg">
        <x-slot name="header">
            <div class="d-flex items-center gap-3">
                @svg('heroicon-o-truck', 'w-6 h-6 text-primary')
                Neuer Lieferant
            </div>
        </x-slot>

        <form wire:submit.prevent="createSupplier" class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <x-ui-input-text 
                    name="name" 
                    label="Name" 
                    wire:model.live="name" 
                    required 
                    placeholder="z.B. Metro AG"
                />
                <x-ui-input-checkbox 
                    model="is_active" 
                    checked-label="Aktiv" 
                    unchecked-label="Inaktiv"
                />
            </div>

            <x-ui-input-textarea 
                name="description" 
                label="Beschreibung" 
                wire:model.live="description" 
                rows="3"
                placeholder="Optionale Beschreibung des Lieferanten"
            />
        </form>

        <x-slot name="footer">
            <div class="d-flex justify-end gap-3">
                <x-ui-button 
                    type="button" 
                    variant="secondary-outline" 
                    @click="$wire.closeCreateModal()"
                >
                    Abbrechen
                </x-ui-button>
                <x-ui-button 
                    type="button" 
                    variant="primary" 
                    wire:click="createSupplier"
                >
                    Lieferant erstellen
                </x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</x-foodservice-page>
