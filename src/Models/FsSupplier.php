<?php

namespace Platform\FoodService\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\ActivityLog\Traits\LogsActivity;
use Platform\Crm\Traits\HasContactLinksTrait;
use Platform\Crm\Traits\HasCompanyLinksTrait;
use Symfony\Component\Uid\UuidV7;

class FsSupplier extends Model
{
    use SoftDeletes, LogsActivity, HasContactLinksTrait, HasCompanyLinksTrait;
    
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
}
