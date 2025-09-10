<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fs_articles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('article_number')->nullable()->unique();
            $table->string('ean')->nullable();
            
            // Foreign Keys
            $table->foreignId('brand_id')->nullable()->constrained('fs_brands')->onDelete('set null');
            $table->foreignId('manufacturer_id')->nullable()->constrained('fs_manufacturers')->onDelete('set null');
            $table->foreignId('article_category_id')->nullable()->constrained('fs_article_categories')->onDelete('set null');
            $table->foreignId('storage_type_id')->nullable()->constrained('fs_storage_types')->onDelete('set null');
            $table->foreignId('base_unit_id')->nullable()->constrained('fs_base_units')->onDelete('set null');
            $table->foreignId('vat_category_id')->nullable()->constrained('fs_vat_categories')->onDelete('set null');
            
            // Nutritional Information (JSON)
            $table->json('nutritional_info')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fs_articles');
    }
};
