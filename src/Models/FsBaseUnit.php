<?php

namespace Platform\FoodService\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\ActivityLog\Traits\LogsActivity;
use Symfony\Component\Uid\UuidV7;

class FsBaseUnit extends Model
{
    use SoftDeletes, LogsActivity;
    protected $table = 'fs_base_units';

    protected $fillable = [
        'uuid',
        'name',
        'short_name',
        'description',
        'conversion_factor',
        'is_base_unit',
        'decimal_places',
        'is_active',
        'parent_id',
        'team_id',
        'created_by_user_id',
        'owned_by_user_id',
    ];

    protected $casts = [
        'conversion_factor' => 'decimal:6',
        'is_base_unit' => 'boolean',
        'decimal_places' => 'integer',
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

    public function scopeCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeUnits($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function getCategoryNameAttribute()
    {
        return $this->parent?->name ?? $this->name;
    }

    public function getFullNameAttribute()
    {
        if ($this->parent) {
            return $this->name . ' (' . $this->short_name . ')';
        }
        return $this->name;
    }
}
