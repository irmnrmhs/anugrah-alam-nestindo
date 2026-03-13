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
            'employees_id' => 39,
            'status' => 1,
        ]);
        
        SteamOfficer::create([
            'employees_id' => 40,
            'status' => 1,
        ]);

        SteamOfficer::create([
            'employees_id' => 41,
            'status' => 1,
        ]);

        SteamOfficer::create([
            'employees_id' => 42,
            'status' => 1,
        ]);

        SteamOfficer::create([
            'employees_id' => 43,
            'status' => 1,
        ]);

        SteamOfficer::create([
            'employees_id' => 14,
            'status' => 1,
        ]);
    }
}
