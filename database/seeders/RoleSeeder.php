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
        // Role::create(['name' => 'Admin Rumah Walet']);
        // Role::create(['name' => 'Admin Bahan Baku']);
        Role::create(['name' => 'Admin Keamanan Pangan']); //2
        Role::create(['name' => 'Admin Produksi']); //3
        Role::create(['name' => 'Admin Kontrol Kualitas']); //4
        Role::create(['name' => 'Admin Ekspor']); //5
    }
}
