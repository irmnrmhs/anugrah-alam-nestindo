<?php

namespace Database\Seeders;

use App\Models\SteamOfficer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SteamOfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SteamOfficer::create([
            'employees_id' => 17,
            'status' => 1,
        ]);
        
        SteamOfficer::create([
            'employees_id' => 19,
            'status' => 1,
        ]);

        SteamOfficer::create([
            'employees_id' => 18,
            'status' => 0,
        ]);
    }
}
