<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']); //1
        Role::create(['name' => 'Admin Keamanan Pangan']); //2
        Role::create(['name' => 'Admin Kontrol Kualitas (Data)']); //3
        Role::create(['name' => 'Admin Kontrol Kualitas (Lab)']); //4
        Role::create(['name' => 'Admin Ekspor']); //5
        Role::create(['name' => 'Admin Human Resource']); //6
        Role::create(['name' => 'Administrasi']); //7
        Role::create(['name' => 'Admin Bahan Baku']); //8
        Role::create(['name' => 'Admin Produksi']); //9
        Role::create(['name' => 'Admin Akuntansi']); //10
        Role::create(['name' => 'Admin General Affairs']); //11
    }
}
