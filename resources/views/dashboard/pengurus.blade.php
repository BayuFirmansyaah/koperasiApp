@extends('layouts.app')

@section('title', 'Dashboard Pengurus')

@section('header')
<div class="page-pretitle">Manajemen Koperasi</div>
<h2 class="page-title">Dashboard Pengurus</h2>
@endsection

@section('content')
<!-- Welcome Section -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card bg-purple text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-1">Selamat Datang, {{ Auth::user()->name }}!</h2>
                        <p class="mb-0 opacity-75">Anda login sebagai <strong>Pengurus Koperasi</strong>. Kelola persetujuan anggota dan pinjaman di sini.</p>
                    </div>
                    <div class="col-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12l2 2l4 -4" /></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards">
    <!-- Statistics Cards -->
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader">Anggota Pending</div>
                    @if($anggotaPending > 0)
                    <div class="ms-auto">
                        <span class="badge bg-red"></span>
                    </div>
                    @endif
                </div>
                <div class="h1 mb-0">{{ $anggotaPending }}</div>
                <div class="text-muted small mb-3">Menunggu persetujuan Anda</div>
                @if($anggotaPending > 0)
                <div class="progress progress-sm mb-2">
                    <div class="progress-bar bg-red" style="width: 100%"></div>
                </div>
                <a href="{{ route('pengurus.approval.index') }}" class="btn btn-sm btn-red w-100">Proses Sekarang</a>
                @else
                <div class="text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                    Tidak ada pending
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader">Anggota Aktif</div>
                    <div class="ms-auto">
                        <span class="badge bg-green"></span>
                    </div>
                </div>
                <div class="h1 mb-0">{{ $anggotaAktif }}</div>
                <div class="text-muted small mb-2">Total anggota terdaftar</div>
                @if($anggotaBaruBulanIni > 0)
                <div class="d-flex align-items-center text-success small">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                    +{{ $anggotaBaruBulanIni }} anggota baru bulan ini
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader">Pinjaman Pending</div>
                    @if($pinjamanPending > 0)
                    <div class="ms-auto">
                        <span class="badge bg-yellow"></span>
                    </div>
                    @endif
                </div>
                <div class="h1 mb-0">{{ $pinjamanPending }}</div>
                <div class="text-muted small mb-1">Perlu review Anda</div>
                <div class="fw-bold text-yellow">Rp {{ number_format($pinjamanPendingNominal, 0, ',', '.') }}</div>
                @if($pinjamanPending > 0)
                <div class="mt-2">
                    <a href="{{ route('pinjaman.index') }}" class="btn btn-sm btn-yellow w-100">Review Sekarang</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader">Pinjaman Aktif</div>
                    <div class="ms-auto">
                        <span class="badge bg-blue"></span>
                    </div>
                </div>
                <div class="h1 mb-0">{{ $pinjamanAktif }}</div>
                <div class="text-muted small mb-1">Sedang berjalan</div>
                <div class="fw-bold text-blue">Rp {{ number_format($pinjamanAktifNominal, 0, ',', '.') }}</div>
                @if($pinjamanDiReview > 0)
                <div class="mt-2">
                    <span class="badge bg-info">{{ $pinjamanDiReview }} Menunggu bendahara</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Anggota Pending List -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Anggota Menunggu Persetujuan</h3>
                <div class="card-actions">
                    <a href="{{ route('pengurus.approval.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Tanggal Daftar</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggotaPendingList as $anggota)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm me-2" style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode($anggota->user->name) }}&size=32&background=206bc4&color=fff)"></span>
                                    {{ $anggota->user->name }}
                                </div>
                            </td>
                            <td class="text-muted">{{ $anggota->user->email }}</td>
                            <td class="text-muted">{{ formatDate($anggota->created_at) }}</td>
                            <td class="text-end">
                                <a href="{{ route('pengurus.approval.show', $anggota->id) }}" class="btn btn-sm btn-primary">Review</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                <div>Tidak ada anggota pending</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pinjaman Pending List -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pengajuan Pinjaman Menunggu Review</h3>
                <div class="card-actions">
                    <a href="{{ route('pinjaman.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pinjamanPendingList as $pinjaman)
                        <tr>
                            <td>
                                <div>{{ $pinjaman->anggota->user->name }}</div>
                                <div class="text-muted small">{{ $pinjaman->anggota->no_anggota }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">Rp {{ number_format($pinjaman->total_pinjaman, 0, ',', '.') }}</div>
                                <div class="text-muted small">{{ $pinjaman->jangka_waktu }} bulan</div>
                            </td>
                            <td class="text-muted">{{ formatDate($pinjaman->tanggal_pengajuan) }}</td>
                            <td class="text-end">
                                <a href="{{ route('pinjaman.show', $pinjaman->id) }}" class="btn btn-sm btn-yellow">Review</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                <div>Tidak ada pinjaman pending</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aksi Cepat</h3>
            </div>
            <div class="list-group list-group-flush">
                @can('approve-anggota')
                <a href="{{ route('pengurus.approval.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar bg-red-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12l2 2l4 -4" /></svg>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-medium">Persetujuan Anggota Baru</div>
                        <div class="text-muted small">Review dan setujui pendaftaran anggota baru</div>
                    </div>
                    @if($anggotaPending > 0)
                    <span class="badge bg-red ms-auto">{{ $anggotaPending }}</span>
                    @endif
                </a>
                @endcan

                @can('view-anggota')
                <a href="{{ route('anggota.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar bg-green-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-medium">Daftar Anggota Aktif</div>
                        <div class="text-muted small">Lihat dan kelola data anggota aktif ({{ $anggotaAktif }})</div>
                    </div>
                </a>
                @endcan

                @can('review-pinjaman')
                <a href="{{ route('pinjaman.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar bg-yellow-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" /><path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" /><path d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z" /><path d="M3 6v10c0 .888 .772 1.45 2 2" /><path d="M3 11c0 .888 .772 1.45 2 2" /></svg>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-medium">Review Pengajuan Pinjaman</div>
                        <div class="text-muted small">Review dan proses pengajuan pinjaman dari anggota</div>
                    </div>
                    @if($pinjamanPending > 0)
                    <span class="badge bg-yellow ms-auto">{{ $pinjamanPending }}</span>
                    @endif
                </a>
                @endcan

                <a href="{{ route('laporan.anggota') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar bg-blue-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 17v-5" /><path d="M12 17v-1" /><path d="M15 17v-3" /></svg>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-medium">Laporan Anggota</div>
                        <div class="text-muted small">Lihat laporan lengkap data anggota</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Info & Tips -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tugas & Tanggung Jawab</h3>
            </div>
            <div class="card-body">
                <div class="markdown text-muted">
                    <h4 class="mb-2">Tugas Utama:</h4>
                    <ul class="mb-3">
                        <li>Menyetujui/menolak pendaftaran anggota baru</li>
                        <li>Review awal pengajuan pinjaman</li>
                        <li>Memantau status anggota dan pinjaman</li>
                        <li>Koordinasi dengan bendahara</li>
                    </ul>
                    
                    <h4 class="mb-2">Tips Penting:</h4>
                    <ul class="mb-0">
                        <li>Periksa kelengkapan dokumen dengan teliti</li>
                        <li>Verifikasi identitas dan data anggota</li>
                        <li>Evaluasi kemampuan bayar peminjam</li>
                        <li>Dokumentasikan setiap keputusan</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Statistics Summary -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Ringkasan Bulan Ini</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted small">Anggota Baru</div>
                        <div class="h2 mb-0 text-green">{{ $anggotaBaruBulanIni }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Anggota Nonaktif</div>
                        <div class="h2 mb-0 text-red">{{ $anggotaNonaktif }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Pengajuan Pinjaman</div>
                        <div class="h2 mb-0 text-blue">{{ $pinjamanBaruBulanIni }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
