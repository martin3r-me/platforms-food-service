<?php

namespace Platform\FoodService\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\ActivityLog\Traits\LogsActivity;
use Symfony\Component\Uid\UuidV7;

class FsManufacturer extends Model
{
    use SoftDeletes, LogsActivity;
    protected $table = 'fs_manufacturers';

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

    public function articles()
    {
        return $this->hasMany(FsArticle::class, 'manufacturer_id');
    }

    public function brands()
    {
        return $this->belongsToMany(FsBrand::class, 'fs_brand_manufacturers', 'fs_manufacturer_id', 'fs_brand_id')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function primaryBrands()
    {
        return $this->belongsToMany(FsBrand::class, 'fs_brand_manufacturers', 'fs_manufacturer_id', 'fs_brand_id')
                    ->wherePivot('is_primary', true);
    }
}
