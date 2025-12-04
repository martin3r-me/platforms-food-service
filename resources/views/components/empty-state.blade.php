@props([
    'icon' => null,
    'title' => 'Keine Einträge',
    'description' => 'Noch keine Daten vorhanden.',
])

<div class="text-center py-10 px-4 border border-dashed border-[var(--ui-border)]/70 rounded-xl bg-[var(--ui-muted-5)]">
    @if($icon)
        @svg($icon, 'w-12 h-12 mx-auto mb-4 text-[var(--ui-muted)]')
    @endif

    <h4 class="text-lg font-semibold text-[var(--ui-secondary)] mb-2">{{ $title }}</h4>
    <p class="text-sm text-[var(--ui-muted)]">{{ $description }}</p>

    @if(trim($slot ?? '') !== '')
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</div>

