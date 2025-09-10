<?php

namespace Platform\FoodService\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\ActivityLog\Traits\LogsActivity;
use Platform\Crm\Contracts\CompanyLinkableInterface;
use Platform\Crm\Models\CrmCompany;
use Platform\Crm\Models\CrmCompanyLink;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Symfony\Component\Uid\UuidV7;

class FsSupplier extends Model implements CompanyLinkableInterface
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'fs_suppliers';

    protected $fillable = [
        'uuid',
        'supplier_number',
        'description',
        'is_active',
        'team_id',
        'created_by_user_id',
        'owned_by_user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                do {
                    $uuid = UuidV7::generate();
                } while (self::where('uuid', $uuid)->exists());

                $model->uuid = $uuid;
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeForTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    public function supplierArticles()
    {
        return $this->hasMany(FsSupplierArticle::class, 'supplier_id');
    }

    // CRM Company Links - Direkte Implementierung
    public function companyLinks(): MorphMany
    {
        return $this->morphMany(CrmCompanyLink::class, 'linkable')
            ->forCurrentTeam();
    }

    public function companies()
    {
        return $this->companyLinks()
            ->with('company')
            ->get()
            ->pluck('company')
            ->filter(function ($company) {
                return $company && $company->is_active;
            });
    }

    public function attachCompany(CrmCompany $company): bool
    {
        if (!$company->is_active) {
            return false;
        }

        if (! $this->hasCompany($company)) {
            $this->companyLinks()->create([
                'company_id' => $company->id,
                'team_id' => auth()->user()->current_team_id,
                'created_by_user_id' => auth()->id(),
            ]);
            return true;
        }
        return false;
    }

    public function hasCompany(CrmCompany $company): bool
    {
        return $this->companyLinks()
            ->where('company_id', $company->id)
            ->exists();
    }

    // CompanyLinkableInterface Implementation
    public function getCompanyLinkableId(): int
    {
        return $this->id;
    }

    public function getCompanyLinkableType(): string
    {
        return 'Platform\\FoodService\\Models\\FsSupplier';
    }

    public function getCompanyIdentifiers(): array
    {
        // Für Suppliers haben wir keine direkten Company-Identifikatoren
        // Diese könnten aus den verknüpften Companies kommen
        return [];
    }

    public function getTeamId(): int
    {
        return $this->team_id;
    }
}
