<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-semibold fs-5" style="color: #1e293b;">
                    {{ __('Dashboard Koperasi') }}
                </h2>
                <p class="text-muted mt-1 mb-0" style="font-size: 0.875rem;">
                    Selamat datang! Berikut adalah ringkasan operasional koperasi Anda
                </p>
            </div>
            <div class="text-end">
                <div class="text-muted" style="font-size: 0.875rem;">
                    {{ now()->format('l, d F Y') }}
                </div>
                <div class="small text-muted">
                    {{ now()->format('H:i') }} WIB
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container-xl">
            <!-- Alert Section -->
            @if($angsuranTerlambat > 0 || $pinjamanPending > 0 || $simpananPending > 0)
            <div class="row mb-4">
                <div class="col-md-12">
                    @if($angsuranTerlambat > 0)
                    <div class="alert alert-warning border-0 d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>⚠ Angsuran Terlambat:</strong> Ada <strong class="text-danger">{{ $angsuranTerlambat }}</strong> angsuran yang telah melewati tanggal jatuh tempo. Segera lakukan penagihan.
                        </div>
                    </div>
                    @endif
                    
                    @if($pinjamanPending > 0)
                    <div class="alert alert-info border-0 d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill me-2" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>ℹ Pinjaman Tertunda:</strong> Ada <strong class="text-primary">{{ $pinjamanPending }}</strong> permohonan pinjaman yang menunggu persetujuan.
                        </div>
                    </div>
                    @endif
                    
                    @if($simpananPending > 0)
                    <div class="alert alert-info border-0 d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill me-2" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>ℹ Simpanan Menunggu Verifikasi:</strong> Ada <strong class="text-primary">{{ $simpananPending }}</strong> simpanan yang perlu diverifikasi.
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Key Metrics Row 1 - Overview Utama -->
            <div class="row mb-4">
                @can('view-anggota')
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-1" style="font-size: 0.875rem;">
                                        <i class="bi bi-people-fill"></i> Total Anggota Aktif
                                    </p>
                                    <h3 class="fw-bold mb-0" style="color: #206bc4;">{{ $totalAnggota ?? 0 }}</h3>
                                    @if($pertumbuhanAnggota > 0)
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up"></i> {{ $pertumbuhanAnggota }}% vs bulan lalu
                                    </small>
                                    @elseif($pertumbuhanAnggota < 0)
                                    <small class="text-danger">
                                        <i class="bi bi-arrow-down"></i> {{ abs($pertumbuhanAnggota) }}% vs bulan lalu
                                    </small>
                                    @endif
                                </div>
                                <div style="font-size: 2.5rem; opacity: 0.1;">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-simpanan')
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-1" style="font-size: 0.875rem;">
                                        <i class="bi bi-piggy-bank-fill"></i> Total Simpanan
                                    </p>
                                    <h3 class="fw-bold mb-0" style="color: #4299e1;">Rp {{ number_format($totalSimpanan ?? 0, 0, ',', '.') }}</h3>
                                    @if($pertumbuhanSimpanan > 0)
                                    <small class="text-success">
                                        <i class="bi bi-arrow-up"></i> Rp {{ number_format($simpananBulanIni, 0, ',', '.') }} bulan ini
                                    </small>
                                    @endif
                                </div>
                                <div style="font-size: 2.5rem; opacity: 0.1;">
                                    <i class="bi bi-piggy-bank-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-pinjaman')
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-1" style="font-size: 0.875rem;">
                                        <i class="bi bi-cash-coin"></i> Total Pinjaman Aktif
                                    </p>
                                    <h3 class="fw-bold mb-0" style="color: #2fb344;">{{ $pinjamanBerjalan ?? 0 }}</h3>
                                    <small class="text-muted">
                                        Rp {{ number_format($totalNominalPinjaman ?? 0, 0, ',', '.') }}
                                    </small>
                                </div>
                                <div style="font-size: 2.5rem; opacity: 0.1;">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-kas')
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-1" style="font-size: 0.875rem;">
                                        <i class="bi bi-vault-fill"></i> Saldo Kas Terkini
                                    </p>
                                    <h3 class="fw-bold mb-0" style="color: #f76707;">Rp {{ number_format($saldoKas ?? 0, 0, ',', '.') }}</h3>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        ↓ Rp {{ number_format($pengeluaranBulanIni ?? 0, 0, ',', '.') }} pengeluaran
                                    </small>
                                </div>
                                <div style="font-size: 2.5rem; opacity: 0.1;">
                                    <i class="bi bi-vault-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
            </div>

            <!-- Status Breakdown Row -->
            <div class="row mb-4">
                @can('view-anggota')
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-people-fill"></i> Distribusi Status Anggota
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="p-2 rounded" style="background: rgba(47, 179, 68, 0.1); border-left: 3px solid #2fb344;">
                                        <h5 class="fw-bold mb-1" style="color: #2fb344;">{{ $anggotaAktif ?? 0 }}</h5>
                                        <small class="text-muted d-block">Aktif</small>
                                        <small class="text-muted">{{ $totalAnggota > 0 ? round(($anggotaAktif/$totalAnggota)*100, 1) : 0 }}%</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-2 rounded" style="background: rgba(247, 103, 7, 0.1); border-left: 3px solid #f76707;">
                                        <h5 class="fw-bold mb-1" style="color: #f76707;">{{ $anggotaMenunggu ?? 0 }}</h5>
                                        <small class="text-muted d-block">Menunggu</small>
                                        <small class="text-muted">{{ $totalAnggota > 0 ? round(($anggotaMenunggu/($totalAnggota+$anggotaMenunggu+$anggotaDitolak+$anggotaNonaktif))*100, 1) : 0 }}%</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-2 rounded" style="background: rgba(214, 57, 57, 0.1); border-left: 3px solid #d63939;">
                                        <h5 class="fw-bold mb-1" style="color: #d63939;">{{ $anggotaDitolak ?? 0 }}</h5>
                                        <small class="text-muted d-block">Ditolak</small>
                                        <small class="text-muted">{{ ($totalAnggota+$anggotaMenunggu+$anggotaDitolak+$anggotaNonaktif) > 0 ? round(($anggotaDitolak/($totalAnggota+$anggotaMenunggu+$anggotaDitolak+$anggotaNonaktif))*100, 1) : 0 }}%</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-2 rounded" style="background: rgba(108, 117, 125, 0.1); border-left: 3px solid #6c757d;">
                                        <h5 class="fw-bold mb-1" style="color: #6c757d;">{{ $anggotaNonaktif ?? 0 }}</h5>
                                        <small class="text-muted d-block">Non-aktif</small>
                                        <small class="text-muted">{{ ($totalAnggota+$anggotaMenunggu+$anggotaDitolak+$anggotaNonaktif) > 0 ? round(($anggotaNonaktif/($totalAnggota+$anggotaMenunggu+$anggotaDitolak+$anggotaNonaktif))*100, 1) : 0 }}%</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-pinjaman')
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-cash-coin"></i> Distribusi Status Pinjaman
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="p-2 rounded" style="background: rgba(206, 188, 132, 0.1); border-left: 3px solid #cebc84;">
                                        <h5 class="fw-bold mb-1" style="color: #cebc84;">{{ $pinjamanMenunggu ?? 0 }}</h5>
                                        <small class="text-muted d-block">Menunggu</small>
                                        <small class="text-muted">review</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-2 rounded" style="background: rgba(47, 179, 68, 0.1); border-left: 3px solid #2fb344;">
                                        <h5 class="fw-bold mb-1" style="color: #2fb344;">{{ $pinjamanBerjalan ?? 0 }}</h5>
                                        <small class="text-muted d-block">Aktif</small>
                                        <small class="text-muted">berjalan</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-2 rounded" style="background: rgba(66, 153, 225, 0.1); border-left: 3px solid #4299e1;">
                                        <h5 class="fw-bold mb-1" style="color: #4299e1;">{{ $pinjamanLunas ?? 0 }}</h5>
                                        <small class="text-muted d-block">Lunas</small>
                                        <small class="text-muted">selesai</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-2 rounded" style="background: rgba(214, 57, 57, 0.1); border-left: 3px solid #d63939;">
                                        <h5 class="fw-bold mb-1" style="color: #d63939;">{{ $pinjamanBermasalah ?? 0 }}</h5>
                                        <small class="text-muted d-block">Bermasalah</small>
                                        <small class="text-muted">overdue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
            </div>

            <!-- Performance & Insights Row -->
            <div class="row mb-4">
                @can('view-angsuran')
                <div class="col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-graph-up"></i> Tingkat Pembayaran Angsuran
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div style="position: relative; width: 150px; height: 150px; margin: 0 auto;">
                                    <svg viewBox="0 0 36 36" class="circular-progress">
                                        <path d="M18 2.0845
                                            a 15.9155 15.9155 0 0 1 0 31.831
                                            a 15.9155 15.9155 0 0 1 0 -31.831"
                                            fill="none" stroke="#e9ecef" stroke-width="3"/>
                                        <path d="M18 2.0845
                                            a 15.9155 15.9155 0 0 1 0 31.831
                                            a 15.9155 15.9155 0 0 1 0 -31.831"
                                            fill="none" stroke="#4299e1" stroke-width="3"
                                            stroke-dasharray="{{ $tingkatPembayaran }}, 100"
                                            style="stroke-linecap: round; transform: rotate(-90deg); transform-origin: 50% 50%;"/>
                                    </svg>
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                        <h4 class="fw-bold mb-0" style="color: #4299e1;">{{ $tingkatPembayaran }}%</h4>
                                        <small class="text-muted">Terbayar</small>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted mb-0">
                                Dari total angsuran yang dijadwalkan, {{ $tingkatPembayaran }}% telah dibayarkan tepat waktu.
                            </p>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-kas')
                <div class="col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-wallet2"></i> Aliran Kas Bulan Ini
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Pemasukan:</span>
                                    <h5 class="fw-bold mb-0 text-success">Rp {{ number_format($pemasukanBulanIni ?? 0, 0, ',', '.') }}</h5>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Pengeluaran:</span>
                                    <h5 class="fw-bold mb-0 text-danger">Rp {{ number_format($pengeluaranBulanIni ?? 0, 0, ',', '.') }}</h5>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-danger" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="mt-3 p-2 rounded" style="background: rgba(66, 153, 225, 0.1);">
                                <small class="text-muted">Selisih Bulan Ini:</small>
                                <h6 class="fw-bold mb-0" style="color: #4299e1;">
                                    Rp {{ number_format(($pemasukanBulanIni - $pengeluaranBulanIni) ?? 0, 0, ',', '.') }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-anggota')
                <div class="col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-bar-chart-fill"></i> Summary Operasional
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Anggota Baru Bulan Ini:</span>
                                        <strong>{{ $totalAnggota ?? 0 }}</strong>
                                    </div>
                                </div>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Transaksi Simpanan:</span>
                                        <strong>{{ $transaksiSimpananBulanIni ?? 0 }}</strong>
                                    </div>
                                </div>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Simpanan Pending:</span>
                                        <strong class="text-warning">{{ $simpananPending ?? 0 }}</strong>
                                    </div>
                                </div>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Pinjaman Pending:</span>
                                        <strong class="text-info">{{ $pinjamanPending ?? 0 }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-lightning-charge"></i> Aksi Cepat & Manajemen
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                @can('create-anggota')
                                <div class="col-md-3 col-sm-6">
                                    <a href="{{ route('anggota.create') }}" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-person-plus"></i> Tambah Anggota
                                    </a>
                                </div>
                                @endcan

                                @can('create-pinjaman')
                                <div class="col-md-3 col-sm-6">
                                    <a href="{{ route('pinjaman.create') }}" class="btn btn-outline-success w-100">
                                        <i class="bi bi-cash-coin"></i> Ajukan Pinjaman
                                    </a>
                                </div>
                                @endcan

                                @can('create-simpanan')
                                <div class="col-md-3 col-sm-6">
                                    <a href="{{ route('simpanan.create') }}" class="btn btn-outline-info w-100">
                                        <i class="bi bi-piggy-bank"></i> Tambah Simpanan
                                    </a>
                                </div>
                                @endcan

                                @can('view-laporan-global')
                                <div class="col-md-3 col-sm-6">
                                    <a href="{{ route('laporan.index') }}" class="btn btn-outline-warning w-100">
                                        <i class="bi bi-file-earmark-pdf"></i> Laporan Keuangan
                                    </a>
                                </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Tables Row -->
            <div class="row">
                <!-- Top Peminjam -->
                @if($topPeminjam->count() > 0)
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-trophy-fill"></i> Top Peminjam
                            </h5>
                            <small class="text-muted">Anggota dengan total pinjaman tertinggi</small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr style="background: #f8f9fa;">
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th class="text-end">Total Pinjaman</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topPeminjam as $idx => $item)
                                        <tr>
                                            <td>{{ $idx + 1 }}</td>
                                            <td>{{ $item->anggota->user->name ?? 'N/A' }}</td>
                                            <td class="text-end">
                                                <span class="badge bg-success">Rp {{ number_format($item->nominal_total, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Recent Transactions -->
                @if($transaksiTerbaru->count() > 0)
                <div class="col-lg-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-clock-history"></i> Transaksi Terakhir
                            </h5>
                            <small class="text-muted">10 transaksi kas terbaru</small>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach($transaksiTerbaru->take(5) as $transaksi)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">
                                                @if($transaksi->jenis == 'masuk')
                                                <span class="badge bg-success me-1">+</span>
                                                @else
                                                <span class="badge bg-danger me-1">-</span>
                                                @endif
                                                {{ ucfirst($transaksi->kategori) }}
                                            </h6>
                                            <small class="text-muted">{{ $transaksi->tanggal_transaksi->format('d M Y H:i') }}</small>
                                        </div>
                                        <strong class="@if($transaksi->jenis == 'masuk') text-success @else text-danger @endif">
                                            Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                                        </strong>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
