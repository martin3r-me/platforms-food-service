<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fs_vat_rates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('vat_category_id');
            $table->decimal('rate_percent', 5, 2); // z. B. 19.00
            $table->date('valid_from');
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('vat_category_id')->references('id')->on('fs_vat_categories')->cascadeOnDelete();
            $table->index(['vat_category_id', 'valid_from']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fs_vat_rates');
    }
};


