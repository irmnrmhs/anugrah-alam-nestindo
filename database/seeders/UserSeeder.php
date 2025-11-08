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
            'username' => 'super_admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin058'),
            'roles_id' => 1,
            'employees_id' => 1
        ]);

        User::create([
            'username' => 'admin_bb',
            'email' => 'bb@mail.com',
            'password' => Hash::make('bb058'),
            'roles_id' => 2,
            'employees_id' => 2
        ]);

        User::create([
            'username' => 'admin_pr',
            'email' => 'pr@mail.com',
            'password' => Hash::make('pr058'),
            'roles_id' => 3,
            'employees_id' => 3
        ]);

        User::create([
            'username' => 'admin_qc',
            'email' => 'qc@mail.com',
            'password' => Hash::make('qc058'),
            'roles_id' => 4,
            'employees_id' => 4
        ]);

        User::create([
            'username' => 'admin_fs',
            'email' => 'fs@mail.com',
            'password' => Hash::make('qc058'),
            'roles_id' => 5,
            'employees_id' => 5
        ]);

        User::create([
            'username' => 'admin_ex',
            'email' => 'ex@mail.com',
            'password' => Hash::make('ex058'),
            'roles_id' => 6,
            'employees_id' => 6
        ]);

        User::create([
            'username' => 'admin_hr',
            'email' => 'hr@mail.com',
            'password' => Hash::make('hr058'),
            'roles_id' => 7,
            'employees_id' => 7
        ]);

        User::create([
            'username' => 'admin_ac',
            'email' => 'ac@mail.com',
            'password' => Hash::make('ac058'),
            'roles_id' => 8,
            'employees_id' => 8
        ]);
        
        User::create([
            'username' => 'admin_ga',
            'email' => 'ga@mail.com',
            'password' => Hash::make('ga058'),
            'roles_id' => 9,
            'employees_id' => 9
        ]);

        // User::create([
        //     'username' => 'regular_user',
        //     'email' => 'user@mail.com',
        //     'password' => Hash::make('user058'),
        //     'roles_id' => 10,
        //     'employees_id' => 10
        // ]);
    }
}
