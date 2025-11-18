<?php

namespace Database\Seeders;

use App\Models\Arrival;
use App\Models\Dcertificate;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CompanySeeder::class,
            DepartmentSeeder::class,
            EmployeeSeeder::class,
            UserSeeder::class,

            AreaSeeder::class,
            CarSeeder::class,
            WBHouseSeeder::class,

            CategorySeeder::class,
            SupplierSeeder::class,
            CustomerSeeder::class,
            FeatherSeeder::class,
            ColorSeeder::class,
            ShapeSeeder::class,
            GradeSeeder::class,
            TestTypeSeeder::class,
            DcertificateSeeder::class,
            ArrivalSeeder::class,


        ]);
        
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
