<?php

namespace App\Http\Controllers\Simpanan;

use App\Http\Controllers\Controller;
use App\Models\Simpanan;
use App\Models\JenisSimpanan;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SimpananController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-simpanan|view-own-simpanan', only: ['index', 'show']),
            new Middleware('permission:create-simpanan', only: ['create', 'store']),
            new Middleware('permission:verify-simpanan', only: ['verify', 'doVerify']),
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Simpanan::with(['anggota.user', 'jenisSimpanan', 'verifiedBy']);

        // Jika anggota, hanya tampilkan simpanan sendiri
        if ($user->hasRole('anggota')) {
            $query->where('anggota_id', $user->anggota->id);
        }

        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_simpanan_id')) {
            $query->where('jenis_simpanan_id', $request->jenis_simpanan_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('anggota', function($q) use ($search) {
                $q->where('no_anggota', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $simpanans = $query->latest('tanggal_simpanan')->paginate(15);
        $jenisSimpanans = JenisSimpanan::all();

        return view('simpanan.index', compact('simpanans', 'jenisSimpanans'));
    }

    public function create()
    {
        $jenisSimpanans = JenisSimpanan::all();
        $anggotas = Anggota::with('user')->where('status', 'active')->get();
        
        return view('simpanan.create', compact('jenisSimpanans', 'anggotas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'jenis_simpanan_id' => 'required|exists:jenis_simpanans,id',
            'nominal' => 'required|numeric|min:1000',
            'tanggal_simpanan' => 'required|date',
            'metode_pembayaran' => 'required|in:tunai,transfer',
            'bukti_transfer' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['status'] = 'pending';

        if ($request->hasFile('bukti_transfer')) {
            $data['bukti_transfer'] = $request->file('bukti_transfer')->store('simpanan', 'public');
        }

        Simpanan::create($data);

        return redirect()->route('simpanan.index')
            ->with('success', 'Transaksi simpanan berhasil dicatat. Menunggu verifikasi bendahara.');
    }

    public function show(Simpanan $simpanan)
    {
        $this->authorize('view', $simpanan);
        
        $simpanan->load(['anggota.user', 'jenisSimpanan', 'verifiedBy']);
        
        return view('simpanan.show', compact('simpanan'));
    }

    public function verify(Request $request)
    {
        $simpanans = Simpanan::with(['anggota.user', 'jenisSimpanan'])
            ->where('status', 'pending')
            ->latest('tanggal_simpanan')
            ->paginate(15);

        return view('simpanan.verify', compact('simpanans'));
    }

    public function doVerify(Request $request, Simpanan $simpanan)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'keterangan_verifikasi' => 'nullable|string'
        ]);

        if ($request->action === 'approve') {
            $simpanan->update([
                'status' => 'verified',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'keterangan_verifikasi' => $request->keterangan_verifikasi
            ]);

            return back()->with('success', 'Simpanan berhasil diverifikasi.');
        } else {
            $simpanan->update([
                'status' => 'rejected',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'keterangan_verifikasi' => $request->keterangan_verifikasi
            ]);

            return back()->with('success', 'Simpanan ditolak.');
        }
    }
}
