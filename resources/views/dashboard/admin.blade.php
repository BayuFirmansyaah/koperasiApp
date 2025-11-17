@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('header')
<div class="page-pretitle">Overview</div>
<h2 class="page-title">Dashboard Admin</h2>
@endsection

@section('content')
<!-- Welcome Section -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h2 class="mb-1">Selamat Datang, {{ Auth::user()->name }}!</h2>
                        <p class="mb-0 opacity-75">Anda login sebagai Super Admin. Berikut adalah ringkasan sistem koperasi Anda.</p>
                    </div>
                    <div class="ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards">
    <!-- Primary Stats Cards -->
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Anggota</div>
                    <div class="ms-auto">
                        @if($pertumbuhanAnggota > 0)
                        <span class="text-green d-inline-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                            {{ $pertumbuhanAnggota }}%
                        </span>
                        @endif
                    </div>
                </div>
                <div class="h1 mb-3">{{ number_format($totalAnggota) }}</div>
                <div class="d-flex mb-2">
                    <div class="text-muted small">Anggota aktif</div>
                </div>
                @if($anggotaPending > 0)
                <div class="mt-2">
                    <span class="badge bg-yellow">{{ $anggotaPending }} Menunggu Persetujuan</span>
                </div>
                @endif
                <div class="progress progress-sm mt-2">
                    <div class="progress-bar bg-green" style="width: {{ $totalAnggota > 0 ? (($totalAnggota / ($totalAnggota + $anggotaPending)) * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Simpanan</div>
                </div>
                <div class="h1 mb-0">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</div>
                <div class="text-muted small mb-2">Akumulasi semua simpanan</div>
                <div class="d-flex align-items-center mt-2">
                    <div class="me-auto">
                        <span class="badge bg-blue">{{ $transaksiSimpananBulanIni }} Transaksi</span>
                    </div>
                    <div class="text-end">
                        <div class="text-green small">Bulan ini:</div>
                        <div class="fw-bold small">Rp {{ number_format($simpananBulanIni, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Pinjaman Berjalan</div>
                </div>
                <div class="h1 mb-0">Rp {{ number_format($sisaPinjaman, 0, ',', '.') }}</div>
                <div class="text-muted small mb-2">Sisa yang harus dibayar</div>
                <div class="mt-2">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="text-muted small">Total:</div>
                            <div class="fw-bold small">Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Lunas:</div>
                            <div class="fw-bold small text-success">{{ $pinjamanLunas }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Saldo Kas</div>
                </div>
                <div class="h1 mb-0">Rp {{ number_format($saldoKas, 0, ',', '.') }}</div>
                <div class="text-muted small mb-2">Posisi kas saat ini</div>
                <div class="mt-2">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="text-muted small">Masuk:</div>
                            <div class="fw-bold small text-green">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Keluar:</div>
                            <div class="fw-bold small text-red">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <div class="col-lg-6" width="100%">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aksi Cepat</h3>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('anggota.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar bg-blue-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-medium">Kelola Anggota</div>
                        <div class="text-muted small">Lihat dan kelola data anggota</div>
                    </div>
                </a>
                <a href="{{ route('simpanan.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar bg-green-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-medium">Transaksi Simpanan</div>
                        <div class="text-muted small">Kelola simpanan anggota</div>
                    </div>
                </a>
                <a href="{{ route('pinjaman.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar bg-yellow-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" /><path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" /><path d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z" /><path d="M3 6v10c0 .888 .772 1.45 2 2" /><path d="M3 11c0 .888 .772 1.45 2 2" /></svg>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-medium">Kelola Pinjaman</div>
                        <div class="text-muted small">Monitor pinjaman anggota</div>
                    </div>
                </a>
                <a href="{{ route('laporan.keuangan') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar bg-purple-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 17v-5" /><path d="M12 17v-1" /><path d="M15 17v-3" /></svg>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-medium">Laporan Keuangan</div>
                        <div class="text-muted small">Lihat laporan dan statistik</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
   
    <div class="col-lg-6">
         <div class="card" style="width:100%">
            <div class="card-header">
                <h3 class="card-title">Statistik Sistem</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="d-flex flex-column align-items-center p-3 bg-blue-lt rounded">
                            <div class="h1 mb-1">{{ $totalUsers }}</div>
                            <div class="text-muted small text-center">Total User</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column align-items-center p-3 bg-green-lt rounded">
                            <div class="h1 mb-1">{{ $totalAnggota }}</div>
                            <div class="text-muted small text-center">Anggota Aktif</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column align-items-center p-3 bg-yellow-lt rounded">
                            <div class="h1 mb-1">{{ $pinjamanLunas }}</div>
                            <div class="text-muted small text-center">Pinjaman Lunas</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column align-items-center p-3 bg-purple-lt rounded">
                            <div class="h1 mb-1">{{ $transaksiSimpananBulanIni }}</div>
                            <div class="text-muted small text-center">Transaksi Bulan Ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
         <div class="card" style="width:100%">
            <div class="card-header">
                <h3 class="card-title">Transaksi Kas Terakhir</h3>
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
                        @forelse($transaksiTerbaru as $transaksi)
                        <tr>
                            <td class="text-muted">{{ formatDate($transaksi->tanggal_transaksi) }}</td>
                            <td>{{ Str::limit($transaksi->keterangan, 40) }}</td>
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
   
    <div class="col-lg-6">
         <div class="card" style="width: 100%">
            <div class="card-header">
                <h3 class="card-title">Anggota Terbaru</h3>
                <div class="card-actions">
                    <a href="{{ route('anggota.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>No. Anggota</th>
                            <th>Nama</th>
                            <th>Bergabung</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggotaTerbaru as $anggota)
                        <tr>
                            <td class="text-muted">{{ $anggota->no_anggota }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm me-2" style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode($anggota->user->name) }}&size=32&background=206bc4&color=fff)"></span>
                                    {{ $anggota->user->name }}
                                </div>
                            </td>
                            <td class="text-muted">{{ formatDate($anggota->created_at) }}</td>
                            <td><span class="badge bg-green">Aktif</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada anggota</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
