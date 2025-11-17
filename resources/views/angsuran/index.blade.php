<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Manajemen Angsuran
        </h2>
    </x-slot>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Angsuran</h3>
                            <div class="card-actions">
                                @can('create-angsuran')
                                <a href="{{ route('angsuran.create') }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                    Bayar Angsuran
                                </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>No. Pinjaman</th>
                                            <th>Anggota</th>
                                            <th>Angsuran Ke-</th>
                                            <th>Jatuh Tempo</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th class="w-1">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($angsurans as $angsuran)
                                        <tr>
                                            <td>{{ $angsuran->pinjaman->no_pinjaman }}</td>
                                            <td>{{ $angsuran->pinjaman->anggota->user->name }}</td>
                                            <td>{{ $angsuran->angsuran_ke }}</td>
                                            <td>{{ formatDate($angsuran->tanggal_jatuh_tempo) }}</td>
                                            <td>Rp {{ number_format($angsuran->nominal, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $angsuran->status === 'lunas' ? 'green' : ($angsuran->status === 'pending' ? 'yellow' : 'red') }}">
                                                    {{ ucfirst($angsuran->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('angsuran.show', $angsuran) }}" class="btn btn-sm btn-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Belum ada data angsuran.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{ $angsurans->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
