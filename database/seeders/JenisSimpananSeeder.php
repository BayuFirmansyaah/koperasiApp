<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisSimpanan;

class JenisSimpananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisSimpanan::create([
            'nama' => 'Simpanan Pokok',
            'kode' => 'POKOK',
            'nominal_minimum' => 1000000,
            'is_mandatory' => true,
            'deskripsi' => 'Simpanan yang dibayarkan sekali saat menjadi anggota',
        ]);

        JenisSimpanan::create([
            'nama' => 'Simpanan Wajib',
            'kode' => 'WAJIB',
            'nominal_minimum' => 200000,
            'is_mandatory' => true,
            'deskripsi' => 'Simpanan yang dibayarkan rutin setiap bulan',
        ]);

        JenisSimpanan::create([
            'nama' => 'Simpanan Sukarela',
            'kode' => 'SUKARELA',
            'nominal_minimum' => 100000,
            'is_mandatory' => false,
            'deskripsi' => 'Simpanan yang dapat dilakukan kapan saja sesuai keinginan anggota',
        ]);
    }
}

