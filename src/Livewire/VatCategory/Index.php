<?php

namespace Platform\FoodService\Livewire\VatCategory;

use Livewire\Component;
use Platform\FoodService\Models\FsVatCategory;

class Index extends Component
{
    public bool $modalShow = false;
    public string $name = '';
    public ?string $description = null;
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'is_active']);
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

        FsVatCategory::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'team_id' => auth()->user()->current_team_id ?? null,
            'created_by_user_id' => auth()->id() ?? null,
            'owned_by_user_id' => auth()->id() ?? null,
        ]);

        $this->closeCreateModal();
    }

    public function render()
    {
        $items = FsVatCategory::orderBy('name')->get();

        return view('foodservice::livewire.vat-category.index', [
            'items' => $items,
        ])->layout('platform::layouts.app');
    }
}


