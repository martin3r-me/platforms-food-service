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
        if (Schema::hasTable('fs_suppliers') && Schema::hasColumn('fs_suppliers', 'supplier_number') && !Schema::hasColumn('fs_suppliers', 'name')) {
            Schema::table('fs_suppliers', function (Blueprint $table) {
                $table->renameColumn('supplier_number', 'name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('fs_suppliers') && Schema::hasColumn('fs_suppliers', 'name') && !Schema::hasColumn('fs_suppliers', 'supplier_number')) {
            Schema::table('fs_suppliers', function (Blueprint $table) {
                $table->renameColumn('name', 'supplier_number');
            });
        }
    }
};
