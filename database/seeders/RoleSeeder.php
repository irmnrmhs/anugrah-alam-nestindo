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
        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Admin Bahan Baku']);
        Role::create(['name' => 'Admin Produksi']);
        Role::create(['name' => 'Admin Kontrol Kualitas']);
        Role::create(['name' => 'Admin Keamanan Pangan']);
        Role::create(['name' => 'Admin Ekspor']);
        Role::create(['name' => 'Admin Human Resource']);
        Role::create(['name' => 'Admin Akuntansi']);
        Role::create(['name' => 'Admin General Affairs']);
        // Role::create(['name' => 'User']);
    }
}
