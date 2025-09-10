<div class="d-flex h-full">
    <div class="flex-grow-1 d-flex flex-col">
        <div class="border-top-1 border-bottom-1 border-muted border-top-solid border-bottom-solid p-2 flex-shrink-0">
            <div class="d-flex gap-1">
                <div class="d-flex">
                    <a href="{{ route('foodservice.article-clusters.index') }}" class="d-flex px-3 border-right-solid border-right-1 border-right-muted underline" wire:navigate>
                        Article Clusters
                    </a>
                </div>
                <div class="flex-grow-1 text-right d-flex items-center justify-end gap-2">
                    <span>{{ $cluster->name }}</span>
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
                <x-ui-input-text 
                    name="cluster.name"
                    label="Name"
                    wire:model.live="cluster.name"
                    required
                    :errorKey="'cluster.name'"
                />
                <div class="mt-4">
                    <x-ui-input-textarea 
                        name="cluster.description"
                        label="Description"
                        wire:model.live="cluster.description"
                        rows="4"
                        :errorKey="'cluster.description'"
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
            <x-ui-input-checkbox
                model="cluster.is_active"
                checked-label="Active"
                unchecked-label="Inactive"
                size="md"
                block="true"
            />

            <hr>

            <div class="mt-3">
                <x-ui-confirm-button 
                    action="deleteCluster"
                    text="Delete Cluster" 
                    confirmText="Wirklich löschen?" 
                    variant="danger-outline"
                    :icon="@svg('heroicon-o-trash', 'w-4 h-4')->toHtml()"
                />
            </div>
        </div>
    </div>
</div>


