<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\Kas;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Redirect based on role
        if ($user->hasRole('anggota')) {
            return $this->anggotaDashboard();
        } elseif ($user->hasRole('bendahara')) {
            return $this->bendaharaDashboard();
        } elseif ($user->hasRole('pengurus')) {
            return $this->pengurusDashboard();
        } else {
            return $this->adminDashboard();
        }
    }

    private function anggotaDashboard()
    {
        $user = auth()->user();
        $anggota = $user->anggota;

        if (!$anggota) {
            return redirect()->route('anggota.register');
        }

        $simpananVerified = $anggota->simpanans()->where('status', 'verified');
        $totalSimpanan = $simpananVerified->sum('nominal');
        $jumlahTransaksiSimpanan = $simpananVerified->count();
        $simpananTerakhir = $simpananVerified->latest()->limit(5)->get();

        $pinjamanAktif = $anggota->pinjamans()->where('status', 'berjalan')->first();
        $riwayatPinjaman = $anggota->pinjamans()->whereIn('status', ['lunas', 'berjalan'])->latest()->limit(3)->get();
        
        $angsuranBerikutnya = null;
        $angsuranTerlambat = 0;
        $totalDibayar = 0;
        $sisaAngsuran = 0;

        if ($pinjamanAktif) {
            $angsuranBerikutnya = $pinjamanAktif->angsurans()
                ->where('status', 'belum_bayar')
                ->orderBy('tanggal_jatuh_tempo')
                ->first();
                
            $angsuranTerlambat = $pinjamanAktif->angsurans()
                ->where('status', 'belum_bayar')
                ->whereDate('tanggal_jatuh_tempo', '<', now())
                ->count();
                
            $totalDibayar = $pinjamanAktif->angsurans()
                ->where('status', 'verified')
                ->sum('nominal');
                
            $sisaAngsuran = $pinjamanAktif->angsurans()
                ->where('status', 'belum_bayar')
                ->count();
        }

        $data = [
            'totalSimpanan' => $totalSimpanan,
            'jumlahTransaksiSimpanan' => $jumlahTransaksiSimpanan,
            'simpananTerakhir' => $simpananTerakhir,
            'pinjamanAktif' => $pinjamanAktif,
            'riwayatPinjaman' => $riwayatPinjaman,
            'angsuranBerikutnya' => $angsuranBerikutnya,
            'angsuranTerlambat' => $angsuranTerlambat,
            'totalDibayar' => $totalDibayar,
            'sisaAngsuran' => $sisaAngsuran,
        ];

        return view('dashboard.anggota', $data);
    }

    private function bendaharaDashboard()
    {
        $saldoKas = Kas::latest()->first()->saldo_sesudah ?? 0;
        
        // Transaksi bulan ini
        $startOfMonth = now()->startOfMonth();
        $pemasukan = Kas::where('jenis', 'masuk')
            ->where('tanggal_transaksi', '>=', $startOfMonth)
            ->sum('nominal');
        $pengeluaran = Kas::where('jenis', 'keluar')
            ->where('tanggal_transaksi', '>=', $startOfMonth)
            ->sum('nominal');
            
        // Pending verifications
        $simpananPending = Simpanan::where('status', 'pending')->count();
        $simpananPendingNominal = Simpanan::where('status', 'pending')->sum('nominal');
        
        $angsuranPending = Angsuran::where('status', 'belum_bayar')
            ->whereDate('tanggal_jatuh_tempo', '<=', now())
            ->count();
        $angsuranPendingNominal = Angsuran::where('status', 'belum_bayar')
            ->whereDate('tanggal_jatuh_tempo', '<=', now())
            ->sum('nominal');
            
        $pinjamanDicairkan = Pinjaman::where('status', 'approved_bendahara')->count();
        $pinjamanDicairkanNominal = Pinjaman::where('status', 'approved_bendahara')->sum('total_pinjaman');
        
        // Recent transactions
        $transaksiTerakhir = Kas::with('transactable')
            ->latest()
            ->limit(10)
            ->get();
            
        // Simpanan & Angsuran pending lists
        $simpananPendingList = Simpanan::with('anggota', 'jenisSimpanan')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();
            
        $angsuranJatuhTempo = Angsuran::with('pinjaman.anggota')
            ->where('status', 'belum_bayar')
            ->whereDate('tanggal_jatuh_tempo', '<=', now())
            ->orderBy('tanggal_jatuh_tempo')
            ->limit(5)
            ->get();

        $data = [
            'saldoKas' => $saldoKas,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'simpananPending' => $simpananPending,
            'simpananPendingNominal' => $simpananPendingNominal,
            'angsuranPending' => $angsuranPending,
            'angsuranPendingNominal' => $angsuranPendingNominal,
            'pinjamanDicairkan' => $pinjamanDicairkan,
            'pinjamanDicairkanNominal' => $pinjamanDicairkanNominal,
            'transaksiTerakhir' => $transaksiTerakhir,
            'simpananPendingList' => $simpananPendingList,
            'angsuranJatuhTempo' => $angsuranJatuhTempo,
        ];

        return view('dashboard.bendahara', $data);
    }

    private function pengurusDashboard()
    {
        // Anggota stats
        $anggotaPending = Anggota::where('status', 'pending')->count();
        $anggotaAktif = Anggota::where('status', 'active')->count();
        $anggotaNonaktif = Anggota::where('status', 'inactive')->count();
        $anggotaPendingList = Anggota::with('user')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();
        
        // Pinjaman stats
        $pinjamanPending = Pinjaman::where('status', 'pending')->count();
        $pinjamanPendingNominal = Pinjaman::where('status', 'pending')->sum('total_pinjaman');
        $pinjamanDiReview = Pinjaman::where('status', 'reviewed_pengurus')->count();
        $pinjamanAktif = Pinjaman::where('status', 'berjalan')->count();
        $pinjamanAktifNominal = Pinjaman::where('status', 'berjalan')->sum('sisa_pinjaman');
        
        $pinjamanPendingList = Pinjaman::with('anggota')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();
            
        // Activity bulan ini
        $startOfMonth = now()->startOfMonth();
        $anggotaBaruBulanIni = Anggota::where('status', 'active')
            ->where('tanggal_bergabung', '>=', $startOfMonth)
            ->count();
        $pinjamanBaruBulanIni = Pinjaman::whereIn('status', ['pending', 'reviewed_pengurus', 'approved_bendahara', 'berjalan'])
            ->where('tanggal_pengajuan', '>=', $startOfMonth)
            ->count();

        $data = [
            'anggotaPending' => $anggotaPending,
            'anggotaAktif' => $anggotaAktif,
            'anggotaNonaktif' => $anggotaNonaktif,
            'anggotaPendingList' => $anggotaPendingList,
            'pinjamanPending' => $pinjamanPending,
            'pinjamanPendingNominal' => $pinjamanPendingNominal,
            'pinjamanDiReview' => $pinjamanDiReview,
            'pinjamanAktif' => $pinjamanAktif,
            'pinjamanAktifNominal' => $pinjamanAktifNominal,
            'pinjamanPendingList' => $pinjamanPendingList,
            'anggotaBaruBulanIni' => $anggotaBaruBulanIni,
            'pinjamanBaruBulanIni' => $pinjamanBaruBulanIni,
        ];

        return view('dashboard.pengurus', $data);
    }

    private function adminDashboard()
    {
        $totalAnggota = Anggota::count();
        $totalSimpanan = Simpanan::sum('nominal');
        $saldoKas = Kas::sum('nominal');
        
        // Growth calculations
        $anggotaBulanIni = Anggota::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $anggotaBulanLalu = Anggota::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)->count();
        $pertumbuhanAnggota = $anggotaBulanLalu > 0 
            ? round((($anggotaBulanIni - $anggotaBulanLalu) / $anggotaBulanLalu) * 100, 1)
            : 0;

        $simpananBulanIni = Simpanan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->sum('nominal');
        $pertumbuhanSimpanan = 0;

        // Status counts
        $anggotaAktif = Anggota::where('status', 'aktif')->count();
        $anggotaMenunggu = Anggota::where('status', 'menunggu')->count();
        $anggotaDitolak = Anggota::where('status', 'ditolak')->count();
        $anggotaNonaktif = Anggota::where('status', 'non-aktif')->count();

        $pinjamanMenunggu = Pinjaman::where('status', 'menunggu')->count();
        $pinjamanBerjalan = Pinjaman::where('status', 'aktif')->count();
        $pinjamanLunas = Pinjaman::where('status', 'lunas')->count();
        
        // Calculate overdue loans
        $pinjamanBermasalah = Angsuran::where('status', 'terlambat')
            ->distinct('pinjaman_id')->count('pinjaman_id');

        // Financial data
        $pemasukanBulanIni = Kas::where('jenis', 'masuk')
            ->whereMonth('tanggal_transaksi', now()->month)
            ->whereYear('tanggal_transaksi', now()->year)
            ->sum('nominal');
        
        $pengeluaranBulanIni = Kas::where('jenis', 'keluar')
            ->whereMonth('tanggal_transaksi', now()->month)
            ->whereYear('tanggal_transaksi', now()->year)
            ->sum('nominal');

        // Payment rate
        $totalAngsuran = Angsuran::count();
        $angsuranTerbayar = Angsuran::where('status', 'lunas')->count();
        $tingkatPembayaran = $totalAngsuran > 0 ? round(($angsuranTerbayar / $totalAngsuran) * 100, 1) : 0;

        // Pending counts for alerts
        $angsuranTerlambat = Angsuran::where('status', 'terlambat')->count();
        $simpananPending = Simpanan::where('status', 'menunggu')->count();
        $pinjamanPending = Pinjaman::where('status', 'menunggu')->count();

        // Top borrowers
        $topPeminjam = Pinjaman::selectRaw('anggota_id, SUM(nominal_pinjaman) as nominal_total')
            ->where('status', '!=', 'ditolak')
            ->groupBy('anggota_id')
            ->orderByDesc('nominal_total')
            ->with('anggota.user')
            ->limit(5)
            ->get();

        // Recent transactions
        $transaksiTerbaru = Kas::orderByDesc('tanggal_transaksi')->limit(10)->get();
        $transaksiSimpananBulanIni = Simpanan::whereMonth('created_at', now()->month)->count();

        // Calculations for remaining data
        $sisaPinjaman = Pinjaman::where('status', '!=', 'lunas')->sum('sisa_pinjaman');

        return view('dashboard', [
            'totalAnggota' => $totalAnggota,
            'totalSimpanan' => $totalSimpanan,
            'saldoKas' => $saldoKas,
            'pertumbuhanAnggota' => $pertumbuhanAnggota,
            'pertumbuhanSimpanan' => $pertumbuhanSimpanan,
            'simpananBulanIni' => $simpananBulanIni,
            'anggotaAktif' => $anggotaAktif,
            'anggotaMenunggu' => $anggotaMenunggu,
            'anggotaDitolak' => $anggotaDitolak,
            'anggotaNonaktif' => $anggotaNonaktif,
            'pinjamanMenunggu' => $pinjamanMenunggu,
            'pinjamanBerjalan' => $pinjamanBerjalan,
            'pinjamanLunas' => $pinjamanLunas,
            'pinjamanBermasalah' => $pinjamanBermasalah,
            'pemasukanBulanIni' => $pemasukanBulanIni,
            'pengeluaranBulanIni' => $pengeluaranBulanIni,
            'tingkatPembayaran' => $tingkatPembayaran,
            'angsuranTerlambat' => $angsuranTerlambat,
            'simpananPending' => $simpananPending,
            'pinjamanPending' => $pinjamanPending,
            'topPeminjam' => $topPeminjam,
            'transaksiTerbaru' => $transaksiTerbaru,
            'transaksiSimpananBulanIni' => $transaksiSimpananBulanIni,
            'sisaPinjaman' => $sisaPinjaman,
            'totalNominalPinjaman' => Pinjaman::where('status', 'aktif')->sum('nominal_pinjaman'),
        ]);
    }
}

