<x-foodservice-page
    :title="$category->name"
    icon="heroicon-o-tag"
    :breadcrumbs="[
        ['label' => 'Artikel-Kategorien', 'href' => route('foodservice.article-categories.index')],
    ]"
>
    <x-slot name="actions">
        <x-ui-button variant="secondary-outline" :href="route('foodservice.article-categories.index')" wire:navigate>
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
            @if($this->breadcrumbs->count() > 0 || $this->fullPath)
                <div class="p-4 rounded-xl border border-[var(--ui-border)]/50 bg-[var(--ui-muted-5)]">
                    <h4 class="text-sm font-semibold text-[var(--ui-secondary)] mb-2">Pfad</h4>
                    <div class="text-sm text-[var(--ui-secondary)]">
                        {{ $this->fullPath ?? $category->name }}
                    </div>
                </div>
            @endif

            <x-ui-input-select
                name="category.parent_id"
                label="Parent (optional)"
                :options="$this->categoryParentOptions"
                optionValue="id"
                optionLabel="name"
                :nullable="true"
                nullLabel="– Keine –"
                wire:model.live="category.parent_id"
            />

            <x-ui-input-checkbox
                model="category.is_active"
                checked-label="Aktiv"
                unchecked-label="Inaktiv"
                size="md"
                block="true"
            />

            <x-ui-confirm-button 
                action="deleteItem" 
                text="Löschen" 
                confirmText="Wirklich löschen?" 
                variant="danger-outline"
                :icon="@svg('heroicon-o-trash', 'w-4 h-4')->toHtml()"
            />
        </div>
    </x-slot>

    <x-slot name="activity">
        <livewire:activity-log.index
            :model="$category"
            :key="get_class($category) . '_' . $category->id"
        />
    </x-slot>

    <div class="space-y-8">
        <x-ui-panel title="Meta">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui-input-text 
                    name="category.name"
                    label="Name"
                    wire:model.live="category.name"
                    required
                    :errorKey="'category.name'"
                />
                <x-ui-input-select
                    name="category.cluster_id"
                    label="Cluster"
                    :options="$clusters"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="false"
                    wire:model.live="category.cluster_id"
                    :disabled="$category->parent_id !== null"
                    required
                />
            </div>
            <div class="mt-4">
                <x-ui-input-textarea 
                    name="category.description"
                    label="Beschreibung"
                    wire:model.live="category.description"
                    rows="4"
                    :errorKey="'category.description'"
                />
            </div>
        </x-ui-panel>

        <x-ui-panel title="Unterkategorien">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-[var(--ui-muted)]">Verwalte Unterkategorien</p>
                <x-ui-button 
                    variant="primary" 
                    size="sm"
                    wire:click="openCreateModal({{ $category->id }})"
                >
                    @svg('heroicon-o-plus', 'w-4 h-4')
                    Neue Sub-Kategorie
                </x-ui-button>
            </div>

            @if($this->childrenTree->count() > 0)
                <x-ui-table compact="true">
                    <x-ui-table-header>
                        <x-ui-table-header-cell compact="true">Name</x-ui-table-header-cell>
                        <x-ui-table-header-cell compact="true">Cluster</x-ui-table-header-cell>
                        <x-ui-table-header-cell compact="true">Status</x-ui-table-header-cell>
                        <x-ui-table-header-cell compact="true" align="right">Aktionen</x-ui-table-header-cell>
                    </x-ui-table-header>

                    <x-ui-table-body>
                        @foreach($this->childrenTree as $child)
                            <x-ui-table-row compact="true">
                                <x-ui-table-cell compact="true">
                                    <div class="flex items-center gap-2">
                                        @if($child->level > 0)
                                            <span class="text-[var(--ui-muted)]" style="margin-left: {{ ($child->level - 1) * 20 }}px;">
                                                @if($child->level == 1)
                                                    └─
                                                @else
                                                    &nbsp;&nbsp;└─
                                                @endif
                                            </span>
                                        @endif
                                        <a href="{{ route('foodservice.article-categories.show', ['category' => $child]) }}" class="underline" wire:navigate>
                                            {{ $child->name }}
                                        </a>
                                    </div>
                                </x-ui-table-cell>
                                <x-ui-table-cell compact="true">{{ $child->cluster?->name }}</x-ui-table-cell>
                                <x-ui-table-cell compact="true">
                                    <x-ui-badge variant="{{ $child->is_active ? 'success' : 'secondary' }}" size="sm">
                                        {{ $child->is_active ? 'Aktiv' : 'Inaktiv' }}
                                    </x-ui-badge>
                                </x-ui-table-cell>
                                <x-ui-table-cell compact="true" align="right">
                                    <div class="flex gap-1 justify-end">
                                        <x-ui-button 
                                            size="sm" 
                                            variant="primary-outline" 
                                            wire:click="openCreateModal({{ $child->id }})"
                                            title="Sub-Kategorie hinzufügen"
                                        >
                                            @svg('heroicon-o-plus', 'w-4 h-4')
                                        </x-ui-button>
                                        <x-ui-button 
                                            size="sm" 
                                            variant="secondary" 
                                            :href="route('foodservice.article-categories.show', ['category' => $child])" 
                                            wire:navigate
                                        >
                                            Öffnen
                                        </x-ui-button>
                                    </div>
                                </x-ui-table-cell>
                            </x-ui-table-row>
                        @endforeach
                    </x-ui-table-body>
                </x-ui-table>
            @else
                <x-ui-empty-state
                    icon="heroicon-o-rectangle-group"
                    title="Keine Sub-Kategorien"
                    message="Füge die erste Sub-Kategorie hinzu."
                >
                    <x-ui-button variant="primary" size="sm" wire:click="openCreateModal({{ $category->id }})">
                        Sub-Kategorie anlegen
                    </x-ui-button>
                </x-ui-empty-state>
            @endif
        </x-ui-panel>
    </div>

    <x-ui-modal model="modalShow" size="md">
        <x-slot name="header">
            @if($selectedParentId)
                Sub-Kategorie anlegen
            @else
                Kategorie anlegen
            @endif
        </x-slot>

        <form wire:submit.prevent="createItem" class="space-y-4">
            <x-ui-input-text name="name" label="Name" wire:model.live="name" required />
            <x-ui-input-textarea name="description" label="Beschreibung" wire:model.live="description" rows="3" />

            @if($selectedParentId)
                <div class="p-3 bg-[var(--ui-muted-5)] rounded border border-[var(--ui-border)]/50 text-sm">
                    Parent: {{ \Platform\FoodService\Models\FsArticleCategory::find($selectedParentId)?->name }}
                </div>
            @else
                <x-ui-input-select
                    name="cluster_id"
                    label="Cluster"
                    :options="$clusters"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="false"
                    wire:model.live="cluster_id"
                    required
                />
                <x-ui-input-select
                    name="parent_id"
                    label="Parent (optional)"
                    :options="$this->categoryParentOptions"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– None –"
                    wire:model.live="parent_id"
                />
            @endif

            <x-ui-input-checkbox model="is_active" checked-label="Aktiv" unchecked-label="Inaktiv" />
        </form>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-ui-button type="button" variant="secondary-outline" @click="$wire.closeCreateModal()">Abbrechen</x-ui-button>
                <x-ui-button type="button" variant="primary" wire:click="createItem">Speichern</x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</x-foodservice-page>

