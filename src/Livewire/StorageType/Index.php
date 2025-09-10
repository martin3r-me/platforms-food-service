<?php

namespace Platform\FoodService\Livewire\StorageType;

use Livewire\Component;
use Platform\FoodService\Models\FsStorageType;

class Index extends Component
{
    public bool $modalShow = false;
    public string $name = '';
    public ?string $description = null;
    public bool $is_active = true;
    public ?int $parent_id = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'parent_id' => ['nullable', 'integer', 'exists:fs_storage_types,id'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'is_active', 'parent_id']);
        $this->is_active = true;
        $this->modalShow = true;
    }

    public function closeCreateModal(): void
    {
        $this->modalShow = false;
    }

    public function createItem(): void
    {
        $this->validate();

        FsStorageType::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'parent_id' => $this->parent_id,
            'team_id' => auth()->user()->current_team_id ?? null,
            'created_by_user_id' => auth()->id() ?? null,
            'owned_by_user_id' => auth()->id() ?? null,
        ]);

        $this->closeCreateModal();
    }

    public function render()
    {
        $items = FsStorageType::query()
            ->orderBy('name')
            ->get();

        return view('foodservice::livewire.storage-type.index', [
            'items' => $items,
        ])->layout('platform::layouts.app');
    }
}
