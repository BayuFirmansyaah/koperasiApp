<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LaporanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-laporan-global|view-laporan-own'),
        ];
    }

    public function keuangan(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        $dateFrom = "{$year}-{$month}-01";
        $dateTo = date('Y-m-t', strtotime($dateFrom));

        // Summary data
        $totalSimpanan = Simpanan::whereBetween('tanggal_simpanan', [$dateFrom, $dateTo])
            ->where('status', 'verified')
            ->sum('nominal');

        $totalPencairan = Pinjaman::whereBetween('tanggal_pencairan', [$dateFrom, $dateTo])
            ->whereIn('status', ['berjalan', 'lunas'])
            ->sum('nominal');

        $totalAngsuran = Angsuran::whereBetween('tanggal_bayar', [$dateFrom, $dateTo])
            ->where('status', 'lunas')
            ->sum('nominal_bayar');

        $kasMasuk = Kas::whereBetween('tanggal_transaksi', [$dateFrom, $dateTo])
            ->where('tipe', 'masuk')
            ->sum('nominal');

        $kasKeluar = Kas::whereBetween('tanggal_transaksi', [$dateFrom, $dateTo])
            ->where('tipe', 'keluar')
            ->sum('nominal');

        $saldoAkhir = Kas::whereDate('tanggal_transaksi', '<=', $dateTo)
            ->latest('tanggal_transaksi')
            ->first();

        return view('laporan.keuangan', compact(
            'year',
            'month',
            'totalSimpanan',
            'totalPencairan',
            'totalAngsuran',
            'kasMasuk',
            'kasKeluar',
            'saldoAkhir'
        ));
    }

    public function anggota(Request $request)
    {
        $status = $request->get('status', 'active');

        $anggotas = Anggota::with(['user', 'simpanans', 'pinjamans'])
            ->when($status !== 'all', function($q) use ($status) {
                $q->where('status', $status);
            })
            ->get();

        $summary = [
            'total' => Anggota::count(),
            'active' => Anggota::where('status', 'active')->count(),
            'pending' => Anggota::where('status', 'pending')->count(),
            'inactive' => Anggota::where('status', 'inactive')->count(),
        ];

        return view('laporan.anggota', compact('anggotas', 'status', 'summary'));
    }

    public function rekeningKoran(Request $request, Anggota $anggota)
    {
        $this->authorize('viewRekeningKoran', $anggota);

        $dateFrom = $request->get('date_from', now()->startOfYear()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $simpanans = $anggota->simpanans()
            ->whereBetween('tanggal_simpanan', [$dateFrom, $dateTo])
            ->where('status', 'verified')
            ->get();

        $pinjamans = $anggota->pinjamans()
            ->whereBetween('tanggal_pencairan', [$dateFrom, $dateTo])
            ->whereIn('status', ['berjalan', 'lunas'])
            ->with('angsurans')
            ->get();

        $totalSimpanan = $simpanans->sum('nominal');
        $totalPinjaman = $pinjamans->sum('nominal');
        $totalAngsuranDibayar = $pinjamans->flatMap->angsurans
            ->where('status', 'lunas')
            ->sum('nominal_bayar');

        return view('laporan.rekening-koran', compact(
            'anggota',
            'dateFrom',
            'dateTo',
            'simpanans',
            'pinjamans',
            'totalSimpanan',
            'totalPinjaman',
            'totalAngsuranDibayar'
        ));
    }
}
