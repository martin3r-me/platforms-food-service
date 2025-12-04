@php
    $sections = [
        [
            'label' => 'Übersicht',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'icon'  => 'heroicon-o-home',
                    'route' => 'foodservice.dashboard',
                    'active'=> ['foodservice.dashboard'],
                ],
            ],
        ],
        [
            'label' => 'Artikelstamm',
            'items' => [
                [
                    'label' => 'Artikel',
                    'icon'  => 'heroicon-o-cube',
                    'route' => 'foodservice.articles.index',
                    'active'=> ['foodservice.articles.*'],
                ],
                [
                    'label' => 'Lieferanten',
                    'icon'  => 'heroicon-o-truck',
                    'route' => 'foodservice.suppliers.index',
                    'active'=> ['foodservice.suppliers.*'],
                ],
                [
                    'label' => 'Hersteller',
                    'icon'  => 'heroicon-o-building-office',
                    'route' => 'foodservice.manufacturers.index',
                    'active'=> ['foodservice.manufacturers.*'],
                ],
                [
                    'label' => 'Marken',
                    'icon'  => 'heroicon-o-star',
                    'route' => 'foodservice.brands.index',
                    'active'=> ['foodservice.brands.*'],
                ],
            ],
        ],
        [
            'label' => 'Klassifizierungen',
            'items' => [
                [
                    'label' => 'Artikelkategorien',
                    'icon'  => 'heroicon-o-tag',
                    'route' => 'foodservice.article-categories.index',
                    'active'=> ['foodservice.article-categories.*'],
                ],
                [
                    'label' => 'Artikelcluster',
                    'icon'  => 'heroicon-o-rectangle-group',
                    'route' => 'foodservice.article-clusters.index',
                    'active'=> ['foodservice.article-clusters.*'],
                ],
                [
                    'label' => 'Lagerarten',
                    'icon'  => 'heroicon-o-archive-box',
                    'route' => 'foodservice.storage-types.index',
                    'active'=> ['foodservice.storage-types.*'],
                ],
                [
                    'label' => 'Basiseinheiten',
                    'icon'  => 'heroicon-o-scale',
                    'route' => 'foodservice.base-units.index',
                    'active'=> ['foodservice.base-units.*'],
                ],
                [
                    'label' => 'MwSt-Kategorien',
                    'icon'  => 'heroicon-o-banknotes',
                    'route' => 'foodservice.vat-categories.index',
                    'active'=> ['foodservice.vat-categories.*'],
                ],
            ],
        ],
        [
            'label' => 'Kennzeichnungen',
            'items' => [
                [
                    'label' => 'Allergene',
                    'icon'  => 'heroicon-o-exclamation-triangle',
                    'route' => 'foodservice.allergens.index',
                    'active'=> ['foodservice.allergens.*'],
                ],
                [
                    'label' => 'Zusatzstoffe',
                    'icon'  => 'heroicon-o-beaker',
                    'route' => 'foodservice.additives.index',
                    'active'=> ['foodservice.additives.*'],
                ],
                [
                    'label' => 'Attribute',
                    'icon'  => 'heroicon-o-adjustments-horizontal',
                    'route' => 'foodservice.attributes.index',
                    'active'=> ['foodservice.attributes.*'],
                ],
            ],
        ],
    ];
@endphp

<div class="space-y-8">
    <x-sidebar-module-header module-name="Food Service" icon="heroicon-o-cake" />

    @foreach($sections as $section)
        <div class="space-y-3">
            <p class="text-xs font-semibold tracking-wide uppercase text-[var(--ui-muted)]">{{ $section['label'] }}</p>
            <div class="space-y-1">
                @foreach($section['items'] as $item)
                    @php
                        $isActive = request()->routeIs(...$item['active']);
                    @endphp
                    <a
                        href="{{ route($item['route']) }}"
                        wire:navigate
                        class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                        @class([
                            'bg-[var(--ui-primary)] text-[var(--ui-on-primary)] shadow-sm' => $isActive,
                            'text-[var(--ui-secondary)] hover:bg-[var(--ui-primary-5)] hover:text-[var(--ui-primary)]' => !$isActive,
                        ])
                    >
                        @svg($item['icon'], 'w-5 h-5')
                        <span class="truncate">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</div>