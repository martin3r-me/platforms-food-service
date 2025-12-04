<x-foodservice-page
    title="Food Service"
    icon="heroicon-o-cake"
    description="Alle Stammdaten rund um Artikel, Lieferanten und Klassifizierungen"
>
    <x-slot name="actions">
        <x-ui-button variant="primary" :href="route('foodservice.articles.index')" wire:navigate>
            <span class="inline-flex items-center gap-2">
                @svg('heroicon-o-cube','w-4 h-4')
                Artikel öffnen
            </span>
        </x-ui-button>
    </x-slot>

    <x-slot name="sidebar">
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-[var(--ui-secondary)] uppercase tracking-wider">Schnellaktionen</h3>
            <div class="space-y-2">
                <x-ui-button variant="secondary-outline" :href="route('foodservice.suppliers.index')" wire:navigate class="w-full justify-start">
                    @svg('heroicon-o-truck','w-4 h-4')
                    <span>Lieferanten</span>
                </x-ui-button>
                <x-ui-button variant="secondary-outline" :href="route('foodservice.manufacturers.index')" wire:navigate class="w-full justify-start">
                    @svg('heroicon-o-building-office','w-4 h-4')
                    <span>Hersteller</span>
                </x-ui-button>
            </div>
        </div>

        @php
            $quickStats = [
                ['label' => 'Allergene', 'value' => $counts['allergens']['total'] ?? 0],
                ['label' => 'Zusatzstoffe', 'value' => $counts['additives']['total'] ?? 0],
                ['label' => 'Attribute', 'value' => $counts['attributes']['total'] ?? 0],
                ['label' => 'Lieferanten', 'value' => $counts['suppliers']['total'] ?? 0],
            ];
        @endphp

        <div>
            <h3 class="text-sm font-semibold text-[var(--ui-secondary)] uppercase tracking-wider mb-3">Stammdaten</h3>
            <div class="space-y-3">
                @foreach($quickStats as $stat)
                    <div class="p-3 rounded-lg border border-[var(--ui-border)]/50 bg-[var(--ui-muted-5)]">
                        <p class="text-xs text-[var(--ui-muted)]">{{ $stat['label'] }}</p>
                        <p class="text-xl font-semibold text-[var(--ui-secondary)]">{{ $stat['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </x-slot>

    <x-slot name="activity">
        <div class="space-y-4 w-full">
            @foreach(($recent ?? []) as $group => $items)
                <div>
                    <p class="text-xs uppercase font-semibold tracking-wide text-[var(--ui-muted)] mb-2">
                        {{ \Illuminate\Support\Str::of($group)->replace('_', ' ')->title() }}
                    </p>
                    <div class="space-y-2">
                        @forelse($items as $entry)
                            <div class="p-2 rounded border border-[var(--ui-border)]/40 bg-[var(--ui-muted-5)]">
                                <p class="font-medium text-[var(--ui-secondary)] truncate">{{ $entry->name ?? $entry->supplier_number ?? '–' }}</p>
                                @if($entry->updated_at)
                                    <p class="text-xs text-[var(--ui-muted)]">{{ $entry->updated_at->diffForHumans() }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-[var(--ui-muted)]">Keine Einträge</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </x-slot>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('foodservice.allergens.index') }}" wire:navigate>
                <x-ui-dashboard-tile
                    title="Allergene"
                    :count="$counts['allergens']['total'] ?? 0"
                    :subtitle="'Aktiv: '.($counts['allergens']['active'] ?? 0).' • Inaktiv: '.($counts['allergens']['inactive'] ?? 0)"
                    icon="exclamation-triangle"
                    variant="warning"
                    size="lg"
                />
            </a>

            <a href="{{ route('foodservice.additives.index') }}" wire:navigate>
                <x-ui-dashboard-tile
                    title="Zusatzstoffe"
                    :count="$counts['additives']['total'] ?? 0"
                    :subtitle="'Aktiv: '.($counts['additives']['active'] ?? 0).' • Inaktiv: '.($counts['additives']['inactive'] ?? 0)"
                    icon="beaker"
                    variant="primary"
                    size="lg"
                />
            </a>

            <a href="{{ route('foodservice.attributes.index') }}" wire:navigate>
                <x-ui-dashboard-tile
                    title="Attribute"
                    :count="$counts['attributes']['total'] ?? 0"
                    :subtitle="'Aktiv: '.($counts['attributes']['active'] ?? 0).' • Inaktiv: '.($counts['attributes']['inactive'] ?? 0)"
                    icon="adjustments-horizontal"
                    variant="neutral"
                    size="lg"
                />
            </a>

            <a href="{{ route('foodservice.vat-categories.index') }}" wire:navigate>
                <x-ui-dashboard-tile
                    title="MwSt-Kategorien"
                    :count="$counts['vat_categories']['total'] ?? 0"
                    :subtitle="'Aktiv: '.($counts['vat_categories']['active'] ?? 0).' • Inaktiv: '.($counts['vat_categories']['inactive'] ?? 0)"
                    icon="banknotes"
                    variant="success"
                    size="lg"
                />
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('foodservice.article-clusters.index') }}" wire:navigate>
                <x-ui-dashboard-tile
                    title="Artikel-Cluster"
                    :count="$counts['article_clusters']['total'] ?? 0"
                    :subtitle="'Aktiv: '.($counts['article_clusters']['active'] ?? 0).' • Inaktiv: '.($counts['article_clusters']['inactive'] ?? 0)"
                    icon="rectangle-group"
                    variant="info"
                    size="lg"
                />
            </a>

            <a href="{{ route('foodservice.article-categories.index') }}" wire:navigate>
                <x-ui-dashboard-tile
                    title="Artikel-Kategorien"
                    :count="$counts['article_categories']['total'] ?? 0"
                    :subtitle="'Aktiv: '.($counts['article_categories']['active'] ?? 0).' • Inaktiv: '.($counts['article_categories']['inactive'] ?? 0)"
                    icon="tag"
                    variant="secondary"
                    size="lg"
                />
            </a>

            <a href="{{ route('foodservice.storage-types.index') }}" wire:navigate>
                <x-ui-dashboard-tile
                    title="Lagerarten"
                    :count="$counts['storage_types']['total'] ?? 0"
                    :subtitle="'Aktiv: '.($counts['storage_types']['active'] ?? 0).' • Inaktiv: '.($counts['storage_types']['inactive'] ?? 0)"
                    icon="archive-box"
                    variant="neutral"
                    size="lg"
                />
            </a>

            <a href="{{ route('foodservice.base-units.index') }}" wire:navigate>
                <x-ui-dashboard-tile
                    title="Basiseinheiten"
                    :count="$counts['base_units']['total'] ?? 0"
                    :subtitle="'Aktiv: '.($counts['base_units']['active'] ?? 0).' • Inaktiv: '.($counts['base_units']['inactive'] ?? 0)"
                    icon="scale"
                    variant="primary"
                    size="lg"
                />
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('foodservice.brands.index') }}" wire:navigate>
                <x-ui-dashboard-tile
                    title="Marken"
                    :count="$counts['brands']['total'] ?? 0"
                    :subtitle="'Aktiv: '.($counts['brands']['active'] ?? 0).' • Inaktiv: '.($counts['brands']['inactive'] ?? 0)"
                    icon="star"
                    variant="warning"
                    size="lg"
                />
            </a>

            <a href="{{ route('foodservice.manufacturers.index') }}" wire:navigate>
                <x-ui-dashboard-tile
                    title="Hersteller"
                    :count="$counts['manufacturers']['total'] ?? 0"
                    :subtitle="'Aktiv: '.($counts['manufacturers']['active'] ?? 0).' • Inaktiv: '.($counts['manufacturers']['inactive'] ?? 0)"
                    icon="building-office"
                    variant="info"
                    size="lg"
                />
            </a>

            <a href="{{ route('foodservice.suppliers.index') }}" wire:navigate>
                <x-ui-dashboard-tile
                    title="Lieferanten"
                    :count="$counts['suppliers']['total'] ?? 0"
                    :subtitle="'Aktiv: '.($counts['suppliers']['active'] ?? 0).' • Inaktiv: '.($counts['suppliers']['inactive'] ?? 0)"
                    icon="truck"
                    variant="primary"
                    size="lg"
                />
            </a>
        </div>

        <div class="space-y-8">
            @php
                $recentGroups = [
                    'Allergene' => ['items' => $recent['allergens'] ?? [], 'route' => fn ($item) => route('foodservice.allergens.show', ['allergen' => $item])],
                    'Zusatzstoffe' => ['items' => $recent['additives'] ?? [], 'route' => fn ($item) => route('foodservice.additives.show', ['additive' => $item])],
                    'Attribute' => ['items' => $recent['attributes'] ?? [], 'route' => fn ($item) => route('foodservice.attributes.show', ['attribute' => $item])],
                    'MwSt-Kategorien' => ['items' => $recent['vat_categories'] ?? [], 'route' => fn ($item) => route('foodservice.vat-categories.show', ['category' => $item])],
                    'Artikel-Cluster' => ['items' => $recent['article_clusters'] ?? [], 'route' => fn ($item) => route('foodservice.article-clusters.show', ['cluster' => $item])],
                    'Artikel-Kategorien' => ['items' => $recent['article_categories'] ?? [], 'route' => fn ($item) => route('foodservice.article-categories.show', ['category' => $item])],
                    'Lagerarten' => ['items' => $recent['storage_types'] ?? [], 'route' => fn ($item) => route('foodservice.storage-types.show', ['storageType' => $item])],
                    'Basiseinheiten' => ['items' => $recent['base_units'] ?? [], 'route' => fn ($item) => route('foodservice.base-units.show', ['baseUnit' => $item])],
                    'Marken' => ['items' => $recent['brands'] ?? [], 'route' => fn ($item) => route('foodservice.brands.show', ['brand' => $item])],
                    'Hersteller' => ['items' => $recent['manufacturers'] ?? [], 'route' => fn ($item) => route('foodservice.manufacturers.show', ['manufacturer' => $item])],
                    'Lieferanten' => ['items' => $recent['suppliers'] ?? [], 'route' => fn ($item) => route('foodservice.suppliers.show', ['supplier' => $item])],
                ];
            @endphp

            @foreach(array_chunk($recentGroups, 3, true) as $chunk)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @foreach($chunk as $title => $group)
                        <x-ui-panel :title="$title" subtitle="Neueste Einträge">
                            <div class="space-y-2">
                                @forelse($group['items'] as $item)
                                    <a
                                        href="{{ $group['route']($item) }}"
                                        wire:navigate
                                        class="flex items-center justify-between p-2 rounded-lg border border-[var(--ui-border)]/40 hover:border-[var(--ui-primary)]/60 transition-colors"
                                    >
                                        <span class="truncate font-medium text-[var(--ui-secondary)]">{{ $item->name ?? $item->supplier_number ?? '–' }}</span>
                                        @svg('heroicon-o-arrow-right','w-4 h-4 text-[var(--ui-muted)]')
                                    </a>
                                @empty
                                    <p class="text-sm text-[var(--ui-muted)]">Keine Einträge vorhanden.</p>
                                @endforelse
                            </div>
                        </x-ui-panel>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</x-foodservice-page>