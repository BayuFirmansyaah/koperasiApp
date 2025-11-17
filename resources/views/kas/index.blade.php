<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Manajemen Kas
        </h2>
    </x-slot>

    <div class="page-body">
        <div class="container-xl">
            <!-- Saldo Kas -->
            <div class="row row-deck row-cards mb-3">
                <div class="col-12">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h3 class="card-title text-white">Saldo Kas Saat Ini</h3>
                            <div class="display-4 fw-bold">
                                Rp {{ number_format($saldoTerakhir->saldo_sesudah ?? 0, 0, ',', '.') }}
                            </div>
                            <small class="text-white-50">
                                Update terakhir: {{ $saldoTerakhir ? formatDateTimeLong($saldoTerakhir->tanggal_transaksi) : '-' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaksi Kas -->
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Transaksi Kas</h3>
                            <div class="card-actions">
                                @can('create-kas')
                                <a href="{{ route('kas.create') }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                    Tambah Transaksi
                                </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Tipe</th>
                                            <th>Kategori</th>
                                            <th>Keterangan</th>
                                            <th>Nominal</th>
                                            <th>Saldo</th>
                                            <th class="w-1">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kasTransactions as $kas)
                                        <tr>
                                            <td>{{ formatDate($kas->tanggal_transaksi) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $kas->tipe === 'masuk' ? 'green' : 'red' }}">
                                                    {{ ucfirst($kas->tipe) }}
                                                </span>
                                            </td>
                                            <td>{{ $kas->kategori }}</td>
                                            <td>{{ Str::limit($kas->keterangan, 50) }}</td>
                                            <td class="{{ $kas->tipe === 'masuk' ? 'text-green' : 'text-red' }}">
                                                {{ $kas->tipe === 'masuk' ? '+' : '-' }} Rp {{ number_format($kas->nominal, 0, ',', '.') }}
                                            </td>
                                            <td>Rp {{ number_format($kas->saldo_sesudah, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('kas.show', $kas) }}" class="btn btn-sm btn-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Belum ada transaksi kas.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{ $kasTransactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
