<div>
    <div class="py-8">
        <div class="max-w-full sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Artikel
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Verwalten Sie Ihre Artikel</p>
                </div>
                <div class="d-flex items-center gap-2">
                    <x-ui-button variant="primary" wire:click="openCreateModal">
                        @svg('heroicon-o-plus', 'w-4 h-4 mr-2')
                        Neuer Artikel
                    </x-ui-button>
                </div>
            </div>

            <!-- Statistiken -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <x-ui-dashboard-tile
                    title="Gesamt Artikel"
                    :count="$this->stats['total']"
                    icon="cube"
                    variant="primary"
                    size="sm"
                />

                <x-ui-dashboard-tile
                    title="Aktive Artikel"
                    :count="$this->stats['active']"
                    icon="check-circle"
                    variant="success"
                    size="sm"
                />

                <x-ui-dashboard-tile
                    title="Inaktive Artikel"
                    :count="$this->stats['inactive']"
                    icon="x-circle"
                    variant="secondary"
                    size="sm"
                />
            </div>

            <!-- Artikel Tabelle -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <button wire:click="sortBy('article_number')" class="d-flex items-center gap-1 hover:text-gray-700">
                                            Artikel-Nr.
                                            @if($sortField === 'article_number')
                                                @if($sortDirection === 'asc')
                                                    @svg('heroicon-o-chevron-up', 'w-4 h-4')
                                                @else
                                                    @svg('heroicon-o-chevron-down', 'w-4 h-4')
                                                @endif
                                            @endif
                                        </button>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">EAN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marke</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategorie</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Erstellt</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aktionen</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($this->articles as $article)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">
                                                {{ $article->name }}
                                            </div>
                                            @if($article->description)
                                                <div class="text-sm text-gray-600">
                                                    {{ Str::limit($article->description, 50) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ $article->article_number }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ $article->ean ?? '–' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ $article->brand?->name ?? '–' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ $article->articleCategory?->name ?? '–' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-ui-badge
                                                variant="{{ $article->is_active ? 'success' : 'secondary' }}"
                                                size="sm"
                                            >
                                                {{ $article->is_active ? 'Aktiv' : 'Inaktiv' }}
                                            </x-ui-badge>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ $article->created_at->format('d.m.Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="d-flex items-center justify-end gap-2">
                                                <a href="{{ route('foodservice.articles.show', ['article' => $article]) }}" 
                                                   class="text-primary hover:text-primary-600" wire:navigate>
                                                    @svg('heroicon-o-eye', 'w-4 h-4')
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                            <div class="d-flex flex-col items-center">
                                                @svg('heroicon-o-cube', 'w-12 h-12 text-gray-300 mb-4')
                                                <p class="text-lg font-medium mb-2">Keine Artikel vorhanden</p>
                                                <p class="text-sm">Erstellen Sie Ihren ersten Artikel, um loszulegen.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <x-ui-modal wire:model="modalShow" size="lg">
        <x-slot name="header">
            <div class="d-flex items-center gap-2">
                @svg('heroicon-o-plus', 'w-6 h-6 text-primary')
                Neuer Artikel
            </div>
        </x-slot>

        <form wire:submit.prevent="createArticle" class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <x-ui-input-text
                    name="name"
                    label="Name"
                    wire:model.live="name"
                    required
                    placeholder="z.B. Coca-Cola Classic"
                />
                <x-ui-input-text
                    name="article_number"
                    label="Artikel-Nummer"
                    wire:model.live="article_number"
                    placeholder="z.B. ART-001"
                />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-ui-input-text
                    name="ean"
                    label="EAN"
                    wire:model.live="ean"
                    placeholder="z.B. 1234567890123"
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
                placeholder="Optionale Beschreibung des Artikels"
            />

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Marke</label>
                    <select wire:model.live="brand_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">Keine Marke</option>
                        @foreach($this->availableBrands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hersteller</label>
                    <select wire:model.live="manufacturer_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">Kein Hersteller</option>
                        @foreach($this->availableManufacturers as $manufacturer)
                            <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategorie</label>
                    <select wire:model.live="article_category_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">Keine Kategorie</option>
                        @foreach($this->availableArticleCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lagerart</label>
                    <select wire:model.live="storage_type_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">Keine Lagerart</option>
                        @foreach($this->availableStorageTypes as $storageType)
                            <option value="{{ $storageType->id }}">{{ $storageType->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Basiseinheit</label>
                    <select wire:model.live="base_unit_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">Keine Basiseinheit</option>
                        @foreach($this->availableBaseUnits as $baseUnit)
                            <option value="{{ $baseUnit->id }}">{{ $baseUnit->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">MwSt-Kategorie</label>
                    <select wire:model.live="vat_category_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">Keine MwSt-Kategorie</option>
                        @foreach($this->availableVatCategories as $vatCategory)
                            <option value="{{ $vatCategory->id }}">{{ $vatCategory->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <x-slot name="footer">
            <div class="d-flex justify-end gap-3">
                <x-ui-button
                    type="button"
                    variant="secondary-outline"
                    wire:click="closeCreateModal"
                >
                    Abbrechen
                </x-ui-button>
                <x-ui-button
                    type="submit"
                    variant="primary"
                    wire:click="createArticle"
                >
                    Erstellen
                </x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</div>
