<x-app-layout>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Data Pinjaman
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    @can('create-pinjaman')
                    <a href="{{ route('pinjaman.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Ajukan Pinjaman
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                    </div>
                    <div>{{ session('success') }}</div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
                    </div>
                    <div>{{ session('error') }}</div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <form action="{{ route('pinjaman.index') }}" method="GET" class="d-flex gap-2 w-100">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari no. pinjaman, no. anggota, atau nama..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
                            </button>
                        </div>
                        <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved_pengurus" {{ request('status') == 'approved_pengurus' ? 'selected' : '' }}>Approved Pengurus</option>
                            <option value="approved_bendahara" {{ request('status') == 'approved_bendahara' ? 'selected' : '' }}>Approved Bendahara</option>
                            <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="rejected_pengurus" {{ request('status') == 'rejected_pengurus' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>No. Pinjaman</th>
                                <th>Tanggal Pengajuan</th>
                                <th>No. Anggota</th>
                                <th>Nama Anggota</th>
                                <th>Nominal</th>
                                <th>Tenor</th>
                                <th>Status</th>
                                <th class="w-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pinjamans as $pinjaman)
                            <tr>
                                <td><strong>{{ $pinjaman->no_pinjaman }}</strong></td>
                                <td>{{ $pinjaman->tanggal_pengajuan->format('d/m/Y') }}</td>
                                <td>{{ $pinjaman->anggota->no_anggota }}</td>
                                <td>{{ $pinjaman->anggota->user->name }}</td>
                                <td class="text-end"><strong>Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</strong></td>
                                <td>{{ $pinjaman->lama_pinjaman }} bulan</td>
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
                                    <a href="{{ route('pinjaman.show', $pinjaman) }}" class="btn btn-sm btn-icon btn-ghost-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" /></svg>
                                    <div>Tidak ada data pinjaman</div>
                                </td>
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
