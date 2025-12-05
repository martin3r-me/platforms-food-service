@props([
    'title' => 'Kontext',
    'width' => 'w-80',
    'defaultOpen' => true,
    'side' => 'left',
])

<x-ui-page-sidebar :title="$title" :width="$width" :defaultOpen="$defaultOpen" :side="$side">
    <div class="p-6 space-y-6">
        {{ $slot }}
    </div>
</x-ui-page-sidebar>

