<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Services\AnggotaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    protected $anggotaService;

    public function __construct(AnggotaService $anggotaService)
    {
        $this->anggotaService = $anggotaService;
    }

    public function index()
    {
        $pendingAnggotas = Anggota::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('pengurus.approval.index', compact('pendingAnggotas'));
    }

    public function approve(Anggota $anggota)
    {
        try {
            $this->anggotaService->approve($anggota, Auth::id());

            return back()->with('success', 'Anggota berhasil disetujui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui anggota: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Anggota $anggota)
    {
        $request->validate([
            'keterangan' => 'required|string'
        ]);

        try {
            $this->anggotaService->reject($anggota, $request->keterangan);

            return back()->with('success', 'Anggota berhasil ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak anggota: ' . $e->getMessage());
        }
    }
}



