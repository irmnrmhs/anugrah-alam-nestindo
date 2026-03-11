<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::create([
            'types_id' => 1,
            'bahan' => 'Nylon-LLDPE Seal Bag Standard',
            'satuan' => 'mm',
            'panjang' => 160,
            'lebar' => 110,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 1,
            'bahan' => 'Nylon-LLDPE Seal Bag Medium',
            'satuan' => 'mm',
            'panjang' => 250,
            'lebar' => 200,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 1,
            'bahan' => 'Nylon-LLDPE Seal Bag Large',
            'satuan' => 'mm',
            'panjang' => 300,
            'lebar' => 250,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 2,
            'bahan' => 'Shrink Bag',
            'satuan' => 'mm',
            'panjang' => 189,
            'lebar' => 200,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 2,
            'bahan' => 'Shrink 1/2',
            'satuan' => 'mm',
            'panjang' => 160,
            'lebar' => 118,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 2,
            'bahan' => 'Shrink 1/3',
            'satuan' => 'mm',
            'panjang' => 97,
            'lebar' => 118,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 3,
            'bahan' => 'Mika Polyethylene Terephthalate 1',
            'satuan' => 'mm',
            'panjang' => 250,
            'lebar' => 175,
            'tinggi' => 75,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 3,
            'bahan' => 'Mika Polyethylene Terephthalate 2',
            'satuan' => 'mm',
            'panjang' => 250,
            'lebar' => 250,
            'tinggi' => 85,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 3,
            'bahan' => 'Mika Polyethylene Terephthalate 3',
            'satuan' => 'mm',
            'panjang' => 249,
            'lebar' => 189,
            'tinggi' => 118,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 3,
            'bahan' => 'Mika Polyethylene Terephthalate 4',
            'satuan' => 'mm',
            'panjang' => 338,
            'lebar' => 268,
            'tinggi' => 50,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 4,
            'bahan' => 'Cemaran Mikroba (Microbial contamination)',
            'lainnya' => 'Bersih (Clean)',
        ]);

        Package::create([
            'types_id' => 5,
            'bahan' => 'Box kardus (Cardboard box)',
            'satuan' => 'mm',
            'panjang' => 620,
            'lebar' => 620,
            'tinggi' => 620,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 6,
            'bahan' => 'Donatsu Polypropylene 1',
            'satuan' => 'mm',
            'panjang' => 340,
            'lebar' => 270,
            'tinggi' => 75,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 6,
            'bahan' => 'Donatsu Polypropylene 2',
            'satuan' => 'mm',
            'panjang' => 365,
            'lebar' => 290,
            'tinggi' => 150,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 7,
            'bahan' => 'Multipack Polypropylene',
            'satuan' => 'mm',
            'panjang' => 350,
            'lebar' => 280,
            'tinggi' => 120,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 8,
            'bahan' => 'Basic Home Polypropylene',
            'satuan' => 'mm',
            'panjang' => 365,
            'lebar' => 285,
            'tinggi' => 80,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 9,
            'bahan' => 'Hardtop 901',
            'satuan' => 'mm',
            'panjang' => 145,
            'lebar' => 80,
            'tinggi' => 70,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 9,
            'bahan' => 'Hardtop 588',
            'satuan' => 'mm',
            'panjang' => 130,
            'lebar' => 110,
            'tinggi' => 95,
            'toleransi' => 5,
        ]);

        Package::create([
            'types_id' => 9,
            'bahan' => 'Hardtop 610',
            'satuan' => 'mm',
            'panjang' => 160,
            'lebar' => 105,
            'tinggi' => 68,
            'toleransi' => 5,
        ]);
    }
}
