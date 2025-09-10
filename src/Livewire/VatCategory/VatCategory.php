<?php

namespace Platform\FoodService\Livewire\VatCategory;

use Livewire\Component;
use Platform\FoodService\Models\FsVatCategory;
use Platform\FoodService\Models\FsVatRate;
use Carbon\Carbon;

class VatCategory extends Component
{
    public FsVatCategory $category;
    public bool $isDirty = false;

    // Rate form
    public bool $rateModalShow = false;
    public ?int $editingRateId = null;
    public string $rate_percent = '';
    public ?string $valid_from = null;
    public ?string $valid_until = null;
    public bool $rate_is_active = true;

    protected function rules(): array
    {
        return [
            'category.name' => ['required', 'string', 'max:255'],
            'category.description' => ['nullable', 'string'],
            'category.is_active' => ['boolean'],

            'rate_percent' => ['nullable', 'numeric', 'min:0'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'rate_is_active' => ['boolean'],
        ];
    }

    public function mount(FsVatCategory $category): void
    {
        $this->category = $category;
    }

    public function updated($prop): void
    {
        if (str_starts_with($prop, 'category.')) {
            $this->isDirty = true;
        }
    }

    public function save(): void
    {
        $this->validate();
        $this->category->save();
        $this->isDirty = false;
    }

    public function openRateModal(?int $rateId = null): void
    {
        $this->resetValidation();
        $this->editingRateId = $rateId;
        if ($rateId) {
            $rate = $this->category->rates()->findOrFail($rateId);
            $this->rate_percent = (string)$rate->rate_percent;
            $this->valid_from = optional($rate->valid_from)?->format('Y-m-d');
            $this->valid_until = optional($rate->valid_until)?->format('Y-m-d');
            $this->rate_is_active = (bool)$rate->is_active;
        } else {
            $this->rate_percent = '';
            $this->valid_from = null;
            $this->valid_until = null;
            $this->rate_is_active = true;
        }
        $this->rateModalShow = true;
    }

    public function closeRateModal(): void
    {
        $this->rateModalShow = false;
    }

    public function saveRate(): void
    {
        $this->validate([
            'rate_percent' => ['required', 'numeric', 'min:0'],
            'valid_from' => ['required', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'rate_is_active' => ['boolean'],
        ]);
        // Überschneidungen prüfen
        $from = Carbon::parse($this->valid_from)->startOfDay();
        $until = $this->valid_until ? Carbon::parse($this->valid_until)->endOfDay() : null;

        $overlapExists = $this->category
            ->rates()
            ->when($this->editingRateId, fn ($q) => $q->where('id', '!=', $this->editingRateId))
            ->where(function ($q) use ($from, $until) {
                // existing_from <= new_until (or infinite)
                $q->whereDate('valid_from', '<=', $until ? $until->toDateString() : '9999-12-31')
                  // and existing_until (or infinite) >= new_from
                  ->where(function ($q2) use ($from) {
                      $q2->whereNull('valid_until')
                         ->orWhereDate('valid_until', '>=', $from->toDateString());
                  });
            })
            ->exists();

        if ($overlapExists) {
            $this->addError('valid_from', 'Der Zeitraum überschneidet sich mit einer bestehenden Rate.');
            return;
        }

        if ($this->editingRateId) {
            $rate = $this->category->rates()->findOrFail($this->editingRateId);
            $rate->update([
                'rate_percent' => $this->rate_percent,
                'valid_from' => $this->valid_from,
                'valid_until' => $this->valid_until,
                'is_active' => $this->rate_is_active,
            ]);
        } else {
            $this->category->rates()->create([
                'rate_percent' => $this->rate_percent,
                'valid_from' => $this->valid_from,
                'valid_until' => $this->valid_until,
                'is_active' => $this->rate_is_active,
            ]);
        }

        $this->closeRateModal();
    }

    public function render()
    {
        $rates = $this->category->rates()->orderByDesc('valid_from')->get();

        return view('foodservice::livewire.vat-category.show', [
            'category' => $this->category,
            'rates' => $rates,
        ])->layout('platform::layouts.app');
    }
}


