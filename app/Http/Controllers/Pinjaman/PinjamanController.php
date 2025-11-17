<?php

namespace App\Http\Controllers\Pinjaman;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PinjamanController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-pinjaman|view-own-pinjaman', only: ['index', 'show']),
            new Middleware('permission:create-pinjaman', only: ['create', 'store']),
            new Middleware('permission:review-pinjaman', only: ['review', 'doReview']),
            new Middleware('permission:approve-pinjaman', only: ['approve', 'doApprove']),
            new Middleware('permission:disburse-pinjaman', only: ['disburse', 'doDisburse']),
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Pinjaman::with(['anggota.user', 'reviewedBy', 'approvedBy']);

        // Jika anggota, hanya tampilkan pinjaman sendiri
        if ($user->hasRole('anggota')) {
            $query->where('anggota_id', $user->anggota->id);
        }

        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_pinjaman', 'like', "%{$search}%")
                  ->orWhereHas('anggota', function($q) use ($search) {
                      $q->where('no_anggota', 'like', "%{$search}%")
                        ->orWhereHas('user', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                  });
            });
        }

        $pinjamans = $query->latest('tanggal_pengajuan')->paginate(15);

        return view('pinjaman.index', compact('pinjamans'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Cek apakah user adalah anggota
        if (!$user->hasRole('anggota') || !$user->anggota) {
            return redirect()->route('dashboard')
                ->with('error', 'Hanya anggota yang dapat mengajukan pinjaman.');
        }

        // Cek apakah ada pinjaman aktif
        $activePinjaman = $user->anggota->pinjamans()
            ->whereIn('status', ['pending', 'approved_pengurus', 'approved_bendahara', 'berjalan'])
            ->exists();

        if ($activePinjaman) {
            return redirect()->route('pinjaman.index')
                ->with('error', 'Anda masih memiliki pinjaman aktif. Selesaikan terlebih dahulu sebelum mengajukan pinjaman baru.');
        }

        return view('pinjaman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:500000|max:50000000',
            'lama_pinjaman' => 'required|integer|min:3|max:36',
            'tujuan_pinjaman' => 'required|string',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $user = auth()->user();
        $anggota = $user->anggota;

        // Generate nomor pinjaman
        $year = date('Y');
        $month = date('m');
        $lastPinjaman = Pinjaman::whereYear('tanggal_pengajuan', $year)
            ->whereMonth('tanggal_pengajuan', $month)
            ->latest('no_pinjaman')
            ->first();

        $sequence = $lastPinjaman ? intval(substr($lastPinjaman->no_pinjaman, -4)) + 1 : 1;
        $noPinjaman = 'PJM' . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        $data = [
            'anggota_id' => $anggota->id,
            'no_pinjaman' => $noPinjaman,
            'tanggal_pengajuan' => now(),
            'nominal' => $request->nominal,
            'lama_pinjaman' => $request->lama_pinjaman,
            'tujuan_pinjaman' => $request->tujuan_pinjaman,
            'status' => 'pending'
        ];

        if ($request->hasFile('dokumen_pendukung')) {
            $data['dokumen_pendukung'] = $request->file('dokumen_pendukung')->store('pinjaman', 'public');
        }

        Pinjaman::create($data);

        return redirect()->route('pinjaman.index')
            ->with('success', 'Pengajuan pinjaman berhasil. Menunggu review dari pengurus.');
    }

    public function show(Pinjaman $pinjaman)
    {
        $this->authorize('view', $pinjaman);
        
        $pinjaman->load(['anggota.user', 'reviewedBy', 'approvedBy', 'disbursedBy', 'angsurans']);
        
        return view('pinjaman.show', compact('pinjaman'));
    }

    public function review(Request $request)
    {
        $pinjamans = Pinjaman::with(['anggota.user'])
            ->where('status', 'pending')
            ->latest('tanggal_pengajuan')
            ->paginate(15);

        return view('pinjaman.review', compact('pinjamans'));
    }

    public function doReview(Request $request, Pinjaman $pinjaman)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan_pengurus' => 'nullable|string'
        ]);

        if ($request->action === 'approve') {
            $pinjaman->update([
                'status' => 'approved_pengurus',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'catatan_pengurus' => $request->catatan_pengurus
            ]);

            return back()->with('success', 'Pinjaman disetujui. Menunggu persetujuan bendahara.');
        } else {
            $pinjaman->update([
                'status' => 'rejected_pengurus',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'catatan_pengurus' => $request->catatan_pengurus
            ]);

            return back()->with('success', 'Pinjaman ditolak.');
        }
    }

    public function approve(Request $request)
    {
        $pinjamans = Pinjaman::with(['anggota.user', 'reviewedBy'])
            ->where('status', 'approved_pengurus')
            ->latest('reviewed_at')
            ->paginate(15);

        return view('pinjaman.approve', compact('pinjamans'));
    }

    public function doApprove(Request $request, Pinjaman $pinjaman)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan_bendahara' => 'nullable|string'
        ]);

        if ($request->action === 'approve') {
            $pinjaman->update([
                'status' => 'approved_bendahara',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'catatan_bendahara' => $request->catatan_bendahara
            ]);

            return back()->with('success', 'Pinjaman disetujui. Siap untuk dicairkan.');
        } else {
            $pinjaman->update([
                'status' => 'rejected_bendahara',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'catatan_bendahara' => $request->catatan_bendahara
            ]);

            return back()->with('success', 'Pinjaman ditolak.');
        }
    }

    public function disburse(Request $request)
    {
        $pinjamans = Pinjaman::with(['anggota.user', 'reviewedBy', 'approvedBy'])
            ->where('status', 'approved_bendahara')
            ->latest('approved_at')
            ->paginate(15);

        return view('pinjaman.disburse', compact('pinjamans'));
    }

    public function doDisburse(Request $request, Pinjaman $pinjaman)
    {
        $request->validate([
            'tanggal_pencairan' => 'required|date',
            'metode_pencairan' => 'required|in:tunai,transfer',
            'nomor_rekening' => 'required_if:metode_pencairan,transfer',
            'catatan_pencairan' => 'nullable|string'
        ]);

        // TODO: Generate jadwal angsuran
        // TODO: Catat ke kas

        $pinjaman->update([
            'status' => 'berjalan',
            'tanggal_pencairan' => $request->tanggal_pencairan,
            'metode_pencairan' => $request->metode_pencairan,
            'nomor_rekening' => $request->nomor_rekening,
            'catatan_pencairan' => $request->catatan_pencairan,
            'disbursed_by' => auth()->id(),
            'sisa_pinjaman' => $pinjaman->nominal
        ]);

        return back()->with('success', 'Pinjaman berhasil dicairkan.');
    }
}
