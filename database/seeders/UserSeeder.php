<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin@058'),
            'roles_id' => 1,
            'employees_id' => 1
        ]);

        User::create([
            'username' => 'el',
            'password' => Hash::make('el058'),
            'roles_id' => 3,
            'employees_id' => 2
        ]);

        User::create([
            'username' => 'elia',
            'password' => Hash::make('el058'),
            'roles_id' => 4,
            'employees_id' => 2
        ]);

        User::create([
            'username' => 'ocha',
            'password' => Hash::make('ocha058'),
            'roles_id' => 2,
            'employees_id' => 3
        ]);

        User::create([
            'username' => 'dini',
            'password' => Hash::make('dini058'),
            'roles_id' => 3,
            'employees_id' => 4
        ]);

        User::create([
            'username' => 'ira',
            'password' => Hash::make('ira058'),
            'roles_id' => 2,
            'employees_id' => 5
        ]);

        User::create([
            'username' => 'rania',
            'password' => Hash::make('rania058'),
            'roles_id' => 4,
            'employees_id' => 6
        ]);

        User::create([
            'username' => 'cattie',
            'password' => Hash::make('cattie058'),
            'roles_id' => 4,
            'employees_id' => 7
        ]);

        User::create([
            'username' => 'nurul',
            'password' => Hash::make('nurul058'),
            'roles_id' => 4,
            'employees_id' => 8
        ]);
        
        User::create([
            'username' => 'syifa',
            'password' => Hash::make('syifa058'),
            'roles_id' => 3,
            'employees_id' => 9
        ]);

        User::create([
            'username' => 'gita',
            'password' => Hash::make('gita058'),
            'roles_id' => 2,
            'employees_id' => 10
        ]);

        User::create([
            'username' => 'Lisna',
            'password' => Hash::make('lisna058'),
            'roles_id' => 2,
            'employees_id' => 11
        ]);

        User::create([
            'username' => 'anggi',
            'password' => Hash::make('anggi058'),
            'roles_id' => 4,
            'employees_id' => 12
        ]);
        
        User::create([
            'username' => 'irma',
            'password' => Hash::make('irma_058'),
            'roles_id' => 1,
            'employees_id' => 13
        ]);

        User::create([
            'username' => 'nisa',
            'password' => Hash::make('nisa058'),
            'roles_id' => 3,
            'employees_id' => 14
        ]);

        User::create([
            'username' => 'retno',
            'password' => Hash::make('retno058'),
            'roles_id' => 3,
            'employees_id' => 15
        ]);

        User::create([
            'username' => 'styla',
            'password' => Hash::make('styla058'),
            'roles_id' => 2,
            'employees_id' => 16
        ]);

        User::create([
            'username' => 'resvi',
            'password' => Hash::make('resvi058'),
            'roles_id' => 5,
            'employees_id' => 17
        ]);
    }
}
