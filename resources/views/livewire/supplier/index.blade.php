<div>
    <div class="h-full overflow-y-auto p-6">
        <!-- Header mit Datum -->
        <div class="mb-6">
            <div class="d-flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Lieferanten</h1>
                    <p class="text-gray-600">{{ now()->format('l') }}, {{ now()->format('d.m.Y') }}</p>
                </div>
                <x-ui-button variant="primary" wire:click="openCreateModal">
                    @svg('heroicon-o-plus', 'w-4 h-4 mr-2')
                    Neuer Lieferant
                </x-ui-button>
            </div>
        </div>

        <!-- Haupt-Statistiken (3x1 Grid) -->
        <div class="grid grid-cols-3 gap-4 mb-8">
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

        <!-- Lieferanten Tabelle -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-500 border-b border-gray-200 text-xs uppercase tracking-wide">
                            <th class="px-6 py-3">
                                <button wire:click="sortBy('supplier_number')" class="d-flex items-center gap-1 hover:text-gray-700">
                                    Lieferanten-Nr.
                                    @if($sortField === 'supplier_number')
                                        @if($sortDirection === 'asc')
                                            @svg('heroicon-o-chevron-up', 'w-4 h-4')
                                        @else
                                            @svg('heroicon-o-chevron-down', 'w-4 h-4')
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3">Unternehmen</th>
                            <th class="px-6 py-3">Kontakt</th>
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
                                        {{ $supplier->supplier_number }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="d-flex items-center">
                                        @svg('heroicon-o-building-office', 'w-5 h-5 text-gray-400 mr-3')
                                        <div>
                                            <div class="font-medium text-gray-900">
                                                {{ optional($supplier->companyLinks->first()?->company)->name ?? 'Kein Unternehmen verknüpft' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="d-flex items-center">
                                        @svg('heroicon-o-user', 'w-5 h-5 text-gray-400 mr-3')
                                        <div>
                                            <div class="font-medium text-gray-900">
                                                {{ optional($supplier->contactLinks->first()?->contact)->name ?? 'Kein Kontakt verknüpft' }}
                                            </div>
                                        </div>
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
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
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
    </div>

    <!-- Modal für neuen Lieferanten -->
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
                    name="supplier_number" 
                    label="Lieferanten-Nummer" 
                    wire:model.live="supplier_number" 
                    required 
                    placeholder="z.B. L001"
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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unternehmen (optional)</label>
                    <x-ui-input-select
                        name="crm_company_id"
                        :options="$this->availableCompanies"
                        optionValue="id"
                        optionLabel="name"
                        :nullable="true"
                        nullLabel="– Kein Unternehmen –"
                        wire:model.live="crm_company_id"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kontakt (optional)</label>
                    <x-ui-input-select
                        name="crm_contact_id"
                        :options="$this->availableContacts"
                        optionValue="id"
                        optionLabel="name"
                        :nullable="true"
                        nullLabel="– Kein Kontakt –"
                        wire:model.live="crm_contact_id"
                    />
                </div>
            </div>
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
</div>
