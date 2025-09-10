<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $article->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Artikel</p>
            </div>
            <div class="d-flex items-center gap-2">
                <x-ui-button variant="secondary" wire:click="$set('settingsModalShow', true)">
                    @svg('heroicon-o-cog-6-tooth', 'w-4 h-4 mr-2')
                    Einstellungen
                </x-ui-button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full sm:px-6 lg:px-8">
            <!-- Statistiken -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <x-ui-dashboard-tile
                    title="Allergene"
                    :count="$this->stats['total_allergens']"
                    icon="exclamation-triangle"
                    variant="warning"
                    size="sm"
                />

                <x-ui-dashboard-tile
                    title="Zusatzstoffe"
                    :count="$this->stats['total_additives']"
                    icon="beaker"
                    variant="info"
                    size="sm"
                />

                <x-ui-dashboard-tile
                    title="Attribute"
                    :count="$this->stats['total_attributes']"
                    icon="tag"
                    variant="primary"
                    size="sm"
                />
            </div>

            <!-- Artikel Details -->
            <div class="grid grid-cols-1 gap-6 mb-6">
                <!-- Grundinformationen -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Grundinformationen</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                        <p class="text-sm text-gray-900">{{ $article->name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Artikel-Nummer</label>
                                        <p class="text-sm text-gray-900">{{ $article->article_number }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">EAN</label>
                                        <p class="text-sm text-gray-900">{{ $article->ean ?? 'Nicht angegeben' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <x-ui-badge
                                            variant="{{ $article->is_active ? 'success' : 'secondary' }}"
                                            size="sm"
                                        >
                                            {{ $article->is_active ? 'Aktiv' : 'Inaktiv' }}
                                        </x-ui-badge>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    @if($article->description)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Beschreibung</label>
                                            <p class="text-sm text-gray-900">{{ $article->description }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Erstellt am</label>
                                        <p class="text-sm text-gray-900">{{ $article->created_at->format('d.m.Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Zuletzt aktualisiert</label>
                                        <p class="text-sm text-gray-900">{{ $article->updated_at->format('d.m.Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Klassifizierung -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Klassifizierung</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Marke</label>
                                        <p class="text-sm text-gray-900">{{ $article->brand?->name ?? 'Nicht zugeordnet' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Hersteller</label>
                                        <p class="text-sm text-gray-900">{{ $article->manufacturer?->name ?? 'Nicht zugeordnet' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategorie</label>
                                        <p class="text-sm text-gray-900">{{ $article->articleCategory?->name ?? 'Nicht zugeordnet' }}</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Lagerart</label>
                                        <p class="text-sm text-gray-900">{{ $article->storageType?->name ?? 'Nicht zugeordnet' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Basiseinheit</label>
                                        <p class="text-sm text-gray-900">{{ $article->baseUnit?->name ?? 'Nicht zugeordnet' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">MwSt-Kategorie</label>
                                        <p class="text-sm text-gray-900">{{ $article->vatCategory?->name ?? 'Nicht zugeordnet' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gewicht und Volumen -->
                @if($article->net_weight || $article->gross_weight || $article->net_volume || $article->gross_volume)
                    <div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Gewicht und Volumen</h3>
                                <div class="grid grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        @if($article->net_weight)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Nettogewicht</label>
                                                <p class="text-sm text-gray-900">{{ $article->net_weight }} kg</p>
                                            </div>
                                        @endif
                                        @if($article->gross_weight)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Bruttogewicht</label>
                                                <p class="text-sm text-gray-900">{{ $article->gross_weight }} kg</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="space-y-4">
                                        @if($article->net_volume)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Netto-Volumen</label>
                                                <p class="text-sm text-gray-900">{{ $article->net_volume }} l</p>
                                            </div>
                                        @endif
                                        @if($article->gross_volume)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Brutto-Volumen</label>
                                                <p class="text-sm text-gray-900">{{ $article->gross_volume }} l</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Nährwerte -->
                @if($article->nutritional_info && count($article->nutritional_info) > 0)
                    <div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Nährwerte</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach($article->nutritional_info as $key => $value)
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-gray-700">{{ ucfirst($key) }}:</span>
                                            <span class="text-sm text-gray-900">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Allergene, Zusatzstoffe und Attribute -->
            <div class="grid grid-cols-1 gap-6">
                <!-- Allergene -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Allergene</h3>
                        @if($article->allergens->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($article->allergens as $allergen)
                                    <x-ui-badge variant="warning" size="sm">
                                        {{ $allergen->name }}
                                    </x-ui-badge>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Keine Allergene zugeordnet</p>
                        @endif
                    </div>
                </div>

                <!-- Zusatzstoffe -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Zusatzstoffe</h3>
                        @if($article->additives->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($article->additives as $additive)
                                    <x-ui-badge variant="info" size="sm">
                                        {{ $additive->name }}
                                    </x-ui-badge>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Keine Zusatzstoffe zugeordnet</p>
                        @endif
                    </div>
                </div>

                <!-- Attribute -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Attribute</h3>
                        @if($article->attributes->count() > 0)
                            <div class="space-y-2">
                                @foreach($article->attributes as $attribute)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700">{{ $attribute->name }}:</span>
                                        <span class="text-sm text-gray-900">{{ $attribute->pivot->value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Keine Attribute zugeordnet</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <x-ui-modal wire:model="settingsModalShow" size="md">
        <x-slot name="header">
            <div class="d-flex items-center gap-2">
                @svg('heroicon-o-cog-6-tooth', 'w-6 h-6 text-primary')
                Einstellungen
            </div>
        </x-slot>

        <form wire:submit.prevent="saveSettings" class="space-y-6">
            <x-ui-input-text
                name="article.name"
                label="Name"
                wire:model.live="article.name"
                required
            />

            <x-ui-input-text
                name="article.article_number"
                label="Artikel-Nummer"
                wire:model.live="article.article_number"
                required
            />

            <x-ui-input-text
                name="article.ean"
                label="EAN"
                wire:model.live="article.ean"
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
                    type="submit"
                    variant="primary"
                    wire:click="saveSettings"
                >
                    Speichern
                </x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</div>
