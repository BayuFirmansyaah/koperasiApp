<?php

namespace App\Http\Controllers\Kas;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KasController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-kas', only: ['index', 'show']),
            new Middleware('permission:create-kas', only: ['create', 'store']),
        ];
    }

    public function index(Request $request)
    {
        $query = Kas::with(['transactable', 'createdBy']);

        // Filter
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $kasTransactions = $query->latest('tanggal_transaksi')->paginate(20);
        $saldoTerakhir = Kas::latest('tanggal_transaksi')->first();

        return view('kas.index', compact('kasTransactions', 'saldoTerakhir'));
    }

    public function create()
    {
        return view('kas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:masuk,keluar',
            'kategori' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'required|string',
            'bukti_transaksi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        // Get saldo terakhir
        $lastKas = Kas::latest('tanggal_transaksi')->first();
        $saldoSebelum = $lastKas ? $lastKas->saldo_sesudah : 0;

        // Hitung saldo sesudah
        $saldoSesudah = $request->tipe === 'masuk' 
            ? $saldoSebelum + $request->nominal 
            : $saldoSebelum - $request->nominal;

        $data = [
            'tipe' => $request->tipe,
            'kategori' => $request->kategori,
            'nominal' => $request->nominal,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'keterangan' => $request->keterangan,
            'saldo_sebelum' => $saldoSebelum,
            'saldo_sesudah' => $saldoSesudah,
            'user_id' => auth()->id()
        ];

        if ($request->hasFile('bukti_transaksi')) {
            $data['bukti_transaksi'] = $request->file('bukti_transaksi')->store('kas', 'public');
        }

        Kas::create($data);

        return redirect()->route('kas.index')
            ->with('success', 'Transaksi kas berhasil dicatat.');
    }

    public function show(Kas $ka)
    {
        $ka->load(['transactable', 'createdBy']);
        
        return view('kas.show', compact('ka'));
    }

    public function laporan(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from'
        ]);

        $kasTransactions = Kas::whereBetween('tanggal_transaksi', [$request->date_from, $request->date_to])
            ->orderBy('tanggal_transaksi')
            ->get();

        $totalMasuk = $kasTransactions->where('tipe', 'masuk')->sum('nominal');
        $totalKeluar = $kasTransactions->where('tipe', 'keluar')->sum('nominal');
        $saldoAwal = Kas::whereDate('tanggal_transaksi', '<', $request->date_from)
            ->latest('tanggal_transaksi')
            ->first();
        $saldoAkhir = Kas::whereDate('tanggal_transaksi', '<=', $request->date_to)
            ->latest('tanggal_transaksi')
            ->first();

        return view('kas.laporan', compact(
            'kasTransactions', 
            'totalMasuk', 
            'totalKeluar', 
            'saldoAwal', 
            'saldoAkhir',
            'request'
        ));
    }
}
