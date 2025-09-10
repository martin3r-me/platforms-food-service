<x-ui-modal wire:model="modalShow">
    <x-slot name="header">
        <div class="d-flex items-center gap-2">
            @svg('heroicon-o-truck', 'w-5 h-5')
            {{ $isEditing ? 'Lieferanten-Artikel bearbeiten' : 'Neuer Lieferanten-Artikel' }}
        </div>
    </x-slot>

    <div class="space-y-4">
        <x-ui-input-select
            name="supplier_id"
            label="Lieferant"
            :options="$this->suppliers"
            optionValue="id"
            optionLabel="name"
            wire:model.live="supplier_id"
            required
            :errorKey="'supplier_id'"
        />

        <div class="grid grid-cols-2 gap-4">
            <x-ui-input-text
                name="supplier_article_number"
                label="Lieferanten-Artikel-Nr"
                wire:model.live="supplier_article_number"
                :errorKey="'supplier_article_number'"
            />
            <x-ui-input-text
                name="supplier_ean"
                label="Lieferanten-EAN"
                wire:model.live="supplier_ean"
                :errorKey="'supplier_ean'"
            />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <x-ui-input-text
                name="purchase_price"
                label="Einkaufspreis"
                type="number"
                step="0.01"
                wire:model.live="purchase_price"
                :errorKey="'purchase_price'"
            />
            <x-ui-input-select
                name="currency"
                label="Währung"
                :options="$this->currencies"
                optionValue="key"
                optionLabel="value"
                wire:model.live="currency"
                required
                :errorKey="'currency'"
            />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <x-ui-input-text
                name="minimum_order_quantity"
                label="Mindestbestellmenge"
                type="number"
                wire:model.live="minimum_order_quantity"
                :errorKey="'minimum_order_quantity'"
            />
            <x-ui-input-text
                name="delivery_time_days"
                label="Lieferzeit (Tage)"
                type="number"
                wire:model.live="delivery_time_days"
                :errorKey="'delivery_time_days'"
            />
        </div>

        <x-ui-input-textarea
            name="notes"
            label="Notizen"
            wire:model.live="notes"
            rows="3"
            :errorKey="'notes'"
        />

        <x-ui-input-checkbox
            model="is_active"
            checked-label="Aktiv"
            unchecked-label="Inaktiv"
            size="md"
            block="true"
        />
    </div>

    <x-slot name="footer">
        <div class="d-flex gap-2 justify-end">
            <x-ui-button variant="secondary" wire:click="closeModal">
                Abbrechen
            </x-ui-button>
            <x-ui-button variant="primary" wire:click="save">
                {{ $isEditing ? 'Aktualisieren' : 'Erstellen' }}
            </x-ui-button>
        </div>
    </x-slot>
</x-ui-modal>
