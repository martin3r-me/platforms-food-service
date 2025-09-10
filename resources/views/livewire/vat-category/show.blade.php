<div class="d-flex h-full">
    <div class="flex-grow-1 d-flex flex-col">
        <div class="border-top-1 border-bottom-1 border-muted border-top-solid border-bottom-solid p-2 flex-shrink-0">
            <div class="d-flex gap-1">
                <div class="d-flex">
                    <a href="{{ route('foodservice.vat-categories.index') }}" class="d-flex px-3 border-right-solid border-right-1 border-right-muted underline" wire:navigate>
                        VAT Categories
                    </a>
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
            <div class="mb-4">
                <div class="d-flex items-center justify-between mb-2">
                    <h4 class="font-semibold">Rates</h4>
                    <x-ui-button size="sm" variant="secondary" wire:click="openRateModal(null)">
                        @svg('heroicon-o-plus', 'w-4 h-4')
                        New
                    </x-ui-button>
                </div>
                <div class="space-y-2">
                    @forelse($rates as $rate)
                        <div class="d-flex items-center justify-between p-2 bg-muted-5 rounded">
                            <div class="text-sm">
                                <strong>{{ number_format($rate->rate_percent, 2) }} %</strong>
                                <span class="text-muted">from {{ optional($rate->valid_from)?->format('Y-m-d') }}</span>
                                @if($rate->valid_until)
                                    <span class="text-muted">to {{ optional($rate->valid_until)?->format('Y-m-d') }}</span>
                                @endif
                            </div>
                            <div class="d-flex items-center gap-2">
                                <x-ui-badge variant="{{ $rate->is_active ? 'success' : 'secondary' }}" size="xs">
                                    {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                </x-ui-badge>
                                <x-ui-button size="sm" variant="secondary-outline" wire:click="openRateModal({{ $rate->id }})">Edit</x-ui-button>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-muted">No rates yet.</div>
                    @endforelse
                </div>
            </div>

            <x-ui-input-checkbox
                model="category.is_active"
                checked-label="Active"
                unchecked-label="Inactive"
                size="md"
                block="true"
            />

        </div>
    </div>

    <x-ui-modal model="rateModalShow" size="md">
        <x-slot name="header">{{ $editingRateId ? 'Edit' : 'Create' }} VAT Rate</x-slot>

        <form wire:submit.prevent="saveRate" class="space-y-4">
            <x-ui-input-text name="rate_percent" label="Rate (%)" wire:model.live="rate_percent" required />
            <div class="grid grid-cols-2 gap-3">
                <x-ui-input-date name="valid_from" label="Valid from" wire:model.live="valid_from" required />
                <x-ui-input-date name="valid_until" label="Valid until" wire:model.live="valid_until" :nullable="true" />
            </div>
            <x-ui-input-checkbox model="rate_is_active" checked-label="Active" unchecked-label="Inactive" />
        </form>

        <x-slot name="footer">
            <div class="d-flex justify-between items-center gap-2">
                <div class="flex-shrink-0">
                    @if($editingRateId)
                        <x-ui-confirm-button 
                            action="deleteRate"
                            wire:args="[{{ $editingRateId }}]"
                            text="Delete" 
                            confirmText="Wirklich löschen?" 
                            variant="danger-outline"
                            :icon="@svg('heroicon-o-trash', 'w-4 h-4')->toHtml()"
                        />
                    @endif
                </div>
                <div class="d-flex justify-end gap-2 flex-shrink-0">
                    <x-ui-button type="button" variant="secondary-outline" wire:click="closeRateModal">Cancel</x-ui-button>
                    <x-ui-button type="button" variant="primary" wire:click="saveRate">Save</x-ui-button>
                </div>
            </div>
        </x-slot>
    </x-ui-modal>
</div>


