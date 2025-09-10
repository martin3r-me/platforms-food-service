<?php

namespace Platform\FoodService\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\ActivityLog\Traits\LogsActivity;
use Symfony\Component\Uid\UuidV7;

class FsBrand extends Model
{
    use SoftDeletes, LogsActivity;
    protected $table = 'fs_brands';

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
        return $this->hasMany(FsArticle::class, 'brand_id');
    }

    public function manufacturers()
    {
        return $this->belongsToMany(FsManufacturer::class, 'fs_brand_manufacturers')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function primaryManufacturer()
    {
        return $this->belongsToMany(FsManufacturer::class, 'fs_brand_manufacturers')
                    ->wherePivot('is_primary', true);
    }
}
