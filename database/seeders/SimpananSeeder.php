<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\Simpanan;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SimpananSeeder extends Seeder
{
    public function run(): void
    {
        $anggotas = Anggota::where('status', 'active')->get();
        $jenisSimpanan = JenisSimpanan::all();

        if ($anggotas->isEmpty() || $jenisSimpanan->isEmpty()) {
            $this->command->warn('⚠ Anggota atau Jenis Simpanan belum ada. Jalankan AnggotaSeeder dan JenisSimpananSeeder terlebih dahulu.');
            return;
        }

        $simpananPokok = $jenisSimpanan->where('kode', 'POKOK')->first();
        $simpananWajib = $jenisSimpanan->where('kode', 'WAJIB')->first();
        $simpananSukarela = $jenisSimpanan->where('kode', 'SUKARELA')->first();

        if (!$simpananPokok || !$simpananWajib || !$simpananSukarela) {
            $this->command->warn('⚠ Jenis simpanan tidak lengkap.');
            return;
        }

        foreach ($anggotas as $anggota) {
            $approvedDate = Carbon::parse($anggota->approved_at);
            
            // Simpanan Pokok (sekali di awal)
            Simpanan::create([
                'anggota_id' => $anggota->id,
                'jenis_simpanan_id' => $simpananPokok->id,
                'tanggal_transaksi' => $approvedDate,
                'nominal' => 1000000, // 1 juta
                'metode_pembayaran' => 'tunai',
                'status' => 'verified',
                'verified_by' => 3, // bendahara
                'verified_at' => $approvedDate->copy()->addHours(2),
                'keterangan' => 'Simpanan Pokok awal bergabung',
            ]);

            // Simpanan Wajib (3 bulan terakhir)
            for ($i = 3; $i >= 1; $i--) {
                Simpanan::create([
                    'anggota_id' => $anggota->id,
                    'jenis_simpanan_id' => $simpananWajib->id,
                    'tanggal_transaksi' => now()->subMonths($i)->startOfMonth(),
                    'nominal' => 200000, // 200 ribu per bulan
                    'metode_pembayaran' => 'transfer',
                    'status' => 'verified',
                    'verified_by' => 3,
                    'verified_at' => now()->subMonths($i)->startOfMonth()->addHours(5),
                    'keterangan' => 'Simpanan Wajib bulan ' . now()->subMonths($i)->format('F Y'),
                ]);
            }

            // Simpanan Sukarela (random jumlah)
            $sukarelaDates = [
                now()->subDays(45),
                now()->subDays(30),
                now()->subDays(15),
            ];

            $sukarelaAmounts = [500000, 750000, 1000000];

            foreach ($sukarelaDates as $index => $date) {
                if (rand(0, 1)) { // 50% chance untuk simpanan sukarela
                    Simpanan::create([
                        'anggota_id' => $anggota->id,
                        'jenis_simpanan_id' => $simpananSukarela->id,
                        'tanggal_transaksi' => $date,
                        'nominal' => $sukarelaAmounts[array_rand($sukarelaAmounts)],
                        'metode_pembayaran' => rand(0, 1) ? 'tunai' : 'transfer',
                        'status' => 'verified',
                        'verified_by' => 3,
                        'verified_at' => $date->copy()->addHours(3),
                        'keterangan' => 'Simpanan Sukarela',
                    ]);
                }
            }
        }

        // Tambah beberapa simpanan yang masih pending verification
        $anggotaSample = $anggotas->random(2);
        foreach ($anggotaSample as $anggota) {
            Simpanan::create([
                'anggota_id' => $anggota->id,
                'jenis_simpanan_id' => $simpananWajib->id,
                'tanggal_transaksi' => now(),
                'nominal' => 200000,
                'metode_pembayaran' => 'transfer',
                'status' => 'pending',
                'keterangan' => 'Menunggu verifikasi bendahara',
            ]);
        }

        $totalSimpanan = Simpanan::count();
        $this->command->info("✓ Created {$totalSimpanan} simpanan transactions");
    }
}
