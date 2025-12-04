@props([
    'title' => 'Aktivitäten',
    'items' => [],
    'storeKey' => 'foodserviceActivityOpen',
])

<x-ui-page-sidebar :title="$title" width="w-80" :defaultOpen="false" :storeKey="$storeKey" side="right">
    <div class="p-6 space-y-4 text-sm text-[var(--ui-muted)]">
        @if(trim($slot ?? '') !== '')
            {{ $slot }}
        @else
            @forelse($items as $item)
                <div class="p-3 rounded-lg border border-[var(--ui-border)]/50 bg-[var(--ui-muted-5)] shadow-sm">
                    <div class="font-medium text-[var(--ui-secondary)]">{{ $item['title'] ?? 'Aktivität' }}</div>
                    @if(!empty($item['meta']))
                        <div class="text-xs">{{ $item['meta'] }}</div>
                    @endif
                </div>
            @empty
                <p class="text-sm">Keine Aktivitäten verfügbar.</p>
            @endforelse
        @endif
    </div>
</x-ui-page-sidebar>

