<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fs_base_units', function (Blueprint $table) {
            // Erweitere conversion_factor auf 15 Stellen vor dem Komma, 6 nach dem Komma
            $table->decimal('conversion_factor', 21, 6)->default(1.000000)->change();
        });
    }

    public function down(): void
    {
        Schema::table('fs_base_units', function (Blueprint $table) {
            // Zurück zu kleinerer Präzision
            $table->decimal('conversion_factor', 10, 6)->default(1.000000)->change();
        });
    }
};
