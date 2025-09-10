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
        Schema::create('fs_supplier_articles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Foreign Keys
            $table->foreignId('supplier_id')->constrained('fs_suppliers')->onDelete('cascade');
            $table->foreignId('article_id')->constrained('fs_articles')->onDelete('cascade');
            
            // Supplier-specific data
            $table->string('supplier_article_number')->nullable();
            $table->string('supplier_ean')->nullable();
            $table->decimal('purchase_price', 10, 4)->nullable();
            $table->string('currency', 3)->default('EUR');
            $table->integer('minimum_order_quantity')->nullable();
            $table->integer('delivery_time_days')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Multi-tenant fields
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('owned_by_user_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['supplier_id', 'article_id']);
            $table->index(['article_id', 'supplier_id']);
            $table->index(['team_id']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fs_supplier_articles');
    }
};
