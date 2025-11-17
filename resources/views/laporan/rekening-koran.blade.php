<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Rekening Koran
        </h2>
    </x-slot>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rekening Koran - {{ $anggota->user->name }}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <strong>No. Anggota:</strong> {{ $anggota->no_anggota }}
                            </div>
                            <div class="mb-2">
                                <strong>Nama:</strong> {{ $anggota->user->name }}
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong> {{ $anggota->user->email }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" class="row">
                                <div class="col-md-5">
                                    <label class="form-label">Dari</label>
                                    <input type="date" class="form-control" name="date_from" value="{{ $dateFrom }}">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Sampai</label>
                                    <input type="date" class="form-control" name="date_to" value="{{ $dateTo }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row row-cards mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h4 class="card-title text-white">Total Simpanan</h4>
                                    <div class="h3">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h4 class="card-title text-white">Total Pinjaman</h4>
                                    <div class="h3">Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-blue text-white">
                                <div class="card-body">
                                    <h4 class="card-title text-white">Angsuran Dibayar</h4>
                                    <div class="h3">Rp {{ number_format($totalAngsuranDibayar, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="mb-3">Riwayat Simpanan</h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis Simpanan</th>
                                    <th class="text-end">Nominal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($simpanans as $simpanan)
                                <tr>
                                    <td>{{ formatDate($simpanan->tanggal_transaksi) }}</td>
                                    <td>{{ $simpanan->jenisSimpanan->nama }}</td>
                                    <td class="text-end">Rp {{ number_format($simpanan->nominal, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-green">{{ ucfirst($simpanan->status) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data simpanan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h4 class="mb-3">Riwayat Pinjaman</h4>
                    @forelse($pinjamans as $pinjaman)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">{{ $pinjaman->no_pinjaman }}</h5>
                            <span class="badge bg-{{ $pinjaman->status === 'lunas' ? 'green' : 'blue' }}">
                                {{ ucfirst($pinjaman->status) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Nominal Pinjaman:</strong> Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Tanggal Pencairan:</strong> {{ formatDate($pinjaman->tanggal_pencairan) }}
                                </div>
                            </div>

                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Angsuran Ke-</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Nominal</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pinjaman->angsurans as $angsuran)
                                    <tr>
                                        <td>{{ $angsuran->angsuran_ke }}</td>
                                        <td>{{ formatDate($angsuran->tanggal_jatuh_tempo) }}</td>
                                        <td>Rp {{ number_format($angsuran->nominal, 0, ',', '.') }}</td>
                                        <td>{{ formatDate($angsuran->tanggal_bayar) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $angsuran->status === 'lunas' ? 'green' : 'red' }}">
                                                {{ ucfirst($angsuran->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">Tidak ada data pinjaman</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
