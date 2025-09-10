<?php

namespace Platform\FoodService\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\ActivityLog\Traits\LogsActivity;
use Symfony\Component\Uid\UuidV7;

class FsSupplierArticle extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'supplier_id',
        'article_id',
        'supplier_article_number',
        'supplier_ean',
        'purchase_price',
        'currency',
        'minimum_order_quantity',
        'delivery_time_days',
        'notes',
        'is_active',
        'team_id',
        'created_by_user_id',
        'owned_by_user_id',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:4',
        'is_active' => 'boolean',
        'minimum_order_quantity' => 'integer',
        'delivery_time_days' => 'integer',
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

    /**
     * Get the supplier that owns the supplier article.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(FsSupplier::class);
    }

    /**
     * Get the article that owns the supplier article.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(FsArticle::class);
    }
}
