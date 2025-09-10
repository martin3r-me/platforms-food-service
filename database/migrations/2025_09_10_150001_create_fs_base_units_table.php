<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fs_base_units', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name'); // z.B. "Gewicht", "Gramm", "Liter"
            $table->string('short_name'); // z.B. "Gew", "g", "L"
            $table->text('description')->nullable();
            $table->decimal('conversion_factor', 10, 6)->default(1.000000); // Umrechnungsfaktor zur Basiseinheit
            $table->boolean('is_base_unit')->default(false); // Ist dies die Basiseinheit der Kategorie?
            $table->integer('decimal_places')->default(2); // Dezimalstellen für Anzeige
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fs_base_units');
    }
};
