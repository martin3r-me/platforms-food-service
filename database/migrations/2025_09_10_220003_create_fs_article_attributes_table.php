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
        Schema::create('fs_article_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('fs_articles')->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained('fs_attributes')->onDelete('cascade');
            $table->string('value');
            $table->timestamps();
            
            $table->unique(['article_id', 'attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fs_article_attributes');
    }
};
