<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']); //1
        Role::create(['name' => 'Admin Rumah Walet']); //2
        Role::create(['name' => 'Admin Bahan Baku']); //3
        Role::create(['name' => 'Admin Produksi']); //4
        Role::create(['name' => 'Admin Kontrol Kualitas']); //5
        Role::create(['name' => 'Admin Ekspor']); //6
        Role::create(['name' => 'MR']); //7
    }
}
