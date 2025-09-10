<?php

namespace Platform\FoodService\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\ActivityLog\Traits\LogsActivity;
use Symfony\Component\Uid\UuidV7;

class FsSupplier extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'fs_suppliers';

    protected $fillable = [
        'uuid',
        'name',
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

    /**
     * Get the supplier articles for the supplier.
     */
    public function supplierArticles(): HasMany
    {
        return $this->hasMany(FsSupplierArticle::class, 'supplier_id');
    }

    /**
     * Get the articles for the supplier through supplier articles.
     */
    public function articles(): HasMany
    {
        return $this->hasManyThrough(FsArticle::class, FsSupplierArticle::class, 'supplier_id', 'id', 'id', 'article_id');
    }
}
