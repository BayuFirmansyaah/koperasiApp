<x-app-layout>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Data Anggota
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    @can('create-anggota')
                    <a href="{{ route('anggota.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Tambah Anggota
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('anggota.index') }}" method="GET" class="d-flex gap-2 w-100">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari NIK, nama, email, telepon..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
                            </button>
                        </div>
                        <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>No. Anggota</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Status</th>
                                <th>Tanggal Daftar</th>
                                <th class="w-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggotas as $anggota)
                            <tr>
                                <td>{{ $anggota->no_anggota ?? '-' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($anggota->foto)
                                        <span class="avatar me-2" style="background-image: url({{ Storage::url($anggota->foto) }})"></span>
                                        @else
                                        <span class="avatar me-2">{{ substr($anggota->user->name, 0, 2) }}</span>
                                        @endif
                                        <div>
                                            <div>{{ $anggota->user->name }}</div>
                                            <div class="text-muted">{{ $anggota->pekerjaan }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $anggota->nik }}</td>
                                <td>{{ $anggota->user->email }}</td>
                                <td>{{ $anggota->no_telepon }}</td>
                                <td>
                                    @if($anggota->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                    @elseif($anggota->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif($anggota->status == 'inactive')
                                    <span class="badge bg-secondary">Inactive</span>
                                    @else
                                    <span class="badge bg-danger">Suspended</span>
                                    @endif
                                </td>
                                <td>{{ $anggota->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        @can('view-anggota')
                                        <a href="{{ route('anggota.show', $anggota) }}" class="btn btn-sm btn-icon btn-ghost-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                                        </a>
                                        @endcan
                                        @can('update-anggota')
                                        <a href="{{ route('anggota.edit', $anggota) }}" class="btn btn-sm btn-icon btn-ghost-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                        </a>
                                        @endcan
                                        @can('delete-anggota')
                                        <form action="{{ route('anggota.destroy', $anggota) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-ghost-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" /></svg>
                                    <div>Tidak ada data anggota</div>
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
