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
        Schema::table('fs_suppliers', function (Blueprint $table) {
            $table->renameColumn('supplier_number', 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fs_suppliers', function (Blueprint $table) {
            $table->renameColumn('name', 'supplier_number');
        });
    }
};
