<x-foodservice-page
    title="MwSt-Kategorien"
    icon="heroicon-o-banknotes"
    description="Mehrwertsteuersätze und Gültigkeiten"
>
    <x-slot name="sidebar">
        <div class="space-y-3">
            <h3 class="text-sm font-semibold text-[var(--ui-secondary)] uppercase tracking-wider">Aktionen</h3>
            <x-ui-button variant="primary" size="sm" class="w-full justify-center" wire:click="openCreateModal">
                @svg('heroicon-o-plus','w-4 h-4')
                Neue Kategorie
            </x-ui-button>
        </div>
    </x-slot>

    <x-slot name="activity">
        <p class="text-sm text-[var(--ui-muted)]">Keine Aktivitäten verfügbar.</p>
    </x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-[var(--ui-muted)]">Aktuelle Steuerkategorien mit gültigen Sätzen</p>
        <x-ui-button variant="primary" wire:click="openCreateModal">
            @svg('heroicon-o-plus','w-4 h-4')
            Neue Kategorie
        </x-ui-button>
    </div>

    @if($items->count() > 0)
        <x-ui-table compact="true">
            <x-ui-table-header>
                <x-ui-table-header-cell compact="true">Name</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Current rate</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Status</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true" align="right">Action</x-ui-table-header-cell>
            </x-ui-table-header>

            <x-ui-table-body>
                @foreach($items as $item)
                    <x-ui-table-row compact="true" clickable="true" :href="route('foodservice.vat-categories.show', ['category' => $item])" wire:navigate>
                        <x-ui-table-cell compact="true">{{ $item->name }}</x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            @php
                                $current = $item->rates()
                                    ->whereDate('valid_from', '<=', now()->toDateString())
                                    ->where(function ($q) { $q->whereNull('valid_until')->orWhereDate('valid_until', '>=', now()->toDateString()); })
                                    ->orderByDesc('valid_from')
                                    ->first();
                            @endphp
                            @if($current)
                                <div class="text-sm">{{ number_format($current->rate_percent, 2) }} %</div>
                                <div class="text-xs text-muted">
                                    {{ optional($current->valid_from)?->format('Y-m-d') }}
                                    @if($current->valid_until)
                                        – {{ optional($current->valid_until)?->format('Y-m-d') }}
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-muted">–</span>
                            @endif
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            <x-ui-badge variant="{{ $item->is_active ? 'success' : 'secondary' }}" size="sm">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </x-ui-badge>
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true" align="right">
                            <x-ui-button size="sm" variant="secondary" :href="route('foodservice.vat-categories.show', ['category' => $item])" wire:navigate>
                                Open
                            </x-ui-button>
                        </x-ui-table-cell>
                    </x-ui-table-row>
                @endforeach
            </x-ui-table-body>
        </x-ui-table>
    @else
        <div class="text-center py-8 text-sm text-muted">No categories yet</div>
    @endif

    <x-ui-modal wire:model="modalShow" size="md">
        <x-slot name="header">Create VAT Category</x-slot>

        <form wire:submit.prevent="createItem" class="space-y-4">
            <x-ui-input-text name="name" label="Name" wire:model.live="name" required />
            <x-ui-input-textarea name="description" label="Description" wire:model.live="description" rows="3" />
            <x-ui-input-checkbox model="is_active" checked-label="Active" unchecked-label="Inactive" />
        </form>

        <x-slot name="footer">
            <div class="d-flex justify-end gap-2">
                <x-ui-button type="button" variant="secondary-outline" @click="$wire.closeCreateModal()">Cancel</x-ui-button>
                <x-ui-button type="button" variant="primary" wire:click="createItem">Create</x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</x-foodservice-page>


