<?php

namespace Database\Seeders;

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
            PositionSeeder::class,
            EmployeeSeeder::class,
            // StepSeeder::class,
            DocumentSeeder::class,
            DetailDocumentSeeder::class,
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
            DetailSkpSeeder::class,
            ArrivalSeeder::class,
            ContainerSeeder::class,
            FpGradeSeeder::class,
            NestTypeSeeder::class,
            SteamOfficerSeeder::class,
            PackageTypeSeeder::class,
            PackageSeeder::class,
        ]);
        
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
