<?php

namespace Platform\FoodService\Livewire\Additive;

use Livewire\Component;
use Platform\FoodService\Models\FsAdditive;

class Index extends Component
{
    public bool $modalShow = false;
    public string $name = '';
    public ?string $description = null;
    public bool $is_strict = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_strict' => ['boolean'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'is_strict']);
        $this->modalShow = true;
    }

    public function closeCreateModal(): void
    {
        $this->modalShow = false;
    }

    public function createItem(): void
    {
        $this->validate();

        FsAdditive::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_strict' => $this->is_strict,
            'is_active' => true,
        ]);

        $this->closeCreateModal();
    }

    public function render()
    {
        $items = FsAdditive::query()
            ->orderBy('name')
            ->get();

        return view('food-service::livewire.additive.index', [
            'items' => $items,
        ])->layout('platform::layouts.app');
    }
}


