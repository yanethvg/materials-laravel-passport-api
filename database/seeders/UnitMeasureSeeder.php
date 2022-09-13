<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitMeasure;

class UnitMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnitMeasure::factory()->count(10)->create();
    }
}
