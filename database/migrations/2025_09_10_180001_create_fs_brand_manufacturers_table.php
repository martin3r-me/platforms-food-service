<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fs_brand_manufacturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('fs_brands')->onDelete('cascade');
            $table->foreignId('manufacturer_id')->constrained('fs_manufacturers')->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            // Ensure unique combination of brand and manufacturer
            $table->unique(['brand_id', 'manufacturer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fs_brand_manufacturers');
    }
};
