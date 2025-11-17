<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Anggota;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        // Sample anggota yang sudah approved
        $anggotaData = [
            [
                'user' => [
                    'name' => 'Ahmad Subarjo',
                    'email' => 'ahmad.subarjo@example.com',
                    'password' => Hash::make('password'),
                ],
                'anggota' => [
                    'no_anggota' => 'AUTO2501001',
                    'nik' => '3201012345670001',
                    'tempat_lahir' => 'Bandung',
                    'tanggal_lahir' => '1985-05-15',
                    'jenis_kelamin' => 'L',
                    'alamat' => 'Jl. Merdeka No. 123, Bandung',
                    'no_telepon' => '081234567801',
                    'pekerjaan' => 'Pegawai Swasta',
                    'status' => 'active',
                    'approved_by' => 2, // approved by pengurus
                    'approved_at' => now()->subDays(30),
                ],
            ],
            [
                'user' => [
                    'name' => 'Siti Nurhaliza',
                    'email' => 'siti.nurhaliza@example.com',
                    'password' => Hash::make('password'),
                ],
                'anggota' => [
                    'no_anggota' => 'AUTO2501002',
                    'nik' => '3201012345670002',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '1990-08-20',
                    'jenis_kelamin' => 'P',
                    'alamat' => 'Jl. Sudirman No. 456, Jakarta',
                    'no_telepon' => '081234567802',
                    'pekerjaan' => 'Guru',
                    'status' => 'active',
                    'approved_by' => 2,
                    'approved_at' => now()->subDays(25),
                ],
            ],
            [
                'user' => [
                    'name' => 'Budi Santoso',
                    'email' => 'budi.santoso@example.com',
                    'password' => Hash::make('password'),
                ],
                'anggota' => [
                    'no_anggota' => 'AUTO2501003',
                    'nik' => '3201012345670003',
                    'tempat_lahir' => 'Surabaya',
                    'tanggal_lahir' => '1988-03-10',
                    'jenis_kelamin' => 'L',
                    'alamat' => 'Jl. Pahlawan No. 789, Surabaya',
                    'no_telepon' => '081234567803',
                    'pekerjaan' => 'Wiraswasta',
                    'status' => 'active',
                    'approved_by' => 2,
                    'approved_at' => now()->subDays(20),
                ],
            ],
            [
                'user' => [
                    'name' => 'Dewi Lestari',
                    'email' => 'dewi.lestari@example.com',
                    'password' => Hash::make('password'),
                ],
                'anggota' => [
                    'no_anggota' => 'AUTO2501004',
                    'nik' => '3201012345670004',
                    'tempat_lahir' => 'Yogyakarta',
                    'tanggal_lahir' => '1992-11-05',
                    'jenis_kelamin' => 'P',
                    'alamat' => 'Jl. Malioboro No. 321, Yogyakarta',
                    'no_telepon' => '081234567804',
                    'pekerjaan' => 'Pegawai Negeri',
                    'status' => 'active',
                    'approved_by' => 2,
                    'approved_at' => now()->subDays(15),
                ],
            ],
            [
                'user' => [
                    'name' => 'Eko Prasetyo',
                    'email' => 'eko.prasetyo@example.com',
                    'password' => Hash::make('password'),
                ],
                'anggota' => [
                    'no_anggota' => 'AUTO2501005',
                    'nik' => '3201012345670005',
                    'tempat_lahir' => 'Semarang',
                    'tanggal_lahir' => '1987-07-25',
                    'jenis_kelamin' => 'L',
                    'alamat' => 'Jl. Pemuda No. 654, Semarang',
                    'no_telepon' => '081234567805',
                    'pekerjaan' => 'Pengusaha',
                    'status' => 'active',
                    'approved_by' => 2,
                    'approved_at' => now()->subDays(10),
                ],
            ],
        ];

        foreach ($anggotaData as $data) {
            $user = User::create($data['user']);
            $user->assignRole('anggota');
            
            $anggota = new Anggota($data['anggota']);
            $anggota->user_id = $user->id;
            $anggota->save();
        }

        // Sample anggota yang masih pending approval
        $pendingData = [
            [
                'user' => [
                    'name' => 'Rudi Hermawan',
                    'email' => 'rudi.hermawan@example.com',
                    'password' => Hash::make('password'),
                ],
                'anggota' => [
                    'nik' => '3201012345670006',
                    'tempat_lahir' => 'Medan',
                    'tanggal_lahir' => '1995-02-14',
                    'jenis_kelamin' => 'L',
                    'alamat' => 'Jl. Asia No. 111, Medan',
                    'no_telepon' => '081234567806',
                    'pekerjaan' => 'Karyawan Bank',
                    'status' => 'pending',
                ],
            ],
            [
                'user' => [
                    'name' => 'Linda Wijaya',
                    'email' => 'linda.wijaya@example.com',
                    'password' => Hash::make('password'),
                ],
                'anggota' => [
                    'nik' => '3201012345670007',
                    'tempat_lahir' => 'Palembang',
                    'tanggal_lahir' => '1993-09-30',
                    'jenis_kelamin' => 'P',
                    'alamat' => 'Jl. Ampera No. 222, Palembang',
                    'no_telepon' => '081234567807',
                    'pekerjaan' => 'Dokter',
                    'status' => 'pending',
                ],
            ],
        ];

        foreach ($pendingData as $data) {
            $user = User::create($data['user']);
            $user->assignRole('anggota');
            
            $anggota = new Anggota($data['anggota']);
            $anggota->user_id = $user->id;
            $anggota->save();
        }

        $this->command->info('✓ Created 5 active anggota and 2 pending anggota');
    }
}
