<?php

namespace Database\Seeders;

use App\Models\SteamOfficer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SteamOfficer::create([
            'employees_id' => 10,
            'status' => 1
        ]);

        SteamOfficer::create([
            'employees_id' => 11,
            'status' => 1
        ]);

        SteamOfficer::create([
            'employees_id' => 12,
            'status' => 0
        ]);
    }
}
