<div class="d-flex h-full">
    <!-- Linke Spalte -->
    <div class="flex-grow-1 d-flex flex-col">
        <!-- Header oben (fix) -->
        <div class="border-top-1 border-bottom-1 border-muted border-top-solid border-bottom-solid p-2 flex-shrink-0">
            <div class="d-flex gap-1">
                <div class="d-flex">
                    <a href="{{ route('foodservice.manufacturers.index') }}" class="d-flex px-3 border-right-solid border-right-1 border-right-muted underline" wire:navigate>
                        Manufacturers
                    </a>
                </div>
                <div class="flex-grow-1 text-right d-flex items-center justify-end gap-2">
                    <span>{{ $manufacturer->name }}</span>
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

        <!-- Haupt-Content -->
        <div class="flex-grow-1 overflow-y-auto p-4">
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-secondary">Meta</h3>
                <div class="grid grid-cols-2 gap-4">
                    <x-ui-input-text 
                        name="manufacturer.name"
                        label="Name"
                        wire:model.live="manufacturer.name"
                        required
                        :errorKey="'manufacturer.name'"
                    />
                </div>
                <div class="mt-4">
                    <x-ui-input-textarea 
                        name="manufacturer.description"
                        label="Description"
                        wire:model.live="manufacturer.description"
                        rows="4"
                        :errorKey="'manufacturer.description'"
                    />
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-secondary">Brands</h3>
                @if($manufacturer->brands->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($manufacturer->brands as $brand)
                            <x-ui-badge 
                                variant="{{ $brand->pivot->is_primary ? 'primary' : 'secondary' }}" 
                                size="md"
                            >
                                {{ $brand->name }}
                                @if($brand->pivot->is_primary)
                                    <span class="ml-1">★</span>
                                @endif
                            </x-ui-badge>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted">No brands assigned</div>
                @endif
            </div>
            
        </div>

        <!-- Aktivitäten -->
        <div x-data="{ open: false }" class="flex-shrink-0 border-t border-muted">
            <div 
                @click="open = !open" 
                class="cursor-pointer border-top-1 border-top-solid border-top-muted border-bottom-1 border-bottom-solid border-bottom-muted p-2 text-center d-flex items-center justify-center gap-1 mx-2 shadow-lg"
            >
                ACTIVITIES
                <span class="text-xs">
                    {{$manufacturer->activities->count()}}
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
                    :model="$manufacturer"
                    :key="get_class($manufacturer) . '_' . $manufacturer->id"
                />
            </div>
        </div>
    </div>

    <!-- Rechte Spalte -->
    <div class="min-w-80 w-80 d-flex flex-col border-left-1 border-left-solid border-left-muted">
        <div class="d-flex gap-2 border-top-1 border-bottom-1 border-muted border-top-solid border-bottom-solid p-2 flex-shrink-0">
            <x-heroicon-o-cog-6-tooth class="w-6 h-6"/>
            Settings
        </div>
        <div class="flex-grow-1 overflow-y-auto p-4">
            <div class="mb-4 p-3 bg-muted-5 rounded-lg">
                <h4 class="font-semibold mb-2 text-secondary">Overview</h4>
                <div class="space-y-1 text-sm">
                    <div><strong>Name:</strong> {{ $manufacturer->name }}</div>
                    <div><strong>Status:</strong> {{ $manufacturer->is_active ? 'Active' : 'Inactive' }}</div>
                </div>
            </div>

            <x-ui-input-checkbox
                model="manufacturer.is_active"
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
