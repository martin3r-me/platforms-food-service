<?php

namespace Platform\FoodService\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\ActivityLog\Traits\LogsActivity;
use Symfony\Component\Uid\UuidV7;

class FsArticle extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'fs_articles';

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'article_number',
        'ean',
        'brand_id',
        'manufacturer_id',
        'article_category_id',
        'storage_type_id',
        'base_unit_id',
        'vat_category_id',
        'nutritional_info',
        'is_active',
        'team_id',
        'created_by_user_id',
        'owned_by_user_id',
    ];

    protected $casts = [
        'nutritional_info' => 'array',
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

    // Relationships
    public function brand()
    {
        return $this->belongsTo(FsBrand::class, 'brand_id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(FsManufacturer::class, 'manufacturer_id');
    }

    public function articleCategory()
    {
        return $this->belongsTo(FsArticleCategory::class, 'article_category_id');
    }

    public function storageType()
    {
        return $this->belongsTo(FsStorageType::class, 'storage_type_id');
    }

    public function baseUnit()
    {
        return $this->belongsTo(FsBaseUnit::class, 'base_unit_id');
    }

    public function vatCategory()
    {
        return $this->belongsTo(FsVatCategory::class, 'vat_category_id');
    }

    public function allergens()
    {
        return $this->belongsToMany(FsAllergen::class, 'fs_article_allergens', 'article_id', 'allergen_id')
            ->withTimestamps();
    }

    public function additives()
    {
        return $this->belongsToMany(FsAdditive::class, 'fs_article_additives', 'article_id', 'additive_id')
            ->withTimestamps();
    }

    public function attributes()
    {
        return $this->belongsToMany(FsAttribute::class, 'fs_article_attributes', 'article_id', 'attribute_id')
            ->withTimestamps();
    }

    /**
     * Get the supplier articles for the article.
     */
    public function supplierArticles()
    {
        return $this->hasMany(FsSupplierArticle::class, 'article_id');
    }

    /**
     * Get the suppliers for the article through supplier articles.
     */
    public function suppliers()
    {
        return $this->hasManyThrough(FsSupplier::class, FsSupplierArticle::class, 'article_id', 'id', 'id', 'supplier_id');
    }
}
