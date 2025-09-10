<?php

namespace Platform\FoodService\Livewire\Supplier;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Platform\FoodService\Models\FsSupplier;

class Index extends Component
{
    // Modal State
    public $modalShow = false;
    
    // Sorting
    public $sortField = 'name';
    public $sortDirection = 'asc';
    
    // Form Data
    public $name = '';
    public $description = '';
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    #[Computed]
    public function suppliers()
    {
        $query = FsSupplier::forTeam(auth()->user()->currentTeam->id);

        if ($this->sortField === 'name') {
            $query->orderBy('name', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query->get();
    }



    #[Computed]
    public function stats()
    {
        $teamId = auth()->user()->currentTeam->id;
        
        return [
            'total' => FsSupplier::forTeam($teamId)->count(),
            'active' => FsSupplier::forTeam($teamId)->active()->count(),
            'inactive' => FsSupplier::forTeam($teamId)->where('is_active', false)->count(),
        ];
    }

    public function render()
    {
        return view('foodservice::livewire.supplier.index')
            ->layout('platform::layouts.app');
    }

    public function createSupplier()
    {
        $this->validate();
        
        $supplier = FsSupplier::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'team_id' => auth()->user()->currentTeam->id,
            'created_by_user_id' => auth()->id(),
            'owned_by_user_id' => auth()->id(),
        ]);

        $this->resetForm();
        $this->modalShow = false;
        
        session()->flash('message', 'Lieferant erfolgreich erstellt!');
    }

    public function resetForm()
    {
        $this->reset([
            'name', 
            'description', 
            'is_active'
        ]);
        $this->is_active = true;
    }

    public function openCreateModal()
    {
        $this->modalShow = true;
    }

    public function closeCreateModal()
    {
        $this->modalShow = false;
        $this->resetForm();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
}
