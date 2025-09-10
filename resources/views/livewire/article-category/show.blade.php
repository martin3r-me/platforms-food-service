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
                :options="$this->parentOptions"
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


