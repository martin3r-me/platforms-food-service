<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fs_brands', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('cascade');
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('owned_by_user_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('fs_brands', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['created_by_user_id']);
            $table->dropForeign(['owned_by_user_id']);
            $table->dropColumn(['team_id', 'created_by_user_id', 'owned_by_user_id']);
        });
    }
};
