<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Document::create([
            'depts_id'  => 1,
            'no'        => 'AAN/FRM/RW/01/04',
            'name'      => 'Surat Keterangan Pengiriman',
            'rev'       => 1,
        ]);
    }
}
