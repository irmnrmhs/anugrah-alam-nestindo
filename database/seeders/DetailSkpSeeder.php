<?php

namespace Database\Seeders;

use App\Models\DetailSkp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailSkpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailSkp::create([
            'dcertificates_id' => 1,
            'tgl_panen' => '2025-12-23',
            'berat_panen' => 100,
            'tgl_kirim' => '2025-12-23',
            'berat_kirim' => 100
        ]);

        DetailSkp::create([
            'dcertificates_id' => 1,
            'tgl_panen' => '2025-12-24',
            'berat_panen' => 250,
            'tgl_kirim' => '2025-12-24',
            'berat_kirim' => 200
        ]);
    }
}
