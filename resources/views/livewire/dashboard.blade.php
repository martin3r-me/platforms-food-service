<div class="h-full overflow-y-auto p-6">
    <div class="mb-6">
        <div class="d-flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Food Service Dashboard</h1>
                <p class="text-gray-600">Aktuelle Übersicht</p>
            </div>
        </div>
    </div>

    <!-- Statistiken (4er Grid) -->
    <div class="grid grid-cols-4 gap-4 mb-8">
        <a href="{{ route('foodservice.allergens.index') }}" wire:navigate>
            <x-ui-dashboard-tile
                title="Allergens"
                :count="$counts['allergens']['total'] ?? 0"
                subtitle="Active: {{ $counts['allergens']['active'] ?? 0 }} · Inactive: {{ $counts['allergens']['inactive'] ?? 0 }}"
                icon="exclamation-triangle"
                variant="warning"
                size="lg"
            />
        </a>

        <a href="{{ route('foodservice.additives.index') }}" wire:navigate>
            <x-ui-dashboard-tile
                title="Additives"
                :count="$counts['additives']['total'] ?? 0"
                subtitle="Active: {{ $counts['additives']['active'] ?? 0 }} · Inactive: {{ $counts['additives']['inactive'] ?? 0 }}"
                icon="beaker"
                variant="primary"
                size="lg"
            />
        </a>

        <a href="{{ route('foodservice.attributes.index') }}" wire:navigate>
            <x-ui-dashboard-tile
                title="Attributes"
                :count="$counts['attributes']['total'] ?? 0"
                subtitle="Active: {{ $counts['attributes']['active'] ?? 0 }} · Inactive: {{ $counts['attributes']['inactive'] ?? 0 }}"
                icon="adjustments-horizontal"
                variant="neutral"
                size="lg"
            />
        </a>

        <a href="{{ route('foodservice.vat-categories.index') }}" wire:navigate>
            <x-ui-dashboard-tile
                title="VAT Categories"
                :count="$counts['vat_categories']['total'] ?? 0"
                subtitle="Active: {{ $counts['vat_categories']['active'] ?? 0 }} · Inactive: {{ $counts['vat_categories']['inactive'] ?? 0 }}"
                icon="banknotes"
                variant="success"
                size="lg"
            />
        </a>
    </div>

    <!-- Zweite Reihe (4er Grid) -->
    <div class="grid grid-cols-4 gap-4 mb-8">
        <a href="{{ route('foodservice.article-clusters.index') }}" wire:navigate>
            <x-ui-dashboard-tile
                title="Article Clusters"
                :count="$counts['article_clusters']['total'] ?? 0"
                subtitle="Active: {{ $counts['article_clusters']['active'] ?? 0 }} · Inactive: {{ $counts['article_clusters']['inactive'] ?? 0 }}"
                icon="rectangle-group"
                variant="info"
                size="lg"
            />
        </a>

        <a href="{{ route('foodservice.article-categories.index') }}" wire:navigate>
            <x-ui-dashboard-tile
                title="Article Categories"
                :count="$counts['article_categories']['total'] ?? 0"
                subtitle="Active: {{ $counts['article_categories']['active'] ?? 0 }} · Inactive: {{ $counts['article_categories']['inactive'] ?? 0 }}"
                icon="tag"
                variant="secondary"
                size="lg"
            />
        </a>

        <a href="{{ route('foodservice.storage-types.index') }}" wire:navigate>
            <x-ui-dashboard-tile
                title="Storage Types"
                :count="$counts['storage_types']['total'] ?? 0"
                subtitle="Active: {{ $counts['storage_types']['active'] ?? 0 }} · Inactive: {{ $counts['storage_types']['inactive'] ?? 0 }}"
                icon="archive-box"
                variant="neutral"
                size="lg"
            />
        </a>

        <a href="{{ route('foodservice.base-units.index') }}" wire:navigate>
            <x-ui-dashboard-tile
                title="Base Units"
                :count="$counts['base_units']['total'] ?? 0"
                subtitle="Active: {{ $counts['base_units']['active'] ?? 0 }} · Inactive: {{ $counts['base_units']['inactive'] ?? 0 }}"
                icon="scale"
                variant="primary"
                size="lg"
            />
        </a>
    </div>

    <!-- Recent Listen (4er Grid) -->
    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 d-flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Recent Allergens</h3>
                <a href="{{ route('foodservice.allergens.index') }}" class="text-xs text-primary underline" wire:navigate>Alle</a>
            </div>
            <div class="p-4 space-y-2 text-sm">
                @forelse(($recent['allergens'] ?? []) as $item)
                    <a href="{{ route('foodservice.allergens.show', ['allergen' => $item]) }}" class="block hover:underline" wire:navigate>
                        {{ $item->name }}
                    </a>
                @empty
                    <div class="text-muted">– No entries –</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 d-flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Recent Additives</h3>
                <a href="{{ route('foodservice.additives.index') }}" class="text-xs text-primary underline" wire:navigate>Alle</a>
            </div>
            <div class="p-4 space-y-2 text-sm">
                @forelse(($recent['additives'] ?? []) as $item)
                    <a href="{{ route('foodservice.additives.show', ['additive' => $item]) }}" class="block hover:underline" wire:navigate>
                        {{ $item->name }}
                    </a>
                @empty
                    <div class="text-muted">– No entries –</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 d-flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Recent Attributes</h3>
                <a href="{{ route('foodservice.attributes.index') }}" class="text-xs text-primary underline" wire:navigate>Alle</a>
            </div>
            <div class="p-4 space-y-2 text-sm">
                @forelse(($recent['attributes'] ?? []) as $item)
                    <a href="{{ route('foodservice.attributes.show', ['attribute' => $item]) }}" class="block hover:underline" wire:navigate>
                        {{ $item->name }}
                    </a>
                @empty
                    <div class="text-muted">– No entries –</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 d-flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Recent VAT Categories</h3>
                <a href="{{ route('foodservice.vat-categories.index') }}" class="text-xs text-primary underline" wire:navigate>Alle</a>
            </div>
            <div class="p-4 space-y-2 text-sm">
                @forelse(($recent['vat_categories'] ?? []) as $item)
                    <a href="{{ route('foodservice.vat-categories.show', ['category' => $item]) }}" class="block hover:underline" wire:navigate>
                        {{ $item->name }}
                    </a>
                @empty
                    <div class="text-muted">– No entries –</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Zweite Reihe Recent (4er Grid) -->
    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 d-flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Recent Article Clusters</h3>
                <a href="{{ route('foodservice.article-clusters.index') }}" class="text-xs text-primary underline" wire:navigate>Alle</a>
            </div>
            <div class="p-4 space-y-2 text-sm">
                @forelse(($recent['article_clusters'] ?? []) as $item)
                    <a href="{{ route('foodservice.article-clusters.show', ['cluster' => $item]) }}" class="block hover:underline" wire:navigate>
                        {{ $item->name }}
                    </a>
                @empty
                    <div class="text-muted">– No entries –</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 d-flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Recent Article Categories</h3>
                <a href="{{ route('foodservice.article-categories.index') }}" class="text-xs text-primary underline" wire:navigate>Alle</a>
            </div>
            <div class="p-4 space-y-2 text-sm">
                @forelse(($recent['article_categories'] ?? []) as $item)
                    <a href="{{ route('foodservice.article-categories.show', ['category' => $item]) }}" class="block hover:underline" wire:navigate>
                        {{ $item->name }}
                    </a>
                @empty
                    <div class="text-muted">– No entries –</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 d-flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Recent Storage Types</h3>
                <a href="{{ route('foodservice.storage-types.index') }}" class="text-xs text-primary underline" wire:navigate>Alle</a>
            </div>
            <div class="p-4 space-y-2 text-sm">
                @forelse(($recent['storage_types'] ?? []) as $item)
                    <a href="{{ route('foodservice.storage-types.show', ['storageType' => $item]) }}" class="block hover:underline" wire:navigate>
                        {{ $item->name }}
                    </a>
                @empty
                    <div class="text-muted">– No entries –</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 d-flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Recent Base Units</h3>
                <a href="{{ route('foodservice.base-units.index') }}" class="text-xs text-primary underline" wire:navigate>Alle</a>
            </div>
            <div class="p-4 space-y-2 text-sm">
                @forelse(($recent['base_units'] ?? []) as $item)
                    <a href="{{ route('foodservice.base-units.show', ['baseUnit' => $item]) }}" class="block hover:underline" wire:navigate>
                        {{ $item->name }}
                    </a>
                @empty
                    <div class="text-muted">– No entries –</div>
                @endforelse
            </div>
        </div>
    </div>
</div>