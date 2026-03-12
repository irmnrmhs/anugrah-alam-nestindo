<?php

namespace Database\Seeders;

use App\Models\DetailDocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailDocument::create([
            'documents_id' => 1,
            'employees_id'  => 1,
        ]);

        DetailDocument::create([
            'documents_id' => 2,
            'employees_id'  => 18,
        ]);

        DetailDocument::create([
            'documents_id' => 3,
            'employees_id'  => 18,
        ]);

        DetailDocument::create([
            'documents_id' => 4,
            'employees_id'  => 19,
        ]);

        DetailDocument::create([
            'documents_id' => 5,
            'employees_id'  => 19,
        ]);

        DetailDocument::create([
            'documents_id' => 6,
            'employees_id'  => 20,
        ]);

        DetailDocument::create([
            'documents_id' => 7,
            'employees_id'  => 21,
        ]);

        DetailDocument::create([
            'documents_id' => 8,
            'employees_id'  => 1,
        ]);

        DetailDocument::create([
            'documents_id' => 9,
            'employees_id'  => 22,
            'shift' => 1,
        ]);

        DetailDocument::create([
            'documents_id' => 9,
            'employees_id'  => 23,
            'shift' => 2,
        ]);

        DetailDocument::create([
            'documents_id' => 10,
            'employees_id'  => 24,
            'shift' => 1,
        ]);

        DetailDocument::create([
            'documents_id' => 10,
            'employees_id'  => 25,
            'shift' => 2,
        ]);

        DetailDocument::create([
            'documents_id' => 11,
            'employees_id'  => 19,
        ]);

        DetailDocument::create([
            'documents_id' => 12,
            'employees_id'  => 26,
            'shift' => 1,
        ]);

        DetailDocument::create([
            'documents_id' => 12,
            'employees_id'  => 27,
            'shift' => 2,
        ]);

        DetailDocument::create([
            'documents_id' => 13,
            'employees_id'  => 28,
            'shift' => 1,
        ]);

        DetailDocument::create([
            'documents_id' => 13,
            'employees_id'  => 29,
            'shift' => 2,
        ]);

        DetailDocument::create([
            'documents_id' => 14,
            'employees_id'  => 30,
        ]);

        DetailDocument::create([
            'documents_id' => 15,
            'employees_id'  => 31,
        ]);

        DetailDocument::create([
            'documents_id' => 16,
            'employees_id'  => 1,
        ]);

        DetailDocument::create([
            'documents_id' => 17,
            'employees_id'  => 8,
        ]);

        DetailDocument::create([
            'documents_id' => 18,
            'employees_id'  => 7,
        ]);

        DetailDocument::create([
            'documents_id' => 19,
            'employees_id'  => 6,
        ]);

        DetailDocument::create([
            'documents_id' => 20,
            'employees_id'  => 7,
        ]);

        DetailDocument::create([
            'documents_id' => 21,
            'employees_id'  => 7,
        ]);

        DetailDocument::create([
            'documents_id' => 22,
            'employees_id'  => 6,
        ]);

        DetailDocument::create([
            'documents_id' => 23,
            'employees_id'  => 7,
        ]);

        DetailDocument::create([
            'documents_id' => 24,
            'employees_id'  => 6,
        ]);
    }
}
