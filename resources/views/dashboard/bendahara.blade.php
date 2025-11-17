@extends('layouts.app')

@section('title', 'Dashboard Bendahara')

@section('header')
<div class="page-pretitle">Manajemen Keuangan</div>
<h2 class="page-title">Dashboard Bendahara</h2>
@endsection

@section('content')
<!-- Welcome Section -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card bg-teal text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-1">Selamat Datang, {{ Auth::user()->name }}!</h2>
                        <p class="mb-0 opacity-75">Anda login sebagai <strong>Bendahara Koperasi</strong>. Kelola keuangan, verifikasi transaksi, dan pantau kas koperasi.</p>
                    </div>
                    <div class="col-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards">
    <!-- Financial Overview Cards -->
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader">Saldo Kas</div>
                    <div class="ms-auto">
                        <span class="badge bg-green"></span>
                    </div>
                </div>
                <div class="h1 mb-0">Rp {{ number_format($saldoKas, 0, ',', '.') }}</div>
                <div class="text-muted small mb-3">Posisi kas saat ini</div>
                <div class="d-flex gap-2">
                    <a href="{{ route('kas.index') }}" class="btn btn-sm btn-primary flex-fill">Detail</a>
                    <a href="{{ route('kas.create') }}" class="btn btn-sm btn-success flex-fill">Tambah</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader">Pemasukan Bulan Ini</div>
                </div>
                <div class="h1 mb-0 text-green">Rp {{ number_format($pemasukan, 0, ',', '.') }}</div>
                <div class="text-muted small mb-2">Total kas masuk</div>
                <div class="d-flex align-items-center text-success small">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                    Periode {{ now()->format('F Y') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader">Pengeluaran Bulan Ini</div>
                </div>
                <div class="h1 mb-0 text-red">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</div>
                <div class="text-muted small mb-2">Total kas keluar</div>
                <div class="d-flex align-items-center text-danger small">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7l6 6l4 -4l8 8" /><path d="M21 10l0 7l-7 0" /></svg>
                    Periode {{ now()->format('F Y') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader">Selisih Bulan Ini</div>
                </div>
                <div class="h1 mb-0 {{ ($pemasukan - $pengeluaran) >= 0 ? 'text-success' : 'text-danger' }}">
                    Rp {{ number_format(abs($pemasukan - $pengeluaran), 0, ',', '.') }}
                </div>
                <div class="text-muted small mb-2">
                    @if(($pemasukan - $pengeluaran) >= 0)
                    Surplus
                    @else
                    Defisit
                    @endif
                </div>
                <div class="progress progress-sm">
                    @if(($pemasukan - $pengeluaran) >= 0)
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                    @else
                    <div class="progress-bar bg-danger" style="width: 100%"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Tasks -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-yellow-lt">
                <h3 class="card-title">Tugas Verifikasi</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card card-sm bg-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="avatar bg-yellow-lt">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                                        </div>
                                    </div>
                                    <div class="flex-fill">
                                        <div class="text-muted small">Simpanan Pending</div>
                                        <div class="h2 mb-0">{{ $simpananPending }}</div>
                                        <div class="text-warning fw-bold">Rp {{ number_format($simpananPendingNominal, 0, ',', '.') }}</div>
                                    </div>
                                    @if($simpananPending > 0)
                                    <div>
                                        <a href="{{ route('simpanan.index') }}" class="btn btn-sm btn-warning">Verifikasi</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card card-sm bg-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="avatar bg-red-lt">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" /></svg>
                                        </div>
                                    </div>
                                    <div class="flex-fill">
                                        <div class="text-muted small">Angsuran Jatuh Tempo</div>
                                        <div class="h2 mb-0">{{ $angsuranPending }}</div>
                                        <div class="text-danger fw-bold">Rp {{ number_format($angsuranPendingNominal, 0, ',', '.') }}</div>
                                    </div>
                                    @if($angsuranPending > 0)
                                    <div>
                                        <a href="{{ route('angsuran.index') }}" class="btn btn-sm btn-danger">Tindak Lanjut</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card card-sm bg-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="avatar bg-blue-lt">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" /><path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" /><path d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z" /><path d="M3 6v10c0 .888 .772 1.45 2 2" /><path d="M3 11c0 .888 .772 1.45 2 2" /></svg>
                                        </div>
                                    </div>
                                    <div class="flex-fill">
                                        <div class="text-muted small">Pinjaman Siap Dicairkan</div>
                                        <div class="h2 mb-0">{{ $pinjamanDicairkan }}</div>
                                        <div class="text-info fw-bold">Rp {{ number_format($pinjamanDicairkanNominal, 0, ',', '.') }}</div>
                                    </div>
                                    @if($pinjamanDicairkan > 0)
                                    <div>
                                        <a href="{{ route('pinjaman.index') }}" class="btn btn-sm btn-info">Cairkan</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Transaksi Terakhir</h3>
                <div class="card-actions">
                    <a href="{{ route('kas.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th class="text-end">Nominal</th>
                            <th class="text-end">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTerakhir as $transaksi)
                        <tr>
                            <td class="text-muted small">{{ formatDateTime($transaksi->tanggal_transaksi) }}</td>
                            <td>
                                <div>{{ Str::limit($transaksi->keterangan, 35) }}</div>
                                @if($transaksi->transactable)
                                <div class="text-muted small">{{ class_basename($transaksi->transactable_type) }}</div>
                                @endif
                            </td>
                            <td>
                                @if($transaksi->jenis == 'masuk')
                                <span class="badge bg-green-lt">Masuk</span>
                                @else
                                <span class="badge bg-red-lt">Keluar</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <span class="{{ $transaksi->jenis == 'masuk' ? 'text-green' : 'text-red' }} fw-bold">
                                    {{ $transaksi->jenis == 'masuk' ? '+' : '-' }} Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-end fw-bold">Rp {{ number_format($transaksi->saldo_sesudah, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Simpanan Pending List -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Simpanan Menunggu Verifikasi</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($simpananPendingList as $simpanan)
                        <tr>
                            <td>
                                <div>{{ $simpanan->anggota->user->name }}</div>
                                <div class="text-muted small">{{ formatDate($simpanan->tanggal_transaksi) }}</div>
                            </td>
                            <td>{{ $simpanan->jenisSimpanan->nama }}</td>
                            <td class="fw-bold text-warning">Rp {{ number_format($simpanan->nominal, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <a href="{{ route('simpanan.show', $simpanan->id) }}" class="btn btn-sm btn-warning">Verifikasi</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                <div>Semua simpanan sudah terverifikasi</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Angsuran Jatuh Tempo List -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Angsuran Jatuh Tempo</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>Angsuran Ke</th>
                            <th>Jatuh Tempo</th>
                            <th class="text-end">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($angsuranJatuhTempo as $angsuran)
                        <tr>
                            <td>
                                <div>{{ $angsuran->pinjaman->anggota->user->name }}</div>
                                <div class="text-muted small">{{ $angsuran->pinjaman->anggota->no_anggota }}</div>
                            </td>
                            <td><span class="badge bg-blue">Ke-{{ $angsuran->angsuran_ke }}</span></td>
                            <td>
                                <div class="{{ now()->gt($angsuran->tanggal_jatuh_tempo) ? 'text-danger' : 'text-muted' }}">
                                    {{ formatDate($angsuran->tanggal_jatuh_tempo) }}
                                </div>
                                @if(now()->gt($angsuran->tanggal_jatuh_tempo))
                                <div class="text-danger small">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                                    Terlambat
                                </div>
                                @endif
                            </td>
                            <td class="text-end fw-bold">Rp {{ number_format($angsuran->nominal, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                <div>Tidak ada angsuran jatuh tempo</div>
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
                @can('verify-simpanan')
                <a href="{{ route('simpanan.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar bg-yellow-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-medium">Verifikasi Simpanan</div>
                        <div class="text-muted small">Verifikasi transaksi simpanan anggota</div>
                    </div>
                    @if($simpananPending > 0)
                    <span class="badge bg-yellow ms-auto">{{ $simpananPending }}</span>
                    @endif
                </a>
                @endcan

                @can('verify-angsuran')
                <a href="{{ route('angsuran.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar bg-blue-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" /></svg>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-medium">Verifikasi Angsuran</div>
                        <div class="text-muted small">Verifikasi pembayaran angsuran pinjaman</div>
                    </div>
                </a>
                @endcan

                @can('disburse-pinjaman')
                <a href="{{ route('pinjaman.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar bg-green-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" /><path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" /><path d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z" /><path d="M3 6v10c0 .888 .772 1.45 2 2" /><path d="M3 11c0 .888 .772 1.45 2 2" /></svg>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-medium">Pencairan Pinjaman</div>
                        <div class="text-muted small">Proses pencairan pinjaman yang disetujui</div>
                    </div>
                    @if($pinjamanDicairkan > 0)
                    <span class="badge bg-blue ms-auto">{{ $pinjamanDicairkan }}</span>
                    @endif
                </a>
                @endcan

                @can('view-kas')
                <a href="{{ route('kas.create') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar bg-purple-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21h18" /><path d="M5 21v-14l8 -4v18" /><path d="M19 21v-10l-6 -4" /><path d="M9 9h1" /><path d="M9 12h1" /><path d="M9 15h1" /><path d="M9 18h1" /></svg>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-medium">Catat Transaksi Kas</div>
                        <div class="text-muted small">Catat transaksi kas masuk dan keluar</div>
                    </div>
                </a>
                @endcan

                <a href="{{ route('laporan.keuangan') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar bg-cyan-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 17v-5" /><path d="M12 17v-1" /><path d="M15 17v-3" /></svg>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-medium">Laporan Keuangan</div>
                        <div class="text-muted small">Lihat dan cetak laporan keuangan</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Info & Responsibilities -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tugas & Tanggung Jawab</h3>
            </div>
            <div class="card-body">
                <div class="markdown text-muted">
                    <h4 class="mb-2">Tugas Utama:</h4>
                    <ul class="mb-3">
                        <li>Mengelola semua transaksi keuangan</li>
                        <li>Memverifikasi simpanan dan angsuran</li>
                        <li>Melakukan pencairan pinjaman</li>
                        <li>Menyusun laporan keuangan</li>
                        <li>Menjaga keseimbangan kas</li>
                    </ul>
                    
                    <h4 class="mb-2">Tips Penting:</h4>
                    <ul class="mb-0">
                        <li>Periksa bukti transaksi sebelum verifikasi</li>
                        <li>Catat transaksi secara detail dan akurat</li>
                        <li>Rekonsiliasi kas secara rutin</li>
                        <li>Simpan bukti transaksi dengan baik</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
