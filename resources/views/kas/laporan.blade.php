<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Laporan Kas
        </h2>
    </x-slot>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Transaksi Kas</h3>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" class="form-control" name="date_from" value="{{ $request->date_from }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" class="form-control" name="date_to" value="{{ $request->date_to }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                            </div>
                        </div>
                    </form>

                    @if(isset($kasTransactions))
                    <div class="row row-cards mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h3 class="card-title text-white">Total Kas Masuk</h3>
                                    <div class="h2">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h3 class="card-title text-white">Total Kas Keluar</h3>
                                    <div class="h2">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h3 class="card-title text-white">Saldo Akhir</h3>
                                    <div class="h2">Rp {{ number_format($saldoAkhir->saldo_sesudah ?? 0, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Tipe</th>
                                    <th>Kategori</th>
                                    <th>Keterangan</th>
                                    <th class="text-end">Kas Masuk</th>
                                    <th class="text-end">Kas Keluar</th>
                                    <th class="text-end">Saldo</th>
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
                                    <td>{{ $kas->keterangan }}</td>
                                    <td class="text-end">{{ $kas->tipe === 'masuk' ? 'Rp ' . number_format($kas->nominal, 0, ',', '.') : '-' }}</td>
                                    <td class="text-end">{{ $kas->tipe === 'keluar' ? 'Rp ' . number_format($kas->nominal, 0, ',', '.') : '-' }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($kas->saldo_sesudah, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada transaksi pada periode ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
