<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-5" style="color: #1e293b;">
                {{ __('Dashboard') }}
            </h2>
            <div class="text-muted" style="font-size: 0.875rem;">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container-xl">
            <!-- Stats Cards Row -->
            <div class="row mb-4">
                @can('view-anggota')
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1" style="font-size: 0.875rem;">Total Anggota</p>
                                    <h3 class="fw-bold mb-0" style="color: #206bc4;">{{ $totalAnggota ?? 0 }}</h3>
                                </div>
                                <div class="text-primary" style="font-size: 2rem; opacity: 0.2;">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Anggota aktif dalam sistem</small>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-pinjaman')
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1" style="font-size: 0.875rem;">Total Pinjaman</p>
                                    <h3 class="fw-bold mb-0" style="color: #2fb344;">{{ $totalPinjaman ?? 0 }}</h3>
                                </div>
                                <div class="text-success" style="font-size: 2rem; opacity: 0.2;">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Total pinjaman terdaftar</small>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-simpanan')
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1" style="font-size: 0.875rem;">Total Simpanan</p>
                                    <h3 class="fw-bold mb-0" style="color: #4299e1;">{{ $totalSimpanan ?? 0 }}</h3>
                                </div>
                                <div class="text-info" style="font-size: 2rem; opacity: 0.2;">
                                    <i class="bi bi-piggy-bank-fill"></i>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Total simpanan anggota</small>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-kas')
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1" style="font-size: 0.875rem;">Saldo Kas</p>
                                    <h3 class="fw-bold mb-0" style="color: #f76707;">Rp {{ number_format($saldoKas ?? 0, 0, ',', '.') }}</h3>
                                </div>
                                <div class="text-warning" style="font-size: 2rem; opacity: 0.2;">
                                    <i class="bi bi-vault-fill"></i>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Saldo kas terkini</small>
                        </div>
                    </div>
                </div>
                @endcan
            </div>

            <!-- Status Overview Row -->
            <div class="row mb-4">
                @can('view-anggota')
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-people-fill"></i> Status Anggota
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 col-md-3">
                                    <div class="mb-2">
                                        <h4 class="fw-bold mb-1" style="color: #2fb344;">{{ $anggotaAktif ?? 0 }}</h4>
                                        <small class="text-muted">Aktif</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="mb-2">
                                        <h4 class="fw-bold mb-1" style="color: #f76707;">{{ $anggotaMenunggu ?? 0 }}</h4>
                                        <small class="text-muted">Menunggu</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="mb-2">
                                        <h4 class="fw-bold mb-1" style="color: #d63939;">{{ $anggotaDitolak ?? 0 }}</h4>
                                        <small class="text-muted">Ditolak</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="mb-2">
                                        <h4 class="fw-bold mb-1" style="color: #6c757d;">{{ $anggotaNonaktif ?? 0 }}</h4>
                                        <small class="text-muted">Non-aktif</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-pinjaman')
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-cash-coin"></i> Status Pinjaman
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 col-md-3">
                                    <div class="mb-2">
                                        <h4 class="fw-bold mb-1" style="color: #206bc4;">{{ $pinjamanMenunggu ?? 0 }}</h4>
                                        <small class="text-muted">Menunggu</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="mb-2">
                                        <h4 class="fw-bold mb-1" style="color: #2fb344;">{{ $pinjamanDiluluskan ?? 0 }}</h4>
                                        <small class="text-muted">Diluluskan</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="mb-2">
                                        <h4 class="fw-bold mb-1" style="color: #4299e1;">{{ $pinjamanBerjalan ?? 0 }}</h4>
                                        <small class="text-muted">Berjalan</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="mb-2">
                                        <h4 class="fw-bold mb-1" style="color: #d63939;">{{ $pinjamanDitolak ?? 0 }}</h4>
                                        <small class="text-muted">Ditolak</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
            </div>

            <!-- Recent Activities / Quick Actions -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-lightning-charge"></i> Aksi Cepat
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

                                @can('view-angsuran')
                                <div class="col-md-3 col-sm-6">
                                    <a href="{{ route('angsuran.index') }}" class="btn btn-outline-warning w-100">
                                        <i class="bi bi-calendar-check"></i> Daftar Angsuran
                                    </a>
                                </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Welcome Message -->
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info border-0 d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <div>
                            <strong>Selamat datang!</strong> Anda sedang menggunakan Sistem Manajemen Koperasi. 
                            Navigasi menu di atas untuk mengelola data anggota, pinjaman, simpanan, dan laporan keuangan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
