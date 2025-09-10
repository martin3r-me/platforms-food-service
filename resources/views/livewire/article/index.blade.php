<div>
    <div class="d-flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Articles</h1>
        <x-ui-button variant="primary" wire:click="openCreateModal">New Article</x-ui-button>
    </div>

    @if($this->articles->count() > 0)
        <x-ui-table compact="true">
            <x-ui-table-header>
                <x-ui-table-header-cell compact="true">Name</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Article Number</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">EAN</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Brand</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Category</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true">Status</x-ui-table-header-cell>
                <x-ui-table-header-cell compact="true" align="right">Action</x-ui-table-header-cell>
            </x-ui-table-header>

            <x-ui-table-body>
                @foreach($this->articles as $article)
                    <x-ui-table-row compact="true" clickable="true" :href="route('foodservice.articles.show', ['article' => $article])" wire:navigate>
                        <x-ui-table-cell compact="true">{{ $article->name }}</x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            {{ $article->article_number ?? '–' }}
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            {{ $article->ean ?? '–' }}
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            {{ $article->brand?->name ?? '–' }}
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            {{ $article->articleCategory?->name ?? '–' }}
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true">
                            <x-ui-badge variant="{{ $article->is_active ? 'success' : 'secondary' }}" size="sm">
                                {{ $article->is_active ? 'Active' : 'Inactive' }}
                            </x-ui-badge>
                        </x-ui-table-cell>
                        <x-ui-table-cell compact="true" align="right">
                            <x-ui-button size="sm" variant="secondary" :href="route('foodservice.articles.show', ['article' => $article])" wire:navigate>
                                Open
                            </x-ui-button>
                        </x-ui-table-cell>
                    </x-ui-table-row>
                @endforeach
            </x-ui-table-body>
        </x-ui-table>
    @else
        <div class="text-center py-8 text-sm text-muted">No articles yet</div>
    @endif

    <x-ui-modal wire:model="modalShow" size="lg">
        <x-slot name="header">Create Article</x-slot>

        <form wire:submit.prevent="createItem" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <x-ui-input-text name="name" label="Name" wire:model.live="name" required />
                <x-ui-input-text name="article_number" label="Article Number" wire:model.live="article_number" />
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <x-ui-input-text name="ean" label="EAN" wire:model.live="ean" />
                <x-ui-input-checkbox model="is_active" checked-label="Active" unchecked-label="Inactive" />
            </div>
            
            <x-ui-input-textarea name="description" label="Description" wire:model.live="description" rows="3" />
            
            <div class="grid grid-cols-2 gap-4">
                <x-ui-input-select
                    name="brand_id"
                    label="Brand"
                    :options="$this->availableBrands"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– None –"
                    wire:model.live="brand_id"
                />
                <x-ui-input-select
                    name="manufacturer_id"
                    label="Manufacturer"
                    :options="$this->availableManufacturers"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– None –"
                    wire:model.live="manufacturer_id"
                />
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <x-ui-input-select
                    name="article_category_id"
                    label="Category"
                    :options="$this->availableArticleCategories"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– None –"
                    wire:model.live="article_category_id"
                />
                <x-ui-input-select
                    name="storage_type_id"
                    label="Storage Type"
                    :options="$this->availableStorageTypes"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– None –"
                    wire:model.live="storage_type_id"
                />
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <x-ui-input-select
                    name="base_unit_id"
                    label="Base Unit"
                    :options="$this->availableBaseUnits"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– None –"
                    wire:model.live="base_unit_id"
                />
                <x-ui-input-select
                    name="vat_category_id"
                    label="VAT Category"
                    :options="$this->availableVatCategories"
                    optionValue="id"
                    optionLabel="name"
                    :nullable="true"
                    nullLabel="– None –"
                    wire:model.live="vat_category_id"
                />
            </div>
        </form>

        <x-slot name="footer">
            <div class="d-flex justify-end gap-2">
                <x-ui-button type="button" variant="secondary-outline" @click="$wire.closeCreateModal()">Cancel</x-ui-button>
                <x-ui-button type="button" variant="primary" wire:click="createItem">Create</x-ui-button>
            </div>
        </x-slot>
    </x-ui-modal>
</div>