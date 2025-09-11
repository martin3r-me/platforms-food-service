<div>
    <div class="d-flex h-full">
        <div class="flex-grow-1 d-flex flex-col">
            <div class="border-top-1 border-bottom-1 border-muted border-top-solid border-bottom-solid p-2 flex-shrink-0">
                <div class="d-flex gap-1">
                    <div class="d-flex">
                        <a href="{{ route('foodservice.article-categories.index') }}" class="d-flex px-3 border-right-solid border-right-1 border-right-muted underline" wire:navigate>
                            Article Categories
                        </a>
                        
                        {{-- Breadcrumb Navigation --}}
                        @if($this->breadcrumbs->count() > 0)
                            @foreach($this->breadcrumbs as $parent)
                                <a href="{{ route('foodservice.article-categories.show', ['category' => $parent]) }}" class="d-flex px-3 border-right-solid border-right-1 border-right-muted underline" wire:navigate>
                                    {{ $parent->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                    <div class="flex-grow-1 text-right d-flex items-center justify-end gap-2">
                        <span>{{ $category->name }}</span>
                        @if($this->isDirty)
                            <x-ui-button 
                                variant="primary" 
                                size="sm"
                                wire:click="save"
                            >
                                <div class="d-flex items-center gap-2">
                                    @svg('heroicon-o-check', 'w-4 h-4')
                                    Save
                                </div>
                            </x-ui-button>
                            <x-ui-confirm-button 
                                action="deleteItem" 
                                text="Delete" 
                                confirmText="Wirklich löschen?" 
                                variant="danger-outline"
                                size="sm"
                                :icon="@svg('heroicon-o-trash', 'w-4 h-4')->toHtml()"
                            />
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex-grow-1 overflow-y-auto p-4">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-secondary">Meta</h3>
                    <div class="grid grid-cols-2 gap-4">
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
                            required
                        />
                    </div>
                    <div class="mt-4">
                        <x-ui-input-textarea 
                            name="category.description"
                            label="Description"
                            wire:model.live="category.description"
                            rows="4"
                            :errorKey="'category.description'"
                        />
                    </div>
                </div>

                {{-- Children Section --}}
                <div class="mb-6">
                    <div class="d-flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-secondary">Children</h3>
                        <x-ui-button 
                            variant="primary" 
                            size="sm"
                            wire:click="openCreateModal({{ $category->id }})"
                        >
                            <div class="d-flex items-center gap-2">
                                @svg('heroicon-o-plus', 'w-4 h-4')
                                New Sub-Category
                            </div>
                        </x-ui-button>
                    </div>
                    
                    @if($this->childrenTree->count() > 0)
                        <x-ui-table compact="true">
                            <x-ui-table-header>
                                <x-ui-table-header-cell compact="true">Name</x-ui-table-header-cell>
                                <x-ui-table-header-cell compact="true">Cluster</x-ui-table-header-cell>
                                <x-ui-table-header-cell compact="true">Status</x-ui-table-header-cell>
                                <x-ui-table-header-cell compact="true" align="right">Actions</x-ui-table-header-cell>
                            </x-ui-table-header>

                            <x-ui-table-body>
                                @foreach($this->childrenTree as $child)
                                    <x-ui-table-row compact="true">
                                        <x-ui-table-cell compact="true">
                                            <div class="d-flex items-center gap-2">
                                                @if($child->level > 0)
                                                    <span class="text-muted" style="margin-left: {{ ($child->level - 1) * 20 }}px;">
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
                                                {{ $child->is_active ? 'Active' : 'Inactive' }}
                                            </x-ui-badge>
                                        </x-ui-table-cell>
                                        <x-ui-table-cell compact="true" align="right">
                                            <div class="d-flex gap-1 justify-end">
                                                <x-ui-button 
                                                    size="sm" 
                                                    variant="primary-outline" 
                                                    wire:click="openCreateModal({{ $child->id }})"
                                                    title="Add Sub-Category"
                                                >
                                                    @svg('heroicon-o-plus', 'w-4 h-4')
                                                </x-ui-button>
                                                <x-ui-button 
                                                    size="sm" 
                                                    variant="secondary" 
                                                    :href="route('foodservice.article-categories.show', ['category' => $child])" 
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
                        <div class="text-center py-8 text-sm text-muted">No sub-categories yet</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="min-w-80 w-80 d-flex flex-col border-left-1 border-left-solid border-left-muted">
            <div class="d-flex gap-2 border-top-1 border-bottom-1 border-muted border-top-solid border-bottom-solid p-2 flex-shrink-0">
                <x-heroicon-o-cog-6-tooth class="w-6 h-6"/>
                Settings
            </div>
            <div class="flex-grow-1 overflow-y-auto p-4">
                {{-- Full Path Display --}}
                @if($this->fullPath)
                    <div class="mb-4 p-3 bg-muted rounded">
                        <div class="text-sm text-muted mb-1">Full Path:</div>
                        <div class="font-medium text-sm">{{ $this->fullPath }}</div>
                    </div>
                @endif

                <x-ui-input-select
                    name="category.parent_id"
                    label="Parent (optional)"
                    :options="$this->categoryParentOptions"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– None –"
                    wire:model.live="category.parent_id"
                />

                <x-ui-input-checkbox
                    model="category.is_active"
                    checked-label="Active"
                    unchecked-label="Inactive"
                    size="md"
                    block="true"
                />
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
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