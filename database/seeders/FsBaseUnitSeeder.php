<?php

namespace Platform\FoodService\Database\Seeders;

use Illuminate\Database\Seeder;
use Platform\FoodService\Models\FsBaseUnit;

class FsBaseUnitSeeder extends Seeder
{
    public function run(): void
    {
        // Gewicht Kategorie
        $weightCategory = FsBaseUnit::create([
            'name' => 'Gewicht',
            'short_name' => 'Gew',
            'description' => 'Gewichtseinheiten',
            'conversion_factor' => 1.0,
            'is_base_unit' => false,
            'decimal_places' => 3,
            'is_active' => true,
            'parent_id' => null,
        ]);

        // Gewicht Einheiten
        FsBaseUnit::create([
            'name' => 'Milligramm',
            'short_name' => 'mg',
            'description' => 'Milligramm',
            'conversion_factor' => 0.001,
            'is_base_unit' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'parent_id' => $weightCategory->id,
        ]);

        FsBaseUnit::create([
            'name' => 'Gramm',
            'short_name' => 'g',
            'description' => 'Gramm - Basiseinheit für Gewicht',
            'conversion_factor' => 1.0,
            'is_base_unit' => true,
            'decimal_places' => 2,
            'is_active' => true,
            'parent_id' => $weightCategory->id,
        ]);

        FsBaseUnit::create([
            'name' => 'Kilogramm',
            'short_name' => 'kg',
            'description' => 'Kilogramm',
            'conversion_factor' => 1000.0,
            'is_base_unit' => false,
            'decimal_places' => 3,
            'is_active' => true,
            'parent_id' => $weightCategory->id,
        ]);

        FsBaseUnit::create([
            'name' => 'Tonne',
            'short_name' => 't',
            'description' => 'Tonne',
            'conversion_factor' => 1000000.0,
            'is_base_unit' => false,
            'decimal_places' => 3,
            'is_active' => true,
            'parent_id' => $weightCategory->id,
        ]);

        // Volumen Kategorie
        $volumeCategory = FsBaseUnit::create([
            'name' => 'Volumen',
            'short_name' => 'Vol',
            'description' => 'Volumeneinheiten',
            'conversion_factor' => 1.0,
            'is_base_unit' => false,
            'decimal_places' => 3,
            'is_active' => true,
            'parent_id' => null,
        ]);

        // Volumen Einheiten
        FsBaseUnit::create([
            'name' => 'Milliliter',
            'short_name' => 'ml',
            'description' => 'Milliliter',
            'conversion_factor' => 0.001,
            'is_base_unit' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'parent_id' => $volumeCategory->id,
        ]);

        FsBaseUnit::create([
            'name' => 'Zentiliter',
            'short_name' => 'cl',
            'description' => 'Zentiliter',
            'conversion_factor' => 0.01,
            'is_base_unit' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'parent_id' => $volumeCategory->id,
        ]);

        FsBaseUnit::create([
            'name' => 'Liter',
            'short_name' => 'L',
            'description' => 'Liter - Basiseinheit für Volumen',
            'conversion_factor' => 1.0,
            'is_base_unit' => true,
            'decimal_places' => 2,
            'is_active' => true,
            'parent_id' => $volumeCategory->id,
        ]);

        FsBaseUnit::create([
            'name' => 'Hektoliter',
            'short_name' => 'hl',
            'description' => 'Hektoliter',
            'conversion_factor' => 100.0,
            'is_base_unit' => false,
            'decimal_places' => 2,
            'is_active' => true,
            'parent_id' => $volumeCategory->id,
        ]);

        // Stück Kategorie
        $pieceCategory = FsBaseUnit::create([
            'name' => 'Stück',
            'short_name' => 'Stk',
            'description' => 'Stückeinheiten',
            'conversion_factor' => 1.0,
            'is_base_unit' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'parent_id' => null,
        ]);

        // Stück Einheiten
        FsBaseUnit::create([
            'name' => 'Stück',
            'short_name' => 'Stk',
            'description' => 'Stück - Basiseinheit für Zählung',
            'conversion_factor' => 1.0,
            'is_base_unit' => true,
            'decimal_places' => 0,
            'is_active' => true,
            'parent_id' => $pieceCategory->id,
        ]);

        FsBaseUnit::create([
            'name' => 'Portion',
            'short_name' => 'Port',
            'description' => 'Portion',
            'conversion_factor' => 1.0,
            'is_base_unit' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'parent_id' => $pieceCategory->id,
        ]);

        FsBaseUnit::create([
            'name' => 'Bund',
            'short_name' => 'Bund',
            'description' => 'Bund',
            'conversion_factor' => 1.0,
            'is_base_unit' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'parent_id' => $pieceCategory->id,
        ]);

        FsBaseUnit::create([
            'name' => 'Kopf',
            'short_name' => 'Kopf',
            'description' => 'Kopf',
            'conversion_factor' => 1.0,
            'is_base_unit' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'parent_id' => $pieceCategory->id,
        ]);

        FsBaseUnit::create([
            'name' => 'Scheibe',
            'short_name' => 'Scheibe',
            'description' => 'Scheibe',
            'conversion_factor' => 1.0,
            'is_base_unit' => false,
            'decimal_places' => 0,
            'is_active' => true,
            'parent_id' => $pieceCategory->id,
        ]);
    }
}
