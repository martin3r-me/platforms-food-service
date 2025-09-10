<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fs_brand_manufacturers', function (Blueprint $table) {
            // Drop the existing foreign key constraints
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['manufacturer_id']);
            
            // Drop the unique constraint
            $table->dropUnique(['brand_id', 'manufacturer_id']);
            
            // Rename columns
            $table->renameColumn('brand_id', 'fs_brand_id');
            $table->renameColumn('manufacturer_id', 'fs_manufacturer_id');
            
            // Add new foreign key constraints
            $table->foreign('fs_brand_id')->references('id')->on('fs_brands')->onDelete('cascade');
            $table->foreign('fs_manufacturer_id')->references('id')->on('fs_manufacturers')->onDelete('cascade');
            
            // Add new unique constraint
            $table->unique(['fs_brand_id', 'fs_manufacturer_id']);
        });
    }

    public function down(): void
    {
        Schema::table('fs_brand_manufacturers', function (Blueprint $table) {
            // Drop the new foreign key constraints
            $table->dropForeign(['fs_brand_id']);
            $table->dropForeign(['fs_manufacturer_id']);
            
            // Drop the new unique constraint
            $table->dropUnique(['fs_brand_id', 'fs_manufacturer_id']);
            
            // Rename columns back
            $table->renameColumn('fs_brand_id', 'brand_id');
            $table->renameColumn('fs_manufacturer_id', 'manufacturer_id');
            
            // Add old foreign key constraints
            $table->foreign('brand_id')->references('id')->on('fs_brands')->onDelete('cascade');
            $table->foreign('manufacturer_id')->references('id')->on('fs_manufacturers')->onDelete('cascade');
            
            // Add old unique constraint
            $table->unique(['brand_id', 'manufacturer_id']);
        });
    }
};
