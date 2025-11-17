<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Data Pinjaman</h2>
            @can('create-pinjaman')
            <a href="{{ route('pinjaman.create') }}" class="btn btn-primary">Ajukan Pinjaman</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-lg">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <form action="{{ route('pinjaman.index') }}" method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Cari nomor pinjaman..." value="{{ request('search') }}">
                        <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved_pengurus" {{ request('status') == 'approved_pengurus' ? 'selected' : '' }}>Approved Pengurus</option>
                            <option value="approved_bendahara" {{ request('status') == 'approved_bendahara' ? 'selected' : '' }}>Siap Dicairkan</option>
                            <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="rejected_pengurus" {{ request('status') == 'rejected_pengurus' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <button type="submit" class="btn btn-outline-secondary">Cari</button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Pinjaman</th>
                                <th>Tanggal</th>
                                <th>Nama Anggota</th>
                                <th class="text-end">Nominal</th>
                                <th>Status</th>
                                <th class="w-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pinjamans as $pinjaman)
                            <tr>
                                <td><strong>{{ $pinjaman->no_pinjaman }}</strong></td>
                                <td>{{ $pinjaman->tanggal_pengajuan->format('d/m/Y') }}</td>
                                <td>{{ $pinjaman->anggota->user->name }}</td>
                                <td class="text-end">Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</td>
                                <td>
                                    @if($pinjaman->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif($pinjaman->status == 'approved_pengurus')
                                    <span class="badge bg-info">Approved Pengurus</span>
                                    @elseif($pinjaman->status == 'approved_bendahara')
                                    <span class="badge bg-primary">Siap Dicairkan</span>
                                    @elseif($pinjaman->status == 'berjalan')
                                    <span class="badge bg-success">Berjalan</span>
                                    @elseif($pinjaman->status == 'lunas')
                                    <span class="badge bg-secondary">Lunas</span>
                                    @else
                                    <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('pinjaman.show', $pinjaman) }}" class="btn btn-sm btn-outline-secondary">Lihat</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data pinjaman</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pinjamans->hasPages())
                <div class="card-footer">
                    {{ $pinjamans->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
