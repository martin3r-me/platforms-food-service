<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fs_article_categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('cluster_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->unsignedBigInteger('owned_by_user_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('cluster_id')->references('id')->on('fs_article_clusters')->cascadeOnDelete();
            $table->foreign('parent_id')->references('id')->on('fs_article_categories')->nullOnDelete();
            $table->foreign('team_id')->references('id')->on('teams')->nullOnDelete();
            $table->foreign('created_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('owned_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['cluster_id']);
            $table->index(['parent_id']);
            $table->index(['team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fs_article_categories');
    }
};


