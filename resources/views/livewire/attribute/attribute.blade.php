<div class="d-flex h-full">
    <div class="flex-grow-1 d-flex flex-col">
        <div class="border-top-1 border-bottom-1 border-muted border-top-solid border-bottom-solid p-2 flex-shrink-0">
            <div class="d-flex gap-1">
                <div class="d-flex">
                    <a href="{{ route('food-service.attributes.index') }}" class="d-flex px-3 border-right-solid border-right-1 border-right-muted underline" wire:navigate>
                        Attributes
                    </a>
                </div>
                <div class="flex-grow-1 text-right d-flex items-center justify-end gap-2">
                    <span>{{ $attribute->name }}</span>
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
                        name="attribute.name"
                        label="Name"
                        wire:model.live.debounce.500ms="attribute.name"
                        required
                        :errorKey="'attribute.name'"
                    />
                    <x-ui-input-checkbox
                        model="attribute.is_strict"
                        checked-label="Strict (hard)"
                        unchecked-label="Strict (hard)"
                        size="md"
                        block="true"
                    />
                </div>
                <div class="mt-4">
                    <x-ui-input-textarea 
                        name="attribute.description"
                        label="Description"
                        wire:model.live.debounce.500ms="attribute.description"
                        rows="4"
                        :errorKey="'attribute.description'"
                    />
                </div>
            </div>
        </div>

        <div x-data="{ open: false }" class="flex-shrink-0 border-t border-muted">
            <div 
                @click="open = !open" 
                class="cursor-pointer border-top-1 border-top-solid border-top-muted border-bottom-1 border-bottom-solid border-bottom-muted p-2 text-center d-flex items-center justify-center gap-1 mx-2 shadow-lg"
            >
                ACTIVITIES
                <span class="text-xs">
                    {{$attribute->activities->count()}}
                </span>
                <x-heroicon-o-chevron-double-down 
                    class="w-3 h-3" 
                    x-show="!open"
                />
                <x-heroicon-o-chevron-double-up 
                    class="w-3 h-3" 
                    x-show="open"
                />
            </div>
            <div x-show="open" class="p-2 max-h-xs overflow-y-auto">
                <livewire:activity-log.index
                    :model="$attribute"
                    :key="get_class($attribute) . '_' . $attribute->id"
                />
            </div>
        </div>
    </div>

    <div class="min-w-80 w-80 d-flex flex-col border-left-1 border-left-solid border-left-muted">
        <div class="d-flex gap-2 border-top-1 border-bottom-1 border-muted border-top-solid border-bottom-solid p-2 flex-shrink-0">
            <x-heroicon-o-cog-6-tooth class="w-6 h-6"/>
            Settings
        </div>
        <div class="flex-grow-1 overflow-y-auto p-4">
            <div class="mb-4 p-3 bg-muted-5 rounded-lg">
                <h4 class="font-semibold mb-2 text-secondary">Overview</h4>
                <div class="space-y-1 text-sm">
                    <div><strong>Name:</strong> {{ $attribute->name }}</div>
                    <div><strong>Strict:</strong> {{ $attribute->is_strict ? 'Hard' : 'Soft' }}</div>
                </div>
            </div>

            <x-ui-input-checkbox
                model="attribute.is_active"
                checked-label="Active"
                unchecked-label="Inactive"
                size="md"
                block="true"
            />

            <hr>

            <x-ui-confirm-button 
                action="deleteItem" 
                text="Delete" 
                confirmText="Wirklich löschen?" 
                variant="danger-outline"
                :icon="@svg('heroicon-o-trash', 'w-4 h-4')->toHtml()"
            />
        </div>
    </div>
</div>


