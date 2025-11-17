<?php

namespace App\Http\Controllers\Angsuran;

use App\Http\Controllers\Controller;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AngsuranController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-angsuran|view-own-angsuran', only: ['index', 'show']),
            new Middleware('permission:create-angsuran', only: ['create', 'store']),
            new Middleware('permission:verify-angsuran', only: ['verify', 'doVerify']),
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Angsuran::with(['pinjaman.anggota.user', 'verifiedBy']);

        // Jika anggota, hanya tampilkan angsuran sendiri
        if ($user->hasRole('anggota')) {
            $query->whereHas('pinjaman', function($q) use ($user) {
                $q->where('anggota_id', $user->anggota->id);
            });
        }

        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pinjaman', function($q) use ($search) {
                $q->where('no_pinjaman', 'like', "%{$search}%")
                  ->orWhereHas('anggota', function($q) use ($search) {
                      $q->where('no_anggota', 'like', "%{$search}%")
                        ->orWhereHas('user', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                  });
            });
        }

        $angsurans = $query->latest('tanggal_jatuh_tempo')->paginate(15);

        return view('angsuran.index', compact('angsurans'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Get pinjaman aktif untuk anggota
        if ($user->hasRole('anggota')) {
            $pinjamans = $user->anggota->pinjamans()
                ->where('status', 'berjalan')
                ->get();
        } else {
            $pinjamans = Pinjaman::where('status', 'berjalan')->get();
        }

        return view('angsuran.create', compact('pinjamans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pinjaman_id' => 'required|exists:pinjamans,id',
            'nominal' => 'required|numeric|min:1000',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|in:tunai,transfer',
            'bukti_transfer' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        $pinjaman = Pinjaman::findOrFail($request->pinjaman_id);

        // Get angsuran terdekat yang belum dibayar
        $angsuranTerdekat = $pinjaman->angsurans()
            ->where('status', 'belum_bayar')
            ->orderBy('tanggal_jatuh_tempo')
            ->first();

        if (!$angsuranTerdekat) {
            return back()->with('error', 'Tidak ada angsuran yang perlu dibayar.');
        }

        $data = [
            'nominal_bayar' => $request->nominal,
            'tanggal_bayar' => $request->tanggal_bayar,
            'metode_pembayaran' => $request->metode_pembayaran,
            'keterangan' => $request->keterangan,
            'status' => 'pending'
        ];

        if ($request->hasFile('bukti_transfer')) {
            $data['bukti_transfer'] = $request->file('bukti_transfer')->store('angsuran', 'public');
        }

        $angsuranTerdekat->update($data);

        return redirect()->route('angsuran.index')
            ->with('success', 'Pembayaran angsuran berhasil dicatat. Menunggu verifikasi bendahara.');
    }

    public function show(Angsuran $angsuran)
    {
        $this->authorize('view', $angsuran);
        
        $angsuran->load(['pinjaman.anggota.user', 'verifiedBy']);
        
        return view('angsuran.show', compact('angsuran'));
    }

    public function verify(Request $request)
    {
        $angsurans = Angsuran::with(['pinjaman.anggota.user'])
            ->where('status', 'pending')
            ->latest('tanggal_bayar')
            ->paginate(15);

        return view('angsuran.verify', compact('angsurans'));
    }

    public function doVerify(Request $request, Angsuran $angsuran)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'keterangan_verifikasi' => 'nullable|string'
        ]);

        if ($request->action === 'approve') {
            $angsuran->update([
                'status' => 'lunas',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'keterangan_verifikasi' => $request->keterangan_verifikasi
            ]);

            // TODO: Update sisa pinjaman
            // TODO: Catat ke kas

            return back()->with('success', 'Angsuran berhasil diverifikasi.');
        } else {
            $angsuran->update([
                'status' => 'belum_bayar',
                'nominal_bayar' => null,
                'tanggal_bayar' => null,
                'bukti_transfer' => null,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'keterangan_verifikasi' => $request->keterangan_verifikasi
            ]);

            return back()->with('success', 'Angsuran ditolak.');
        }
    }
}
