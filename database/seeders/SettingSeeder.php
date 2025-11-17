<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Koperasi Info
        Setting::create([
            'key' => 'koperasi_name',
            'value' => 'Koperasi Sejahtera',
            'type' => 'string',
            'group' => 'general',
            'deskripsi' => 'Nama Koperasi',
        ]);

        Setting::create([
            'key' => 'koperasi_address',
            'value' => 'Jl. Contoh No. 123, Jakarta',
            'type' => 'string',
            'group' => 'general',
            'deskripsi' => 'Alamat Koperasi',
        ]);

        Setting::create([
            'key' => 'koperasi_phone',
            'value' => '021-12345678',
            'type' => 'string',
            'group' => 'general',
            'deskripsi' => 'Telepon Koperasi',
        ]);

        Setting::create([
            'key' => 'koperasi_email',
            'value' => 'info@koperasi.com',
            'type' => 'string',
            'group' => 'general',
            'deskripsi' => 'Email Koperasi',
        ]);

        // Pinjaman Settings
        Setting::create([
            'key' => 'bunga_pinjaman',
            'value' => '2',
            'type' => 'number',
            'group' => 'pinjaman',
            'deskripsi' => 'Bunga pinjaman per bulan (%)',
        ]);

        Setting::create([
            'key' => 'denda_keterlambatan',
            'value' => '1',
            'type' => 'number',
            'group' => 'pinjaman',
            'deskripsi' => 'Denda keterlambatan angsuran per hari (%)',
        ]);

        Setting::create([
            'key' => 'limit_pinjaman_multiplier',
            'value' => '3',
            'type' => 'number',
            'group' => 'pinjaman',
            'deskripsi' => 'Maksimal pinjaman = total simpanan x multiplier',
        ]);

        Setting::create([
            'key' => 'tenor_maksimal',
            'value' => '24',
            'type' => 'number',
            'group' => 'pinjaman',
            'deskripsi' => 'Tenor maksimal pinjaman (bulan)',
        ]);
    }
}

