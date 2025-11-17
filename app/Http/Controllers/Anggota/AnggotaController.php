<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnggotaRequest;
use App\Models\Anggota;
use App\Services\AnggotaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AnggotaController extends Controller implements HasMiddleware
{
    protected $anggotaService;

    public function __construct(AnggotaService $anggotaService)
    {
        $this->anggotaService = $anggotaService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-anggota', only: ['index', 'show']),
            new Middleware('permission:create-anggota', only: ['create', 'store']),
            new Middleware('permission:update-anggota', only: ['edit', 'update']),
            new Middleware('permission:delete-anggota', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Anggota::with(['user', 'approvedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_anggota', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $anggotas = $query->latest()->paginate(10);

        return view('anggota.index', compact('anggotas'));
    }

    public function create()
    {
        return view('anggota.create');
    }

    public function store(AnggotaRequest $request)
    {
        try {
            $data = $request->validated();

            // Handle foto upload
            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('anggota', 'public');
            }

            $anggota = $this->anggotaService->create($data);

            return redirect()->route('anggota.index')
                ->with('success', 'Anggota berhasil didaftarkan. Menunggu persetujuan pengurus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendaftarkan anggota: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Anggota $anggota)
    {
        $anggota->load(['user', 'simpanans', 'pinjamans']);
        
        return view('anggota.show', compact('anggota'));
    }

    public function edit(Anggota $anggota)
    {
        return view('anggota.edit', compact('anggota'));
    }

    public function update(AnggotaRequest $request, Anggota $anggota)
    {
        try {
            $data = $request->validated();

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Delete old photo
                if ($anggota->foto) {
                    Storage::disk('public')->delete($anggota->foto);
                }
                $data['foto'] = $request->file('foto')->store('anggota', 'public');
            }

            $this->anggotaService->update($anggota, $data);

            return redirect()->route('anggota.index')
                ->with('success', 'Data anggota berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data anggota: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Anggota $anggota)
    {
        try {
            // Delete photo
            if ($anggota->foto) {
                Storage::disk('public')->delete($anggota->foto);
            }

            // Delete user account
            $anggota->user->delete();
            
            // Anggota will be deleted via cascade

            return redirect()->route('anggota.index')
                ->with('success', 'Anggota berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus anggota: ' . $e->getMessage());
        }
    }
}
