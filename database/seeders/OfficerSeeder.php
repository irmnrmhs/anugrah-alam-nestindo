<?php

namespace Database\Seeders;

use App\Models\Officer;
use Illuminate\Database\Seeder;

class OfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Officer::create([
            'nama' => 'Petugas 1',
        ]);

        Officer::create([
            'nama' => 'Petugas 2',
        ]);

        Officer::create([
            'nama' => 'Petugas 3',
        ]);
    }
}
