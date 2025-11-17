<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Data Anggota</h2>
            @can('create-anggota')
            <a href="{{ route('anggota.create') }}" class="btn btn-primary">Tambah Anggota</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-xl">
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ route('anggota.index') }}" method="GET" class="row g-2">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama, NIK, email..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Non-aktif</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-outline-secondary w-100">Cari</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Anggota</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Tanggal Daftar</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggotas as $anggota)
                            <tr>
                                <td class="fw-bold">{{ $anggota->no_anggota ?? '-' }}</td>
                                <td>{{ $anggota->user->name }}</td>
                                <td>{{ $anggota->user->email }}</td>
                                <td>
                                    @if($anggota->status == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                    @elseif($anggota->status == 'menunggu')
                                    <span class="badge bg-warning">Menunggu</span>
                                    @elseif($anggota->status == 'non-aktif')
                                    <span class="badge bg-secondary">Non-aktif</span>
                                    @else
                                    <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $anggota->created_at->format('d/m/Y') }}</td>
                                <td class="text-end">
                                    @can('view-anggota')
                                    <a href="{{ route('anggota.show', $anggota) }}" class="btn btn-sm btn-outline-secondary">Lihat</a>
                                    @endcan
                                    @can('update-anggota')
                                    <a href="{{ route('anggota.edit', $anggota) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    @endcan
                                    @can('delete-anggota')
                                    <form action="{{ route('anggota.destroy', $anggota) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Tidak ada data anggota
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($anggotas->hasPages())
                <div class="card-footer">
                    {{ $anggotas->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
