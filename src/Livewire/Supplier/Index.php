<?php

namespace Platform\FoodService\Livewire\Supplier;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Platform\FoodService\Models\FsSupplier;
use Platform\Crm\Models\CrmContact;
use Platform\Crm\Models\CrmCompany;

class Index extends Component
{
    // Modal State
    public $modalShow = false;
    
    // Sorting
    public $sortField = 'supplier_number';
    public $sortDirection = 'asc';
    
    // Form Data
    public $supplier_number = '';
    public $crm_company_id = '';
    public $crm_contact_id = '';
    public $description = '';
    public $is_active = true;

    protected $rules = [
        'supplier_number' => 'required|string|max:255|unique:fs_suppliers,supplier_number',
        'crm_company_id' => 'nullable|exists:crm_companies,id',
        'crm_contact_id' => 'nullable|exists:crm_contacts,id',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    #[Computed]
    public function suppliers()
    {
        $query = FsSupplier::with(['crmContactLinks.contact', 'crmCompanyLinks.company'])
            ->forTeam(auth()->user()->currentTeam->id);

        if ($this->sortField === 'supplier_number') {
            $query->orderBy('supplier_number', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query->get();
    }

    #[Computed]
    public function availableCompanies()
    {
        // Aktive CRM Companies aus dem Team, die noch nicht mit einem Supplier verknüpft sind
        $linkedIds = \Platform\Crm\Models\CrmCompanyLink::where('linkable_type', 'Platform\\FoodService\\Models\\FsSupplier')
            ->where('team_id', auth()->user()->currentTeam->id)
            ->pluck('crm_company_id');

        return CrmCompany::where('team_id', auth()->user()->currentTeam->id)
            ->where('is_active', true)
            ->whereNotIn('id', $linkedIds)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function availableContacts()
    {
        // Aktive CRM Contacts aus dem Team, die noch nicht mit einem Supplier verknüpft sind
        $linkedIds = \Platform\Crm\Models\CrmContactLink::where('linkable_type', 'Platform\\FoodService\\Models\\FsSupplier')
            ->where('team_id', auth()->user()->currentTeam->id)
            ->pluck('crm_contact_id');

        return CrmContact::where('team_id', auth()->user()->currentTeam->id)
            ->where('is_active', true)
            ->whereNotIn('id', $linkedIds)
            ->orderBy('name')
            ->get();
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
            'supplier_number' => $this->supplier_number,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'team_id' => auth()->user()->currentTeam->id,
            'created_by_user_id' => auth()->id(),
            'owned_by_user_id' => auth()->id(),
        ]);

        // Verknüpfe mit CRM Company
        if ($this->crm_company_id) {
            $company = CrmCompany::find($this->crm_company_id);
            if ($company) {
                $supplier->attachCrmCompany($company);
            }
        }

        // Verknüpfe mit CRM Contact
        if ($this->crm_contact_id) {
            $contact = CrmContact::find($this->crm_contact_id);
            if ($contact) {
                $supplier->attachCrmContact($contact);
            }
        }

        $this->resetForm();
        $this->modalShow = false;
        
        session()->flash('message', 'Lieferant erfolgreich erstellt!');
    }

    public function resetForm()
    {
        $this->reset([
            'supplier_number', 
            'crm_company_id',
            'crm_contact_id', 
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
