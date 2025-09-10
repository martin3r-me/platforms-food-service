<?php

namespace Platform\FoodService\Console\Commands;

use Illuminate\Console\Command;
use Platform\FoodService\Database\Seeders\FsBaseUnitSeeder;

class SeedBaseUnits extends Command
{
    protected $signature = 'foodservice:seed-base-units';
    protected $description = 'Seed the base units with standard categories and units';

    public function handle()
    {
        $this->info('Seeding base units...');
        
        $seeder = new FsBaseUnitSeeder();
        $seeder->run();
        
        $this->info('Base units seeded successfully!');
        $this->line('');
        $this->line('Created categories:');
        $this->line('- Gewicht (mg, g, kg, t)');
        $this->line('- Volumen (ml, cl, L, hl)');
        $this->line('- Stück (Stk, Port, Bund, Kopf, Scheibe)');
    }
}
