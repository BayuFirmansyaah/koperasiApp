<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use Illuminate\Database\Seeder;

class PinjamanSeeder extends Seeder
{
    public function run(): void
    {
        $anggotas = Anggota::where('status', 'active')->take(3)->get();

        if ($anggotas->isEmpty()) {
            $this->command->warn('⚠ Anggota belum ada. Jalankan AnggotaSeeder terlebih dahulu.');
            return;
        }

        // Pinjaman 1: Lunas
        $pinjaman1 = Pinjaman::create([
            'anggota_id' => $anggotas[0]->id,
            'no_pinjaman' => 'PJM2501001',
            'nominal_pinjaman' => 5000000,
            'bunga_persen' => 2.0,
            'nominal_bunga' => 600000, // 2% x 12 bulan x 5jt
            'total_pinjaman' => 5600000,
            'tenor_bulan' => 12,
            'nominal_angsuran_per_bulan' => 466667, // 5.6jt / 12
            'tanggal_pengajuan' => now()->subMonths(13),
            'tanggal_approve' => now()->subMonths(13)->addDays(2),
            'tanggal_pencairan' => now()->subMonths(13)->addDays(3),
            'status' => 'lunas',
            'approved_by_pengurus' => 2,
            'approved_by_bendahara' => 3,
            'alasan_pengajuan' => 'Modal usaha warung kelontong',
            'sisa_pinjaman' => 0,
        ]);

        // Generate angsuran untuk pinjaman lunas
        for ($i = 1; $i <= 12; $i++) {
            Angsuran::create([
                'pinjaman_id' => $pinjaman1->id,
                'angsuran_ke' => $i,
                'tanggal_jatuh_tempo' => now()->subMonths(13 - $i),
                'tanggal_bayar' => now()->subMonths(13 - $i)->addDays(rand(-2, 2)),
                'nominal_angsuran' => 466667,
                'denda' => 0,
                'total_bayar' => 466667,
                'status' => 'sudah_bayar',
                'verified_by' => 3,
                'keterangan' => 'Angsuran ke-' . $i,
            ]);
        }

        // Pinjaman 2: Berjalan (sudah bayar 6 dari 12 angsuran)
        $pinjaman2 = Pinjaman::create([
            'anggota_id' => $anggotas[1]->id,
            'no_pinjaman' => 'PJM2501002',
            'nominal_pinjaman' => 10000000,
            'bunga_persen' => 2.0,
            'nominal_bunga' => 1200000,
            'total_pinjaman' => 11200000,
            'tenor_bulan' => 12,
            'nominal_angsuran_per_bulan' => 933333,
            'tanggal_pengajuan' => now()->subMonths(7),
            'tanggal_approve' => now()->subMonths(7)->addDays(1),
            'tanggal_pencairan' => now()->subMonths(7)->addDays(2),
            'status' => 'berjalan',
            'approved_by_pengurus' => 2,
            'approved_by_bendahara' => 3,
            'alasan_pengajuan' => 'Renovasi rumah',
            'sisa_pinjaman' => 5600000, // 6 x 933333
        ]);

        // Angsuran yang sudah dibayar (6 bulan)
        for ($i = 1; $i <= 6; $i++) {
            Angsuran::create([
                'pinjaman_id' => $pinjaman2->id,
                'angsuran_ke' => $i,
                'tanggal_jatuh_tempo' => now()->subMonths(7 - $i),
                'tanggal_bayar' => now()->subMonths(7 - $i)->addDays(rand(0, 3)),
                'nominal_angsuran' => 933333,
                'denda' => 0,
                'total_bayar' => 933333,
                'status' => 'sudah_bayar',
                'verified_by' => 3,
                'keterangan' => 'Angsuran ke-' . $i,
            ]);
        }

        // Angsuran yang belum dibayar (6 bulan)
        for ($i = 7; $i <= 12; $i++) {
            $jatuhTempo = now()->subMonths(7 - $i);
            $status = $jatuhTempo < now() ? 'telat' : 'belum_bayar';
            $denda = $status === 'telat' ? 50000 * (now()->diffInDays($jatuhTempo)) : 0;

            Angsuran::create([
                'pinjaman_id' => $pinjaman2->id,
                'angsuran_ke' => $i,
                'tanggal_jatuh_tempo' => $jatuhTempo,
                'nominal_angsuran' => 933333,
                'denda' => $denda,
                'total_bayar' => 0,
                'status' => $status,
                'keterangan' => $i == 7 ? 'Jatuh tempo bulan ini' : 'Belum jatuh tempo',
            ]);
        }

        // Pinjaman 3: Pending Approval (baru diajukan)
        Pinjaman::create([
            'anggota_id' => $anggotas[2]->id,
            'no_pinjaman' => 'PJM2501003',
            'nominal_pinjaman' => 7500000,
            'bunga_persen' => 2.0,
            'nominal_bunga' => 900000,
            'total_pinjaman' => 8400000,
            'tenor_bulan' => 10,
            'nominal_angsuran_per_bulan' => 840000,
            'tanggal_pengajuan' => now()->subDays(2),
            'status' => 'pending',
            'alasan_pengajuan' => 'Biaya pendidikan anak',
            'sisa_pinjaman' => 8400000,
        ]);

        $totalPinjaman = Pinjaman::count();
        $totalAngsuran = Angsuran::count();
        $this->command->info("✓ Created {$totalPinjaman} pinjaman with {$totalAngsuran} angsuran records");
    }
}
