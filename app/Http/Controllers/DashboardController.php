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
        // User & Anggota Stats
        $totalUsers = User::count();
        $totalAnggota = Anggota::where('status', 'active')->count();
        $anggotaPending = Anggota::where('status', 'pending')->count();
        $anggotaAktif = Anggota::where('status', 'active')->count();
        $anggotaMenunggu = Anggota::where('status', 'pending')->count();
        $anggotaDitolak = Anggota::where('status', 'rejected')->count();
        $anggotaNonaktif = Anggota::where('status', 'inactive')->count();
        
        // Financial Stats
        $totalSimpanan = Simpanan::where('status', 'verified')->sum('nominal');
        $simpananBulanIni = Simpanan::where('status', 'verified')
            ->where('tanggal_transaksi', '>=', now()->startOfMonth())
            ->sum('nominal');
        $transaksiSimpananBulanIni = Simpanan::where('status', 'verified')
            ->where('tanggal_transaksi', '>=', now()->startOfMonth())
            ->count();
        $simpananMinggulalu = Simpanan::where('status', 'verified')
            ->whereBetween('tanggal_transaksi', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->sum('nominal');
            
        $totalPinjaman = Pinjaman::whereIn('status', ['berjalan', 'approved_bendahara'])->count();
        $pinjamanMenunggu = Pinjaman::where('status', 'pending')->count();
        $pinjamanDiluluskan = Pinjaman::where('status', 'approved_bendahara')->count();
        $pinjamanBerjalan = Pinjaman::where('status', 'berjalan')->count();
        $pinjamanDitolak = Pinjaman::where('status', 'rejected')->count();
        $sisaPinjaman = Pinjaman::whereIn('status', ['berjalan', 'approved_bendahara'])->sum('sisa_pinjaman');
        $pinjamanLunas = Pinjaman::where('status', 'lunas')->count();
        $totalNominalPinjaman = Pinjaman::whereIn('status', ['berjalan', 'approved_bendahara'])->sum('total_pinjaman');
        
        $saldoKas = Kas::latest()->first()->saldo_sesudah ?? 0;
        $pemasukanBulanIni = Kas::where('jenis', 'masuk')
            ->where('tanggal_transaksi', '>=', now()->startOfMonth())
            ->sum('nominal');
        $pengeluaranBulanIni = Kas::where('jenis', 'keluar')
            ->where('tanggal_transaksi', '>=', now()->startOfMonth())
            ->sum('nominal');
        
        // Insights & Alerts
        $angsuranTerlambat = Angsuran::where('status', 'belum_bayar')
            ->whereDate('tanggal_jatuh_tempo', '<', now()->toDateString())
            ->count();
        $simpananPending = Simpanan::where('status', 'pending')->count();
        $pinjamanPending = Pinjaman::where('status', 'pending')->count();
        
        // Portfolio Quality
        $totalAngsuran = Angsuran::where('status', 'verified')->count();
        $angsuranTerbayar = Angsuran::where('status', 'verified')->count();
        $tingkatPembayaran = $totalAngsuran > 0 ? round(($angsuranTerbayar / $totalAngsuran) * 100, 1) : 0;
        
        // Monthly comparison
        $anggotaBulanLalu = Anggota::where('status', 'active')
            ->where('created_at', '<', now()->startOfMonth())
            ->count();
        $pertumbuhanAnggota = $anggotaBulanLalu > 0 
            ? round((($totalAnggota - $anggotaBulanLalu) / $anggotaBulanLalu) * 100, 1)
            : 0;
        
        $simpananBulanLalu = Simpanan::where('status', 'verified')
            ->whereBetween('tanggal_transaksi', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('nominal');
        $pertumbuhanSimpanan = $simpananBulanLalu > 0
            ? round((($simpananBulanIni - $simpananBulanLalu) / $simpananBulanLalu) * 100, 1)
            : 0;
        
        // Risk Analysis
        $pinjamanBermasalah = Pinjaman::where('status', 'berjalan')
            ->whereHas('angsurans', function($q) {
                $q->where('status', 'belum_bayar')
                  ->whereDate('tanggal_jatuh_tempo', '<', now());
            })
            ->count();
        
        // Recent Activity
        $anggotaTerbaru = Anggota::with('user')
            ->where('status', 'active')
            ->latest()
            ->limit(5)
            ->get();
            
        $pinjamanTerbaru = Pinjaman::with('anggota')
            ->latest()
            ->limit(5)
            ->get();
            
        $transaksiTerbaru = Kas::with('transactable')
            ->latest()
            ->limit(10)
            ->get();
        
        // Top performers & Problems
        $topPeminjam = Pinjaman::selectRaw('anggota_id, COUNT(*) as total_pinjaman, SUM(total_pinjaman) as nominal_total')
            ->groupBy('anggota_id')
            ->orderByDesc('nominal_total')
            ->limit(5)
            ->with('anggota')
            ->get();
        
        $anggotaTerbanyakAngsuran = Angsuran::selectRaw('pinjaman_id')
            ->where('status', 'belum_bayar')
            ->whereDate('tanggal_jatuh_tempo', '<', now())
            ->groupBy('pinjaman_id')
            ->with('pinjaman.anggota')
            ->limit(5)
            ->get();

        $data = [
            'totalUsers' => $totalUsers,
            'totalAnggota' => $totalAnggota,
            'anggotaPending' => $anggotaPending,
            'anggotaAktif' => $anggotaAktif,
            'anggotaMenunggu' => $anggotaMenunggu,
            'anggotaDitolak' => $anggotaDitolak,
            'anggotaNonaktif' => $anggotaNonaktif,
            'pertumbuhanAnggota' => $pertumbuhanAnggota,
            
            'totalSimpanan' => $totalSimpanan,
            'simpananBulanIni' => $simpananBulanIni,
            'simpananMinggulalu' => $simpananMinggulalu,
            'transaksiSimpananBulanIni' => $transaksiSimpananBulanIni,
            'pertumbuhanSimpanan' => $pertumbuhanSimpanan,
            
            'totalPinjaman' => $totalPinjaman,
            'totalNominalPinjaman' => $totalNominalPinjaman,
            'pinjamanMenunggu' => $pinjamanMenunggu,
            'pinjamanDiluluskan' => $pinjamanDiluluskan,
            'pinjamanBerjalan' => $pinjamanBerjalan,
            'pinjamanDitolak' => $pinjamanDitolak,
            'pinjamanBermasalah' => $pinjamanBermasalah,
            'sisaPinjaman' => $sisaPinjaman,
            'pinjamanLunas' => $pinjamanLunas,
            
            'saldoKas' => $saldoKas,
            'pemasukanBulanIni' => $pemasukanBulanIni,
            'pengeluaranBulanIni' => $pengeluaranBulanIni,
            
            'angsuranTerlambat' => $angsuranTerlambat,
            'simpananPending' => $simpananPending,
            'pinjamanPending' => $pinjamanPending,
            'tingkatPembayaran' => $tingkatPembayaran,
            
            'anggotaTerbaru' => $anggotaTerbaru,
            'pinjamanTerbaru' => $pinjamanTerbaru,
            'transaksiTerbaru' => $transaksiTerbaru,
            'topPeminjam' => $topPeminjam,
            'anggotaTerbanyakAngsuran' => $anggotaTerbanyakAngsuran,
        ];

        return view('dashboard', $data);
    }
}

