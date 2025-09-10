<div class="d-flex h-full">
    <!-- Linke Spalte -->
    <div class="flex-grow-1 d-flex flex-col">
        <!-- Header oben (fix) -->
        <div class="border-top-1 border-bottom-1 border-muted border-top-solid border-bottom-solid p-2 flex-shrink-0">
            <div class="d-flex gap-1">
                <div class="d-flex">
                    <a href="{{ route('foodservice.articles.index') }}" class="d-flex px-3 border-right-solid border-right-1 border-right-muted underline" wire:navigate>
                        Articles
                    </a>
                </div>
                <div class="flex-grow-1 text-right d-flex items-center justify-end gap-2">
                    <span>{{ $article->name }}</span>
                    @if($this->isDirty)
                        <x-ui-button 
                            variant="primary" 
                            size="sm"
                            wire:click="save"
                        >
                            <div class="d-flex items-center gap-2">
                                @svg('heroicon-o-check', 'w-4 h-4')
                                Save
                            </div>
                        </x-ui-button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Haupt-Content -->
        <div class="flex-grow-1 overflow-y-auto p-4">
            <!-- Statistiken -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-secondary">Statistiken</h3>
                <div class="grid grid-cols-3 gap-4">
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
            </div>

            <!-- Grundinformationen -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-secondary">Grundinformationen</h3>
                <div class="grid grid-cols-2 gap-4">
                    <x-ui-input-text 
                        name="article.name"
                        label="Name"
                        wire:model.live="article.name"
                        required
                        :errorKey="'article.name'"
                    />
                    <x-ui-input-text 
                        name="article.article_number"
                        label="Artikel-Nummer"
                        wire:model.live="article.article_number"
                        :errorKey="'article.article_number'"
                    />
                </div>
                <div class="mt-4">
                    <x-ui-input-text 
                        name="article.ean"
                        label="EAN"
                        wire:model.live="article.ean"
                        :errorKey="'article.ean'"
                    />
                </div>
                <div class="mt-4">
                    <x-ui-input-textarea 
                        name="article.description"
                        label="Beschreibung"
                        wire:model.live="article.description"
                        rows="4"
                        :errorKey="'article.description'"
                    />
                </div>
            </div>

            <!-- Klassifizierung -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-secondary">Klassifizierung</h3>
                <div class="grid grid-cols-2 gap-4">
                    <x-ui-input-select
                        name="article.brand_id"
                        label="Marke"
                        :options="$this->availableBrands"
                        optionValue="id"
                        optionLabel="name"
                        :nullable="true"
                        nullLabel="– Keine Marke –"
                        wire:model.live="article.brand_id"
                    />
                    <x-ui-input-select
                        name="article.manufacturer_id"
                        label="Hersteller"
                        :options="$this->availableManufacturers"
                        optionValue="id"
                        optionLabel="name"
                        :nullable="true"
                        nullLabel="– Kein Hersteller –"
                        wire:model.live="article.manufacturer_id"
                    />
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <x-ui-input-select
                        name="article.article_category_id"
                        label="Kategorie"
                        :options="$this->availableArticleCategories"
                        optionValue="id"
                        optionLabel="name"
                        :nullable="true"
                        nullLabel="– Keine Kategorie –"
                        wire:model.live="article.article_category_id"
                    />
                    <x-ui-input-select
                        name="article.storage_type_id"
                        label="Lagerart"
                        :options="$this->availableStorageTypes"
                        optionValue="id"
                        optionLabel="name"
                        :nullable="true"
                        nullLabel="– Keine Lagerart –"
                        wire:model.live="article.storage_type_id"
                    />
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <x-ui-input-select
                        name="article.base_unit_id"
                        label="Basiseinheit"
                        :options="$this->availableBaseUnits"
                        optionValue="id"
                        optionLabel="name"
                        :nullable="true"
                        nullLabel="– Keine Basiseinheit –"
                        wire:model.live="article.base_unit_id"
                    />
                    <x-ui-input-select
                        name="article.vat_category_id"
                        label="MwSt-Kategorie"
                        :options="$this->availableVatCategories"
                        optionValue="id"
                        optionLabel="name"
                        :nullable="true"
                        nullLabel="– Keine MwSt-Kategorie –"
                        wire:model.live="article.vat_category_id"
                    />
                </div>
            </div>


            <!-- Allergene, Zusatzstoffe und Attribute -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-secondary">Allergene, Zusatzstoffe und Attribute</h3>
                <div class="space-y-4">
                    <!-- Allergene -->
                    <div>
                        <div class="d-flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Allergene</label>
                            <x-ui-button variant="primary" size="sm" wire:click="$set('allergenModalShow', true)">
                                @svg('heroicon-o-plus', 'w-4 h-4 mr-1')
                                Verwalten
                            </x-ui-button>
                        </div>
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

                    <!-- Zusatzstoffe -->
                    <div>
                        <div class="d-flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Zusatzstoffe</label>
                            <x-ui-button variant="primary" size="sm" wire:click="$set('additiveModalShow', true)">
                                @svg('heroicon-o-plus', 'w-4 h-4 mr-1')
                                Verwalten
                            </x-ui-button>
                        </div>
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

                    <!-- Attribute -->
                    <div>
                        <div class="d-flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Attribute</label>
                            <x-ui-button variant="primary" size="sm" wire:click="$set('attributeModalShow', true)">
                                @svg('heroicon-o-plus', 'w-4 h-4 mr-1')
                                Verwalten
                            </x-ui-button>
                        </div>
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

        <!-- Aktivitäten -->
        <div x-data="{ open: false }" class="flex-shrink-0 border-t border-muted">
            <div 
                @click="open = !open" 
                class="cursor-pointer border-top-1 border-top-solid border-top-muted border-bottom-1 border-bottom-solid border-bottom-muted p-2 text-center d-flex items-center justify-center gap-1 mx-2 shadow-lg"
            >
                ACTIVITIES
                <span class="text-xs">
                    {{$article->activities->count()}}
                </span>
                <x-heroicon-o-chevron-double-down 
                    class="w-3 h-3" 
                    x-show="!open"
                />
                <x-heroicon-o-chevron-double-up 
                    class="w-3 h-3" 
                    x-show="open"
                />
            </div>
            <div x-show="open" class="p-2 max-h-xs overflow-y-auto">
                <livewire:activity-log.index
                    :model="$article"
                    :key="get_class($article) . '_' . $article->id"
                />
            </div>
        </div>
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
                    <div><strong>Name:</strong> {{ $article->name }}</div>
                    <div><strong>Artikel-Nr:</strong> {{ $article->article_number ?? 'Nicht angegeben' }}</div>
                    <div><strong>EAN:</strong> {{ $article->ean ?? 'Nicht angegeben' }}</div>
                    <div><strong>Status:</strong> {{ $article->is_active ? 'Aktiv' : 'Inaktiv' }}</div>
                </div>
            </div>

            <x-ui-input-checkbox
                model="article.is_active"
                checked-label="Active"
                unchecked-label="Inactive"
                size="md"
                block="true"
            />

            <div class="mb-4">
                <h4 class="font-semibold mb-2">Marke</h4>
                <div class="space-y-2">
                    @if($article->brand)
                        <div class="text-sm">
                            Aktuell: 
                            <a href="{{ route('foodservice.brands.show', ['brand' => $article->brand]) }}" class="text-primary underline" wire:navigate>
                                {{ $article->brand->name }}
                            </a>
                        </div>
                    @else
                        <div class="text-sm text-gray-500">Keine Marke zugeordnet</div>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <h4 class="font-semibold mb-2">Hersteller</h4>
                <div class="space-y-2">
                    @if($article->manufacturer)
                        <div class="text-sm">
                            Aktuell: 
                            <a href="{{ route('foodservice.manufacturers.show', ['manufacturer' => $article->manufacturer]) }}" class="text-primary underline" wire:navigate>
                                {{ $article->manufacturer->name }}
                            </a>
                        </div>
                    @else
                        <div class="text-sm text-gray-500">Kein Hersteller zugeordnet</div>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <h4 class="font-semibold mb-2">Kategorie</h4>
                <div class="space-y-2">
                    @if($article->articleCategory)
                        <div class="text-sm">
                            Aktuell: 
                            <a href="{{ route('foodservice.article-categories.show', ['category' => $article->articleCategory]) }}" class="text-primary underline" wire:navigate>
                                {{ $article->articleCategory->name }}
                            </a>
                        </div>
                    @else
                        <div class="text-sm text-gray-500">Keine Kategorie zugeordnet</div>
                    @endif
                </div>
            </div>

            <hr>

            <div class="mb-4">
                <h4 class="font-semibold mb-2">Meta</h4>
                <div class="space-y-1 text-xs text-gray-500">
                    <div><strong>UUID:</strong> {{ $article->uuid }}</div>
                    <div><strong>Team ID:</strong> {{ $article->team_id }}</div>
                    <div><strong>Erstellt:</strong> {{ $article->created_at->format('d.m.Y H:i') }}</div>
                    <div><strong>Geändert:</strong> {{ $article->updated_at->format('d.m.Y H:i') }}</div>
                </div>
            </div>

            <x-ui-confirm-button 
                action="deleteItem" 
                text="Delete" 
                confirmText="Wirklich löschen?" 
                variant="danger-outline"
                :icon="@svg('heroicon-o-trash', 'w-4 h-4')->toHtml()"
            />
        </div>
    </div>

    <!-- Allergen Modal -->
    <x-ui-modal wire:model.live="allergenModalShow" size="md">
        <x-slot name="header">
            <div class="d-flex items-center gap-2">
                @svg('heroicon-o-exclamation-triangle', 'w-6 h-6 text-warning')
                Allergene verwalten
            </div>
        </x-slot>

        <div class="space-y-2">
            @foreach($this->availableAllergens as $allergen)
                <div class="d-flex items-center gap-2">
                    <input 
                        type="checkbox" 
                        id="allergen_{{ $allergen->id }}"
                        wire:model.live="selectedAllergens"
                        value="{{ $allergen->id }}"
                        class="rounded border-gray-300"
                    >
                    <label for="allergen_{{ $allergen->id }}" class="text-sm">{{ $allergen->name }}</label>
                </div>
            @endforeach
        </div>

        <x-slot name="footer">
            <div class="d-flex justify-end gap-3">
                <x-ui-button
                    type="button"
                    variant="secondary-outline"
                    @click="$wire.allergenModalShow = false"
                >
                    Abbrechen
                </x-ui-button>
                <x-ui-button
                    type="submit"
                    variant="primary"
                    wire:click="saveAllergens"
                >
                    Speichern
                </x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>

    <!-- Additive Modal -->
    <x-ui-modal wire:model.live="additiveModalShow" size="md">
        <x-slot name="header">
            <div class="d-flex items-center gap-2">
                @svg('heroicon-o-beaker', 'w-6 h-6 text-info')
                Zusatzstoffe verwalten
            </div>
        </x-slot>

        <div class="space-y-2">
            @foreach($this->availableAdditives as $additive)
                <div class="d-flex items-center gap-2">
                    <input 
                        type="checkbox" 
                        id="additive_{{ $additive->id }}"
                        wire:model.live="selectedAdditives"
                        value="{{ $additive->id }}"
                        class="rounded border-gray-300"
                    >
                    <label for="additive_{{ $additive->id }}" class="text-sm">{{ $additive->name }}</label>
                </div>
            @endforeach
        </div>

        <x-slot name="footer">
            <div class="d-flex justify-end gap-3">
                <x-ui-button
                    type="button"
                    variant="secondary-outline"
                    @click="$wire.additiveModalShow = false"
                >
                    Abbrechen
                </x-ui-button>
                <x-ui-button
                    type="submit"
                    variant="primary"
                    wire:click="saveAdditives"
                >
                    Speichern
                </x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>

    <!-- Attribute Modal -->
    <x-ui-modal wire:model.live="attributeModalShow" size="md">
        <x-slot name="header">
            <div class="d-flex items-center gap-2">
                @svg('heroicon-o-tag', 'w-6 h-6 text-primary')
                Attribute verwalten
            </div>
        </x-slot>

        <div class="space-y-3">
            @foreach($this->availableAttributes as $attribute)
                <div class="d-flex items-center gap-2">
                    <label class="text-sm w-32">{{ $attribute->name }}:</label>
                    <input 
                        type="text" 
                        wire:model.live="attributeValues.{{ $attribute->id }}"
                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                        placeholder="Wert eingeben"
                    >
                </div>
            @endforeach
        </div>

        <x-slot name="footer">
            <div class="d-flex justify-end gap-3">
                <x-ui-button
                    type="button"
                    variant="secondary-outline"
                    @click="$wire.attributeModalShow = false"
                >
                    Abbrechen
                </x-ui-button>
                <x-ui-button
                    type="submit"
                    variant="primary"
                    wire:click="saveAttributes"
                >
                    Speichern
                </x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</div>