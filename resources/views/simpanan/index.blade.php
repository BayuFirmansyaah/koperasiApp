<x-app-layout>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Data Simpanan
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    @can('create-simpanan')
                    <a href="{{ route('simpanan.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Tambah Simpanan
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

            <div class="card">
                <div class="card-header">
                    <form action="{{ route('simpanan.index') }}" method="GET" class="d-flex gap-2 w-100">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari no. anggota atau nama..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
                            </button>
                        </div>
                        <select name="jenis_simpanan_id" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisSimpanans as $jenis)
                            <option value="{{ $jenis->id }}" {{ request('jenis_simpanan_id') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_simpanan }}</option>
                            @endforeach
                        </select>
                        <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Anggota</th>
                                <th>Nama Anggota</th>
                                <th>Jenis Simpanan</th>
                                <th>Nominal</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th class="w-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($simpanans as $simpanan)
                            <tr>
                                <td>{{ formatDate($simpanan->tanggal_transaksi) }}</td>
                                <td>{{ $simpanan->anggota->no_anggota }}</td>
                                <td>{{ $simpanan->anggota->user->name }}</td>
                                <td>{{ $simpanan->jenisSimpanan->nama_simpanan }}</td>
                                <td class="text-end"><strong>Rp {{ number_format($simpanan->nominal, 0, ',', '.') }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $simpanan->metode_pembayaran == 'tunai' ? 'info' : 'primary' }}">
                                        {{ ucfirst($simpanan->metode_pembayaran) }}
                                    </span>
                                </td>
                                <td>
                                    @if($simpanan->status == 'verified')
                                    <span class="badge bg-success">Verified</span>
                                    @elseif($simpanan->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @else
                                    <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('simpanan.show', $simpanan) }}" class="btn btn-sm btn-icon btn-ghost-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" /></svg>
                                    <div>Tidak ada data simpanan</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($simpanans->hasPages())
                <div class="card-footer">
                    {{ $simpanans->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
