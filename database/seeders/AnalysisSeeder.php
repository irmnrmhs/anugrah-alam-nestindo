<?php

namespace Database\Seeders;

use App\Models\Analysis;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnalysisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Analysis::create([
            'item' => 'Description (physical)',
            'standard' => 'White to yellowish color, cleand and no meteal and wood contamination',
        ]);

        Analysis::create([
            'item' => 'Organoleptic Test',
            'standard' => 'Specific taste and aroma',
        ]);

        Analysis::create([
            'item' => 'Weight (g)',
            'standard' => '
                50.0 g(+0.30.5 g)
                100.0 g (+0.7-0.9g)
                250.0 g (+2.55.0 g)
                450.0 g (0.10.5 g)
                500.0 g (+5.0 10.0 g)
                540.0 g (+0.1-0.5 g)
                600.0 g (+0.1-0.5g)
                720.0 g (+0.1-0.5 g)
                800.0 g (+0.1-0.5 g)
                900.0 g (+0.1-0.5g)
                1000.0 g (+ 10.0-20.0 g)
            ',
        ]);

        Analysis::create([
            'item' => 'Sodium Nitrite (mg/kg)',
            'standard' => 'Not more than 30 mg/kg',
        ]);

        Analysis::create([
            'item' => 'Moisture Content (%)',
            'standard' => 'Not more than 15%',
        ]);

        Analysis::create([
            'item' => 'Expand Cook Test',
            'standard' => '18-28 x',
        ]);

        Analysis::create([
            'item' => 'Aluminium',
            'standard' => 'Not more than 100 mg/kg',
        ]);
    }
}
