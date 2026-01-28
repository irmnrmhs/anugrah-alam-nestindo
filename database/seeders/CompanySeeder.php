<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'ikh'       => '058',
            'nama'      => 'PT Anugrah Alam Nestindo',
            'alamat'    => 'Kp Bolenglang RT 002 RW 013, Sukasari, Cilaku, Kab. Cianjur, Jawa Barat',
            'telp'      => '+62 81318124220',
            'fax'       => '-',
            'negara'   => 'Indonesia',
        ]);
    }
}
