<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $supplier->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Lieferant</p>
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
            <div class="grid grid-cols-1 gap-6 mb-6">
                <!-- Lieferanten Information -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informationen</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                    <p class="text-sm text-gray-900">{{ $supplier->name }}</p>
                                </div>
                                @if($supplier->description)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Beschreibung</label>
                                        <p class="text-sm text-gray-900">{{ $supplier->description }}</p>
                                    </div>
                                @endif
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <x-ui-badge 
                                        variant="{{ $supplier->is_active ? 'success' : 'secondary' }}" 
                                        size="sm"
                                    >
                                        {{ $supplier->is_active ? 'Aktiv' : 'Inaktiv' }}
                                    </x-ui-badge>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel Liste -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Artikel</h3>
                    <div class="text-center py-8 text-gray-500">
                        <div class="d-flex flex-col items-center">
                            @svg('heroicon-o-cube', 'w-12 h-12 text-gray-300 mb-4')
                            <p class="text-lg font-medium mb-2">Keine Artikel vorhanden</p>
                            <p class="text-sm">Artikel-Funktionalität wird später implementiert.</p>
                        </div>
                    </div>
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
