<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@koperasi.com',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('super-admin');

        // Pengurus
        $pengurus = User::create([
            'name' => 'Budi Pengurus',
            'email' => 'pengurus@koperasi.com',
            'password' => Hash::make('password'),
        ]);
        $pengurus->assignRole('pengurus');

        // Bendahara
        $bendahara = User::create([
            'name' => 'Siti Bendahara',
            'email' => 'bendahara@koperasi.com',
            'password' => Hash::make('password'),
        ]);
        $bendahara->assignRole('bendahara');

        // Anggota Demo
        $anggotaUser = User::create([
            'name' => 'Andi Anggota',
            'email' => 'anggota@koperasi.com',
            'password' => Hash::make('password'),
        ]);
        $anggotaUser->assignRole('anggota');
        
        // Create Anggota Profile
        Anggota::create([
            'user_id' => $anggotaUser->id,
            'no_anggota' => 'A00001',
            'nik' => '3201234567890001',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Contoh No. 123, Jakarta',
            'no_telepon' => '081234567890',
            'pekerjaan' => 'Karyawan Swasta',
            'status' => 'active',
            'approved_by' => $pengurus->id,
            'approved_at' => now(),
        ]);
    }
}

