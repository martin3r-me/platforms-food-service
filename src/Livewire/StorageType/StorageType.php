<?php

namespace Platform\FoodService\Livewire\StorageType;

use Livewire\Component;
use Platform\FoodService\Models\FsStorageType;
use Illuminate\Validation\Rule;

class StorageType extends Component
{
    public FsStorageType $storageType;
    public bool $isDirty = false;
    public bool $settingsOpen = true;

    public function mount(FsStorageType $storageType): void
    {
        $this->storageType = $storageType;
    }

    protected function rules(): array
    {
        return [
            'storageType.name' => ['required', 'string', 'max:255'],
            'storageType.description' => ['nullable', 'string'],
            'storageType.is_active' => ['boolean'],
            'storageType.parent_id' => [
                'nullable',
                'integer',
                Rule::notIn([$this->storageType->id]),
                'exists:fs_storage_types,id',
            ],
        ];
    }

    public function updated($property): void
    {
        if (str_starts_with($property, 'storageType.')) {
            $this->isDirty = true;
        }
    }

    public function save(): void
    {
        $this->validate();
        $this->storageType->save();
        $this->isDirty = false;
    }

    public function deleteItem(): void
    {
        $this->storageType->delete();
        $this->redirectRoute('foodservice.storage-types.index');
    }

    public function render()
    {
        return view('foodservice::livewire.storage-type.storage-type', [
            'storageType' => $this->storageType,
        ])->layout('platform::layouts.app');
    }

    public function getParentOptionsProperty()
    {
        return FsStorageType::query()
            ->when($this->storageType?->id, fn ($q) => $q->where('id', '!=', $this->storageType->id))
            ->orderBy('name')
            ->get();
    }
}
