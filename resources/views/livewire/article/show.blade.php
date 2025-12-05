<x-foodservice-page
    :title="$article->name"
    icon="heroicon-o-cube"
    :breadcrumbs="[
        ['label' => 'Artikel', 'href' => route('foodservice.articles.index')],
    ]"
>
    <x-slot name="actions">
        <x-ui-button variant="secondary-outline" :href="route('foodservice.articles.index')" wire:navigate>
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
                <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-3">Status & Identität</h4>
                <dl class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-[var(--ui-muted)]">Status</dt>
                        <x-ui-badge :variant="$article->is_active ? 'success' : 'secondary'" size="sm">
                            {{ $article->is_active ? 'Aktiv' : 'Inaktiv' }}
                        </x-ui-badge>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-[var(--ui-muted)]">Artikel-Nr.</dt>
                        <dd class="font-medium text-[var(--ui-secondary)]">{{ $article->article_number ?? '–' }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-[var(--ui-muted)]">EAN</dt>
                        <dd class="font-medium text-[var(--ui-secondary)]">{{ $article->ean ?? '–' }}</dd>
                    </div>
                </dl>
            </div>

            <x-ui-input-checkbox
                model="article.is_active"
                checked-label="Aktiv"
                unchecked-label="Inaktiv"
                size="md"
                block="true"
            />

            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-[var(--ui-muted)]">Marke</p>
                    @if($article->brand)
                        <a href="{{ route('foodservice.brands.show', ['brand' => $article->brand]) }}" wire:navigate class="text-[var(--ui-primary)] hover:underline">
                            {{ $article->brand->name }}
                        </a>
                    @else
                        <p class="text-[var(--ui-muted)]">– Keine Marke –</p>
                    @endif
                </div>
                <div>
                    <p class="text-[var(--ui-muted)]">Hersteller</p>
                    @if($article->manufacturer)
                        <a href="{{ route('foodservice.manufacturers.show', ['manufacturer' => $article->manufacturer]) }}" wire:navigate class="text-[var(--ui-primary)] hover:underline">
                            {{ $article->manufacturer->name }}
                        </a>
                    @else
                        <p class="text-[var(--ui-muted)]">– Kein Hersteller –</p>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="activity">
        <livewire:activity-log.index
            :model="$article"
            :key="get_class($article) . '_' . $article->id"
        />
    </x-slot>

    <div class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <x-ui-dashboard-tile title="Allergene" :count="$this->stats['total_allergens']" icon="exclamation-triangle" variant="warning" size="sm" />
            <x-ui-dashboard-tile title="Zusatzstoffe" :count="$this->stats['total_additives']" icon="beaker" variant="info" size="sm" />
            <x-ui-dashboard-tile title="Attribute" :count="$this->stats['total_attributes']" icon="tag" variant="primary" size="sm" />
            <x-ui-dashboard-tile title="Lieferanten" :count="$this->stats['total_suppliers']" icon="truck" variant="success" size="sm" />
        </div>

        <x-ui-panel title="Grundinformationen">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui-input-text name="article.name" label="Name" wire:model.live="article.name" required :errorKey="'article.name'" />
                <x-ui-input-text name="article.article_number" label="Artikel-Nummer" wire:model.live="article.article_number" :errorKey="'article.article_number'" />
                <x-ui-input-text name="article.ean" label="EAN" wire:model.live="article.ean" :errorKey="'article.ean'" />
            </div>
            <div class="mt-4">
                <x-ui-input-textarea name="article.description" label="Beschreibung" wire:model.live="article.description" rows="4" :errorKey="'article.description'" />
            </div>
        </x-ui-panel>

        <x-ui-panel title="Klassifizierung">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui-input-select name="article.brand_id" label="Marke" :options="$this->availableBrands" optionValue="id" optionLabel="name" :nullable="true" nullLabel="– Keine Marke –" wire:model.live="article.brand_id" />
                <x-ui-input-select name="article.manufacturer_id" label="Hersteller" :options="$this->availableManufacturers" optionValue="id" optionLabel="name" :nullable="true" nullLabel="– Kein Hersteller –" wire:model.live="article.manufacturer_id" />
                <x-ui-input-select name="article.article_category_id" label="Kategorie" :options="$this->availableArticleCategories" optionValue="id" optionLabel="name" :nullable="true" nullLabel="– Keine Kategorie –" wire:model.live="article.article_category_id" />
                <x-ui-input-select name="article.storage_type_id" label="Lagerart" :options="$this->availableStorageTypes" optionValue="id" optionLabel="name" :nullable="true" nullLabel="– Keine Lagerart –" wire:model.live="article.storage_type_id" />
                <x-ui-input-select name="article.base_unit_id" label="Basiseinheit" :options="$this->availableBaseUnits" optionValue="id" optionLabel="name" :nullable="true" nullLabel="– Keine Basiseinheit –" wire:model.live="article.base_unit_id" />
                <x-ui-input-select name="article.vat_category_id" label="MwSt-Kategorie" :options="$this->availableVatCategories" optionValue="id" optionLabel="name" :nullable="true" nullLabel="– Keine MwSt-Kategorie –" wire:model.live="article.vat_category_id" />
            </div>
        </x-ui-panel>

        <x-ui-panel title="Allergene, Zusatzstoffe und Attribute">
            <div class="space-y-5">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-[var(--ui-secondary)]">Allergene</p>
                        <x-ui-button variant="primary" size="sm" wire:click="openModal('allergen')">
                            @svg('heroicon-o-plus', 'w-4 h-4 mr-1')
                            Verwalten
                        </x-ui-button>
                    </div>
                    @if($article->allergens->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($article->allergens as $allergen)
                                <x-ui-badge variant="warning" size="sm">{{ $allergen->name }}</x-ui-badge>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-[var(--ui-muted)]">Keine Allergene zugeordnet.</p>
                    @endif
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-[var(--ui-secondary)]">Zusatzstoffe</p>
                        <x-ui-button variant="primary" size="sm" wire:click="openModal('additive')">
                            @svg('heroicon-o-plus', 'w-4 h-4 mr-1')
                            Verwalten
                        </x-ui-button>
                    </div>
                    @if($article->additives->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($article->additives as $additive)
                                <x-ui-badge variant="info" size="sm">{{ $additive->name }}</x-ui-badge>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-[var(--ui-muted)]">Keine Zusatzstoffe zugeordnet.</p>
                    @endif
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-[var(--ui-secondary)]">Attribute</p>
                        <x-ui-button variant="primary" size="sm" wire:click="openModal('attribute')">
                            @svg('heroicon-o-plus', 'w-4 h-4 mr-1')
                            Verwalten
                        </x-ui-button>
                    </div>
                    @if($article->attributes->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($article->attributes as $attribute)
                                <x-ui-badge variant="primary" size="sm">{{ $attribute->name }}</x-ui-badge>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-[var(--ui-muted)]">Keine Attribute zugeordnet.</p>
                    @endif
                </div>
            </div>
        </x-ui-panel>

        <x-ui-panel title="Lieferanten">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-[var(--ui-muted)]">Verwalte Lieferanten-Beziehungen für diesen Artikel.</p>
                <x-ui-button variant="primary" size="sm" wire:click="openModal('supplier')">
                    @svg('heroicon-o-plus', 'w-4 h-4 mr-1')
                    Neuer Lieferant
                </x-ui-button>
            </div>

            @if($article->supplierArticles->count() > 0)
                <x-ui-table>
                    <x-slot name="header">
                        <x-ui-table-header>Lieferant</x-ui-table-header>
                        <x-ui-table-header>Artikel-Nr</x-ui-table-header>
                        <x-ui-table-header>EAN</x-ui-table-header>
                        <x-ui-table-header>Einkaufspreis</x-ui-table-header>
                        <x-ui-table-header>Lieferzeit</x-ui-table-header>
                        <x-ui-table-header>Status</x-ui-table-header>
                        <x-ui-table-header>Aktionen</x-ui-table-header>
                    </x-slot>

                    @foreach($article->supplierArticles as $supplierArticle)
                        <x-ui-table-row>
                            <x-ui-table-cell>
                                <div class="flex items-center gap-2">
                                    @svg('heroicon-o-truck', 'w-4 h-4 text-[var(--ui-muted)]')
                                    {{ $supplierArticle->supplier->name }}
                                </div>
                            </x-ui-table-cell>
                            <x-ui-table-cell>{{ $supplierArticle->supplier_article_number ?: '–' }}</x-ui-table-cell>
                            <x-ui-table-cell>{{ $supplierArticle->supplier_ean ?: '–' }}</x-ui-table-cell>
                            <x-ui-table-cell>
                                @if($supplierArticle->purchase_price)
                                    {{ number_format($supplierArticle->purchase_price, 2) }} {{ $supplierArticle->currency }}
                                @else
                                    –
                                @endif
                            </x-ui-table-cell>
                            <x-ui-table-cell>
                                @if($supplierArticle->delivery_time_days)
                                    {{ $supplierArticle->delivery_time_days }} Tage
                                @else
                                    –
                                @endif
                            </x-ui-table-cell>
                            <x-ui-table-cell>
                                <x-ui-badge :variant="$supplierArticle->is_active ? 'success' : 'secondary'" size="sm">
                                    {{ $supplierArticle->is_active ? 'Aktiv' : 'Inaktiv' }}
                                </x-ui-badge>
                            </x-ui-table-cell>
                            <x-ui-table-cell>
                                <div class="flex gap-1">
                                    <x-ui-button variant="secondary" size="sm" wire:click="editSupplierArticle({{ $supplierArticle->id }})">
                                        @svg('heroicon-o-pencil', 'w-4 h-4')
                                    </x-ui-button>
                                    <x-ui-confirm-button
                                        action="deleteSupplierArticle"
                                        :params="['supplierArticleId' => $supplierArticle->id]"
                                        text=""
                                        confirmText="Wirklich löschen?"
                                        variant="danger-outline"
                                        size="sm"
                                        :icon="@svg('heroicon-o-trash', 'w-4 h-4')->toHtml()"
                                    />
                                </div>
                            </x-ui-table-cell>
                        </x-ui-table-row>
                    @endforeach
                </x-ui-table>
            @else
                <x-ui-empty-state
                    icon="heroicon-o-truck"
                    title="Keine Lieferanten verknüpft"
                    message="Füge den ersten Lieferanten hinzu, um Einkaufspreise zu pflegen."
                >
                    <x-ui-button variant="primary" size="sm" wire:click="openModal('supplier')">
                        Lieferant hinzufügen
                    </x-ui-button>
                </x-ui-empty-state>
            @endif
        </x-ui-panel>
    </div>
</x-foodservice-page>

<x-ui-modal model="modalShow" size="2xl">
    <x-slot name="header">
        {{ $currentModalTitle }}
    </x-slot>

    @if($modalContent)
        @livewire($modalContent, $modalParams, key($modalContent))
    @endif

    <x-slot name="footer">
        <x-ui-button variant="secondary-outline" wire:click="closeModal">Schließen</x-ui-button>
    </x-slot>
</x-ui-modal>

