<?php

namespace Platform\FoodService\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\ActivityLog\Traits\LogsActivity;
use Symfony\Component\Uid\UuidV7;

class FsVatRate extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'fs_vat_rates';

    protected $fillable = [
        'uuid',
        'vat_category_id',
        'rate_percent',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'rate_percent' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
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

    public function category()
    {
        return $this->belongsTo(FsVatCategory::class, 'vat_category_id');
    }
}


