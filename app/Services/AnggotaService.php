<?php

namespace App\Services;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AnggotaService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Create user account
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Assign role anggota
            $user->assignRole('anggota');

            // Generate no_anggota
            $lastAnggota = Anggota::latest('id')->first();
            $nextNumber = $lastAnggota ? ((int) substr($lastAnggota->no_anggota, 1)) + 1 : 1;
            $noAnggota = 'A' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            // Create anggota profile
            $anggota = Anggota::create([
                'user_id' => $user->id,
                'no_anggota' => $noAnggota,
                'nik' => $data['nik'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat' => $data['alamat'],
                'no_telepon' => $data['no_telepon'],
                'pekerjaan' => $data['pekerjaan'],
                'foto' => $data['foto'] ?? null,
                'status' => 'pending',
                'tanggal_daftar' => now(),
            ]);

            return $anggota;
        });
    }

    public function update(Anggota $anggota, array $data)
    {
        return DB::transaction(function () use ($anggota, $data) {
            // Update user if email or name changed
            if (isset($data['email']) || isset($data['name'])) {
                $anggota->user->update([
                    'name' => $data['name'] ?? $anggota->user->name,
                    'email' => $data['email'] ?? $anggota->user->email,
                ]);
            }

            // Update anggota profile
            $anggota->update([
                'nik' => $data['nik'] ?? $anggota->nik,
                'tempat_lahir' => $data['tempat_lahir'] ?? $anggota->tempat_lahir,
                'tanggal_lahir' => $data['tanggal_lahir'] ?? $anggota->tanggal_lahir,
                'jenis_kelamin' => $data['jenis_kelamin'] ?? $anggota->jenis_kelamin,
                'alamat' => $data['alamat'] ?? $anggota->alamat,
                'no_telepon' => $data['no_telepon'] ?? $anggota->no_telepon,
                'pekerjaan' => $data['pekerjaan'] ?? $anggota->pekerjaan,
                'foto' => $data['foto'] ?? $anggota->foto,
            ]);

            return $anggota->fresh();
        });
    }

    public function approve(Anggota $anggota, $approvedBy)
    {
        $anggota->update([
            'status' => 'active',
            'tanggal_approve' => now(),
            'approved_by' => $approvedBy,
        ]);

        return $anggota;
    }

    public function reject(Anggota $anggota, string $alasan)
    {
        $anggota->update([
            'status' => 'pending',
            'alasan_reject' => $alasan,
        ]);

        return $anggota;
    }

    public function changeStatus(Anggota $anggota, string $status)
    {
        $anggota->update(['status' => $status]);
        return $anggota;
    }
}
