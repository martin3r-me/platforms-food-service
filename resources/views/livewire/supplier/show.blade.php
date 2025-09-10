<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ optional($supplier->crmCompanyLinks->first()?->company)->name ?? optional($supplier->crmContactLinks->first()?->contact)->name ?? 'Lieferant' }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Lieferanten-Nr.: {{ $supplier->supplier_number }}</p>
            </div>
            <div class="d-flex items-center gap-2">
                <x-ui-button variant="secondary" wire:click="$set('settingsModalShow', true)">
                    @svg('heroicon-o-cog-6-tooth', 'w-4 h-4 mr-2')
                    Einstellungen
                </x-ui-button>
                <x-ui-button variant="primary" href="#" wire:navigate>
                    @svg('heroicon-o-plus', 'w-4 h-4 mr-2')
                    Artikel hinzufügen
                </x-ui-button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full sm:px-6 lg:px-8">
            <!-- Statistiken -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <x-ui-dashboard-tile
                    title="Artikel Gesamt"
                    :count="$this->stats['total_articles']"
                    icon="cube"
                    variant="primary"
                    size="sm"
                />
                
                <x-ui-dashboard-tile
                    title="Aktive Artikel"
                    :count="$this->stats['active_articles']"
                    icon="check-circle"
                    variant="success"
                    size="sm"
                />
            </div>

            <!-- Lieferanten Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Unternehmen Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Unternehmen</h3>
                            @if($supplier->crmCompanyLinks->count() > 0)
                                @foreach($supplier->crmCompanyLinks as $companyLink)
                                    <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                                        <div class="d-flex items-center mb-2">
                                            @svg('heroicon-o-building-office', 'w-5 h-5 text-gray-400 mr-3')
                                            <h4 class="font-medium text-gray-900">{{ $companyLink->company->name }}</h4>
                                        </div>
                                        @if($companyLink->company->description)
                                            <p class="text-sm text-gray-600 mb-2">{{ $companyLink->company->description }}</p>
                                        @endif
                                        <div class="text-xs text-gray-500">
                                            <p>Erstellt: {{ $companyLink->company->created_at->format('d.m.Y H:i') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <p>Kein Unternehmen verknüpft</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kontakt Information -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Kontakt</h3>
                            @if($supplier->crmContactLinks->count() > 0)
                                @foreach($supplier->crmContactLinks as $contactLink)
                                    <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                                        <div class="d-flex items-center mb-2">
                                            @svg('heroicon-o-user', 'w-5 h-5 text-gray-400 mr-3')
                                            <h4 class="font-medium text-gray-900">{{ $contactLink->contact->name }}</h4>
                                        </div>
                                        @if($contactLink->contact->email)
                                            <p class="text-sm text-gray-600 mb-1">
                                                @svg('heroicon-o-envelope', 'w-4 h-4 inline mr-1')
                                                {{ $contactLink->contact->email }}
                                            </p>
                                        @endif
                                        @if($contactLink->contact->phone)
                                            <p class="text-sm text-gray-600 mb-2">
                                                @svg('heroicon-o-phone', 'w-4 h-4 inline mr-1')
                                                {{ $contactLink->contact->phone }}
                                            </p>
                                        @endif
                                        <div class="text-xs text-gray-500">
                                            <p>Erstellt: {{ $contactLink->contact->created_at->format('d.m.Y H:i') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <p>Kein Kontakt verknüpft</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel Liste -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Artikel</h3>
                    @if($this->supplierArticles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto border-collapse text-sm">
                                <thead class="bg-gray-50">
                                    <tr class="text-left text-gray-500 border-b border-gray-200 text-xs uppercase tracking-wide">
                                        <th class="px-6 py-3">Artikel</th>
                                        <th class="px-6 py-3">EAN</th>
                                        <th class="px-6 py-3">Preis</th>
                                        <th class="px-6 py-3">Status</th>
                                        <th class="px-6 py-3">Erstellt</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($this->supplierArticles as $supplierArticle)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900">
                                                    {{ $supplierArticle->article->name ?? 'Unbekannt' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $supplierArticle->ean ?? '–' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $supplierArticle->price ? number_format($supplierArticle->price, 2) . ' €' : '–' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-ui-badge 
                                                    variant="{{ $supplierArticle->is_active ? 'success' : 'secondary' }}" 
                                                    size="sm"
                                                >
                                                    {{ $supplierArticle->is_active ? 'Aktiv' : 'Inaktiv' }}
                                                </x-ui-badge>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $supplierArticle->created_at->format('d.m.Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <div class="d-flex flex-col items-center">
                                @svg('heroicon-o-cube', 'w-12 h-12 text-gray-300 mb-4')
                                <p class="text-lg font-medium mb-2">Keine Artikel vorhanden</p>
                                <p class="text-sm">Fügen Sie Artikel zu diesem Lieferanten hinzu.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <x-ui-modal wire:model="settingsModalShow" size="md">
        <x-slot name="header">
            <div class="d-flex items-center gap-3">
                @svg('heroicon-o-cog-6-tooth', 'w-6 h-6 text-primary')
                Einstellungen
            </div>
        </x-slot>

        <form wire:submit.prevent="saveSettings" class="space-y-6">
            <x-ui-input-text 
                name="supplier.supplier_number" 
                label="Lieferanten-Nummer" 
                wire:model.live="supplier.supplier_number" 
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
            <div class="d-flex justify-end gap-3">
                <x-ui-button 
                    type="button" 
                    variant="secondary-outline" 
                    @click="$wire.settingsModalShow = false"
                >
                    Abbrechen
                </x-ui-button>
                <x-ui-button 
                    type="button" 
                    variant="primary" 
                    wire:click="saveSettings"
                >
                    Speichern
                </x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</div>
