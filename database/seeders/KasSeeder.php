<?php

namespace Database\Seeders;

use App\Models\Kas;
use App\Models\Simpanan;
use App\Models\Angsuran;
use Illuminate\Database\Seeder;

class KasSeeder extends Seeder
{
    public function run(): void
    {
        $saldo = 0;

        // Saldo awal kas
        $saldo = 50000000; // 50 juta modal awal
        Kas::create([
            'tanggal_transaksi' => now()->subMonths(4),
            'jenis' => 'masuk',
            'kategori' => 'lainnya',
            'nominal' => 50000000,
            'saldo_sebelum' => 0,
            'saldo_sesudah' => $saldo,
            'keterangan' => 'Saldo awal kas koperasi',
            'created_by' => 3, // bendahara
        ]);

        // Catat semua simpanan yang verified ke kas
        $simpanans = Simpanan::where('status', 'verified')
            ->orderBy('tanggal_transaksi')
            ->get();

        foreach ($simpanans as $simpanan) {
            $saldoSebelum = $saldo;
            $saldo += $simpanan->nominal;

            Kas::create([
                'tanggal_transaksi' => $simpanan->tanggal_transaksi,
                'jenis' => 'masuk',
                'kategori' => 'simpanan',
                'nominal' => $simpanan->nominal,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldo,
                'referensi_id' => $simpanan->id,
                'referensi_type' => 'App\Models\Simpanan',
                'keterangan' => 'Simpanan ' . $simpanan->jenisSimpanan->nama . ' - ' . $simpanan->anggota->user->name,
                'created_by' => 3,
            ]);
        }

        // Catat pencairan pinjaman sebagai kas keluar
        $pinjamanDicairkan = \App\Models\Pinjaman::whereNotNull('tanggal_pencairan')
            ->orderBy('tanggal_pencairan')
            ->get();

        foreach ($pinjamanDicairkan as $pinjaman) {
            $saldoSebelum = $saldo;
            $saldo -= $pinjaman->nominal_pinjaman;

            Kas::create([
                'tanggal_transaksi' => $pinjaman->tanggal_pencairan,
                'jenis' => 'keluar',
                'kategori' => 'pinjaman_dicairkan',
                'nominal' => $pinjaman->nominal_pinjaman,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldo,
                'referensi_id' => $pinjaman->id,
                'referensi_type' => 'App\Models\Pinjaman',
                'keterangan' => 'Pencairan pinjaman ' . $pinjaman->no_pinjaman . ' - ' . $pinjaman->anggota->user->name,
                'created_by' => 3,
            ]);
        }

        // Catat pembayaran angsuran sebagai kas masuk
        $angsuranBayar = Angsuran::where('status', 'sudah_bayar')
            ->orderBy('tanggal_bayar')
            ->get();

        foreach ($angsuranBayar as $angsuran) {
            $saldoSebelum = $saldo;
            $saldo += $angsuran->total_bayar;

            Kas::create([
                'tanggal_transaksi' => $angsuran->tanggal_bayar,
                'jenis' => 'masuk',
                'kategori' => 'angsuran',
                'nominal' => $angsuran->total_bayar,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldo,
                'referensi_id' => $angsuran->id,
                'referensi_type' => 'App\Models\Angsuran',
                'keterangan' => 'Angsuran ke-' . $angsuran->angsuran_ke . ' pinjaman ' . $angsuran->pinjaman->no_pinjaman,
                'created_by' => 3,
            ]);
        }

        // Tambahkan beberapa transaksi operasional
        $operasionalData = [
            [
                'tanggal' => now()->subDays(20),
                'jenis' => 'keluar',
                'kategori' => 'operasional',
                'nominal' => 500000,
                'keterangan' => 'Biaya ATK dan perlengkapan kantor',
            ],
            [
                'tanggal' => now()->subDays(15),
                'jenis' => 'keluar',
                'kategori' => 'operasional',
                'nominal' => 300000,
                'keterangan' => 'Biaya listrik dan internet',
            ],
            [
                'tanggal' => now()->subDays(10),
                'jenis' => 'keluar',
                'kategori' => 'operasional',
                'nominal' => 200000,
                'keterangan' => 'Biaya konsumsi rapat pengurus',
            ],
        ];

        foreach ($operasionalData as $data) {
            $saldoSebelum = $saldo;
            $saldo -= $data['nominal'];

            Kas::create([
                'tanggal_transaksi' => $data['tanggal'],
                'jenis' => $data['jenis'],
                'kategori' => $data['kategori'],
                'nominal' => $data['nominal'],
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldo,
                'keterangan' => $data['keterangan'],
                'created_by' => 3,
            ]);
        }

        $totalKas = Kas::count();
        $saldoAkhir = Kas::latest('tanggal_transaksi')->first()->saldo_sesudah ?? 0;
        $this->command->info("✓ Created {$totalKas} kas transactions");
        $this->command->info("  Saldo akhir kas: Rp " . number_format($saldoAkhir, 0, ',', '.'));
    }
}
