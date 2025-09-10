<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fs_vat_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable()->after('id');
            $table->unsignedBigInteger('created_by_user_id')->nullable()->after('team_id');
            $table->unsignedBigInteger('owned_by_user_id')->nullable()->after('created_by_user_id');

            $table->index('team_id');
            $table->index('created_by_user_id');
            $table->index('owned_by_user_id');

            $table->foreign('team_id')->references('id')->on('teams')->nullOnDelete();
            $table->foreign('created_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('owned_by_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fs_vat_categories', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['created_by_user_id']);
            $table->dropForeign(['owned_by_user_id']);
            $table->dropIndex(['team_id']);
            $table->dropIndex(['created_by_user_id']);
            $table->dropIndex(['owned_by_user_id']);
            $table->dropColumn(['team_id','created_by_user_id','owned_by_user_id']);
        });
    }
};


