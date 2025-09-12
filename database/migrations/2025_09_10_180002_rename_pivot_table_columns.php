<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('fs_brand_manufacturers', 'brand_id') && Schema::hasColumn('fs_brand_manufacturers', 'manufacturer_id')) {
            // Drop constraints tied to old columns
            Schema::table('fs_brand_manufacturers', function (Blueprint $table) {
                $table->dropForeign(['brand_id']);
                $table->dropForeign(['manufacturer_id']);
                $table->dropUnique(['brand_id', 'manufacturer_id']);
            });

            // Rename columns in a separate table call to avoid schema builder issues
            Schema::table('fs_brand_manufacturers', function (Blueprint $table) {
                $table->renameColumn('brand_id', 'fs_brand_id');
                $table->renameColumn('manufacturer_id', 'fs_manufacturer_id');
            });

            // Re-create constraints for new columns
            Schema::table('fs_brand_manufacturers', function (Blueprint $table) {
                $table->foreign('fs_brand_id')->references('id')->on('fs_brands')->onDelete('cascade');
                $table->foreign('fs_manufacturer_id')->references('id')->on('fs_manufacturers')->onDelete('cascade');
                $table->unique(['fs_brand_id', 'fs_manufacturer_id']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('fs_brand_manufacturers', 'fs_brand_id') && Schema::hasColumn('fs_brand_manufacturers', 'fs_manufacturer_id') && !Schema::hasColumn('fs_brand_manufacturers', 'brand_id') && !Schema::hasColumn('fs_brand_manufacturers', 'manufacturer_id')) {
            // Drop constraints for new columns
            Schema::table('fs_brand_manufacturers', function (Blueprint $table) {
                $table->dropForeign(['fs_brand_id']);
                $table->dropForeign(['fs_manufacturer_id']);
                $table->dropUnique(['fs_brand_id', 'fs_manufacturer_id']);
            });

            // Rename columns back
            Schema::table('fs_brand_manufacturers', function (Blueprint $table) {
                $table->renameColumn('fs_brand_id', 'brand_id');
                $table->renameColumn('fs_manufacturer_id', 'manufacturer_id');
            });

            // Re-create old constraints
            Schema::table('fs_brand_manufacturers', function (Blueprint $table) {
                $table->foreign('brand_id')->references('id')->on('fs_brands')->onDelete('cascade');
                $table->foreign('manufacturer_id')->references('id')->on('fs_manufacturers')->onDelete('cascade');
                $table->unique(['brand_id', 'manufacturer_id']);
            });
        }
    }
};
