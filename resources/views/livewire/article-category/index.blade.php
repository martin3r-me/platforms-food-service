<div>
    <div class="d-flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Article Categories</h1>
        <x-ui-button variant="primary" wire:click="openCreateModal">New Category</x-ui-button>
    </div>

    @if($items->count() > 0)
        <x-ui-table compact="true">
            <x-ui-table-header>
                <x-ui-table-header-cell compact="true">Name</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Cluster</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Status</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true" align="right">Actions</x-ui-table-header-cell>
            </x-ui-table-header>

            <x-ui-table-body>
                @foreach($items as $item)
                    <x-ui-table-row compact="true">
                        <x-ui-table-cell compact="true">
                            <div class="d-flex items-center gap-2">
                                @if(isset($item->level) && $item->level > 0)
                                    <span class="text-muted" style="margin-left: {{ ($item->level - 1) * 20 }}px;">
                                        @if($item->level == 1)
                                            └─
                                        @else
                                            &nbsp;&nbsp;└─
                                        @endif
                                    </span>
                                @endif
                                <a href="{{ route('foodservice.article-categories.show', ['category' => $item]) }}" class="underline" wire:navigate>
                                    {{ $item->name }}
                                </a>
                            </div>
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true">{{ $item->cluster?->name }}</x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            <x-ui-badge variant="{{ $item->is_active ? 'success' : 'secondary' }}" size="sm">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </x-ui-badge>
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true" align="right">
                            <div class="d-flex gap-1 justify-end">
                                <x-ui-button 
                                    size="sm" 
                                    variant="primary-outline" 
                                    wire:click="openCreateModal({{ $item->id }})"
                                    title="Add Sub-Category"
                                >
                                    @svg('heroicon-o-plus', 'w-4 h-4')
                                </x-ui-button>
                                <x-ui-button 
                                    size="sm" 
                                    variant="secondary" 
                                    :href="route('foodservice.article-categories.show', ['category' => $item])" 
                                    wire:navigate
                                >
                                    Open
                                </x-ui-button>
                            </div>
                        </x-ui-table-cell>
                    </x-ui-table-row>
                @endforeach
            </x-ui-table-body>
        </x-ui-table>
    @else
        <div class="text-center py-8 text-sm text-muted">No categories yet</div>
    @endif

    <x-ui-modal wire:model="modalShow" size="md">
        <x-slot name="header">
            @if($selectedParentId)
                Create Sub-Category
            @else
                Create Article Category
            @endif
        </x-slot>

        <form wire:submit.prevent="createItem" class="space-y-4">
            <x-ui-input-text name="name" label="Name" wire:model.live="name" required />
            <x-ui-input-textarea name="description" label="Description" wire:model.live="description" rows="3" />
            
            @if($selectedParentId)
                {{-- Parent ausgewählt - Cluster und Parent sind bereits gesetzt --}}
                <div class="p-3 bg-muted rounded">
                    <div class="text-sm text-muted mb-1">Parent Category:</div>
                    <div class="font-medium">{{ \Platform\FoodService\Models\FsArticleCategory::find($selectedParentId)?->name }}</div>
                </div>
            @else
                {{-- Neuer Parent - Cluster auswählen --}}
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
                    :options="$this->parentOptions"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– None –"
                    wire:model.live="parent_id"
                />
            @endif
            
            <x-ui-input-checkbox model="is_active" checked-label="Active" unchecked-label="Inactive" />
        </form>

        <x-slot name="footer">
            <div class="d-flex justify-end gap-2">
                <x-ui-button type="button" variant="secondary-outline" @click="$wire.closeCreateModal()">Cancel</x-ui-button>
                <x-ui-button type="button" variant="primary" wire:click="createItem">Create</x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</div>


