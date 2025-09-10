<?php

namespace Platform\FoodService\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\ActivityLog\Traits\LogsActivity;
use Symfony\Component\Uid\UuidV7;

class FsVatCategory extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'fs_vat_categories';

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'is_active',
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

    public function rates()
    {
        return $this->hasMany(FsVatRate::class, 'vat_category_id');
    }
}


