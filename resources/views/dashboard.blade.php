<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-5" style="color: #1e293b;">
            Dashboard Koperasi
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container-xl">
            <!-- Alerts -->
            @if($angsuranTerlambat > 0 || $pinjamanPending > 0 || $simpananPending > 0)
            <div class="row mb-4">
                <div class="col-12">
                    @if($angsuranTerlambat > 0)
                    <div class="alert alert-warning mb-2">
                        <strong>Perhatian!</strong> {{ $angsuranTerlambat }} angsuran terlambat memerlukan penagihan.
                    </div>
                    @endif
                    
                    @if($pinjamanPending > 0)
                    <div class="alert alert-info mb-2">
                        <strong>Info:</strong> {{ $pinjamanPending }} permohonan pinjaman menunggu persetujuan.
                    </div>
                    @endif
                    
                    @if($simpananPending > 0)
                    <div class="alert alert-info mb-2">
                        <strong>Info:</strong> {{ $simpananPending }} simpanan menunggu verifikasi.
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Main Stats -->
            <div class="row mb-4">
                @can('view-anggota')
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Total Anggota</h6>
                            <h3 class="mb-1">{{ $totalAnggota ?? 0 }}</h3>
                            @if($pertumbuhanAnggota != 0)
                            <small class="@if($pertumbuhanAnggota > 0) text-success @else text-danger @endif">
                                {{ $pertumbuhanAnggota > 0 ? '+' : '' }}{{ $pertumbuhanAnggota }}% dari bulan lalu
                            </small>
                            @endif
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-simpanan')
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Total Simpanan</h6>
                            <h3 class="mb-1">Rp {{ number_format($totalSimpanan ?? 0, 0, ',', '.') }}</h3>
                            <small class="text-muted">{{ $transaksiSimpananBulanIni ?? 0 }} transaksi bulan ini</small>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-pinjaman')
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Pinjaman Aktif</h6>
                            <h3 class="mb-1">{{ $pinjamanBerjalan ?? 0 }}</h3>
                            <small class="text-muted">Rp {{ number_format($totalNominalPinjaman ?? 0, 0, ',', '.') }}</small>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-kas')
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Saldo Kas</h6>
                            <h3 class="mb-1">Rp {{ number_format($saldoKas ?? 0, 0, ',', '.') }}</h3>
                            <small class="text-muted">Saldo terkini</small>
                        </div>
                    </div>
                </div>
                @endcan
            </div>

            <!-- Status Overview -->
            <div class="row mb-4">
                @can('view-anggota')
                <div class="col-lg-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Status Anggota</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 col-md-3">
                                    <div class="p-2">
                                        <h5 class="mb-1">{{ $anggotaAktif ?? 0 }}</h5>
                                        <small class="text-muted">Aktif</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2">
                                        <h5 class="mb-1">{{ $anggotaMenunggu ?? 0 }}</h5>
                                        <small class="text-muted">Menunggu</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2">
                                        <h5 class="mb-1">{{ $anggotaDitolak ?? 0 }}</h5>
                                        <small class="text-muted">Ditolak</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2">
                                        <h5 class="mb-1">{{ $anggotaNonaktif ?? 0 }}</h5>
                                        <small class="text-muted">Non-aktif</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-pinjaman')
                <div class="col-lg-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Status Pinjaman</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 col-md-3">
                                    <div class="p-2">
                                        <h5 class="mb-1">{{ $pinjamanMenunggu ?? 0 }}</h5>
                                        <small class="text-muted">Menunggu</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2">
                                        <h5 class="mb-1">{{ $pinjamanBerjalan ?? 0 }}</h5>
                                        <small class="text-muted">Berjalan</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2">
                                        <h5 class="mb-1">{{ $pinjamanLunas ?? 0 }}</h5>
                                        <small class="text-muted">Lunas</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-2">
                                        <h5 class="mb-1">{{ $pinjamanBermasalah ?? 0 }}</h5>
                                        <small class="text-muted">Bermasalah</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
            </div>

            <!-- Financial Summary -->
            <div class="row mb-4">
                @can('view-kas')
                <div class="col-lg-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Aliran Kas Bulan Ini</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Pemasukan:</span>
                                    <strong>Rp {{ number_format($pemasukanBulanIni ?? 0, 0, ',', '.') }}</strong>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Pengeluaran:</span>
                                    <strong>Rp {{ number_format($pengeluaranBulanIni ?? 0, 0, ',', '.') }}</strong>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-danger" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="p-2 bg-light rounded">
                                <div class="d-flex justify-content-between">
                                    <span class="small">Selisih:</span>
                                    <strong>Rp {{ number_format(($pemasukanBulanIni - $pengeluaranBulanIni) ?? 0, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-angsuran')
                <div class="col-lg-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Informasi Penting</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <span>Tingkat Pembayaran Angsuran:</span>
                                        <strong>{{ $tingkatPembayaran ?? 0 }}%</strong>
                                    </div>
                                </div>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <span>Sisa Pinjaman Beredar:</span>
                                        <strong>Rp {{ number_format($sisaPinjaman ?? 0, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <span>Pinjaman Lunas:</span>
                                        <strong>{{ $pinjamanLunas ?? 0 }}</strong>
                                    </div>
                                </div>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <span>Simpanan Pending:</span>
                                        <strong>{{ $simpananPending ?? 0 }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
            </div>

            <!-- Action Buttons -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="btn-group w-100" role="group">
                        @can('create-anggota')
                        <a href="{{ route('anggota.create') }}" class="btn btn-outline-primary">
                            Tambah Anggota
                        </a>
                        @endcan

                        @can('create-pinjaman')
                        <a href="{{ route('pinjaman.create') }}" class="btn btn-outline-success">
                            Ajukan Pinjaman
                        </a>
                        @endcan

                        @can('create-simpanan')
                        <a href="{{ route('simpanan.create') }}" class="btn btn-outline-info">
                            Tambah Simpanan
                        </a>
                        @endcan

                        @can('view-laporan-global')
                        <a href="{{ route('laporan.keuangan') }}" class="btn btn-outline-warning">
                            Laporan Keuangan
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Recent Data Tables -->
            @if($topPeminjam->count() > 0 || $transaksiTerbaru->count() > 0)
            <div class="row">
                @if($topPeminjam->count() > 0)
                <div class="col-lg-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Top Peminjam</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topPeminjam as $item)
                                    <tr>
                                        <td>{{ $item->anggota->user->name ?? 'N/A' }}</td>
                                        <td class="text-end">Rp {{ number_format($item->nominal_total, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                @if($transaksiTerbaru->count() > 0)
                <div class="col-lg-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Transaksi Terakhir</h6>
                        </div>
                        <div class="card-body">
                            @foreach($transaksiTerbaru->take(5) as $transaksi)
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <div>
                                    <div class="small">{{ ucfirst($transaksi->kategori) }}</div>
                                    <small class="text-muted">{{ $transaksi->tanggal_transaksi->format('d M Y') }}</small>
                                </div>
                                <div class="@if($transaksi->jenis == 'masuk') text-success @else text-danger @endif">
                                    Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
