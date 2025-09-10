<div class="h-full overflow-y-auto p-6">
    <div class="mb-6">
        <div class="d-flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Food Service Dashboard</h1>
                <p class="text-gray-600">Aktuelle Übersicht</p>
            </div>
        </div>
    </div>

    <!-- Statistiken (3er Grid) -->
    <div class="grid grid-cols-3 gap-4 mb-8">
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
    </div>

    <!-- Recent Listen (3er Grid) -->
    <div class="grid grid-cols-3 gap-4 mb-8">
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
    </div>
</div>