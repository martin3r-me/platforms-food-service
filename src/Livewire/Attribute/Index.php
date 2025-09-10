<?php

namespace Platform\FoodService\Livewire\Attribute;

use Livewire\Component;
use Platform\FoodService\Models\FsAttribute;

class Index extends Component
{
    public bool $modalShow = false;
    public string $name = '';
    public ?string $description = null;
    public bool $is_strict = false;
    public ?int $parent_id = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_strict' => ['boolean'],
            'parent_id' => ['nullable', 'integer', 'exists:fs_attributes,id'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'is_strict', 'parent_id']);
        $this->modalShow = true;
    }

    public function closeCreateModal(): void
    {
        $this->modalShow = false;
    }

    public function createItem(): void
    {
        $this->validate();

        FsAttribute::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_strict' => $this->is_strict,
            'is_active' => true,
            'parent_id' => $this->parent_id,
            'team_id' => auth()->user()->current_team_id ?? null,
            'created_by_user_id' => auth()->id() ?? null,
            'owned_by_user_id' => auth()->id() ?? null,
        ]);

        $this->closeCreateModal();
    }

    public function render()
    {
        $items = FsAttribute::query()
            ->orderBy('name')
            ->get();

        return view('foodservice::livewire.attribute.index', [
            'items' => $items,
        ])->layout('platform::layouts.app');
    }
}


