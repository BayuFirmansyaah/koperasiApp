@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@section('header')
<div class="page-pretitle">Selamat Datang</div>
<h2 class="page-title">Dashboard Anggota</h2>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card bg-blue-lt">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="avatar avatar-xl rounded" style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=128&background=206bc4&color=fff)"></span>
                    </div>
                    <div class="col">
                        <h2 class="mb-1">Halo, {{ auth()->user()->name }}!</h2>
                        <p class="text-muted mb-2">No. Anggota: <strong>{{ auth()->user()->anggota->no_anggota ?? '-' }}</strong> • Status: <span class="badge bg-green ms-1">Aktif</span></p>
                        <p class="mb-0">Selamat datang di dashboard koperasi Anda. Monitor simpanan dan pinjaman Anda di sini.</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards">
    <!-- Simpanan Section -->
    <div class="col-lg-8">
        <!-- Simpanan Summary -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ringkasan Simpanan</h3>
                <div class="card-actions">
                    <a href="{{ route('simpanan.create') }}" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Setor Simpanan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="subheader mb-2">Total Simpanan Anda</div>
                        <div class="h1 text-primary mb-0">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</div>
                        <div class="text-muted small mt-1">Dari {{ $jumlahTransaksiSimpanan }} transaksi</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex flex-column h-100 justify-content-center">
                            <div class="mb-2">
                                <span class="badge bg-green-lt me-1">Simpanan Pokok</span>
                                <span class="badge bg-blue-lt me-1">Simpanan Wajib</span>
                                <span class="badge bg-yellow-lt">Simpanan Sukarela</span>
                            </div>
                            <div class="text-muted small">Simpanan Anda telah terverifikasi dan aman</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Simpanan Terakhir -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Transaksi Simpanan Terakhir</h3>
                <div class="card-actions">
                    <a href="{{ route('simpanan.index') }}" class="btn btn-sm">Lihat Semua</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Simpanan</th>
                            <th>Nominal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($simpananTerakhir as $simpanan)
                        <tr>
                            <td class="text-muted">{{ formatDate($simpanan->tanggal_transaksi) }}</td>
                            <td>{{ $simpanan->jenisSimpanan->nama }}</td>
                            <td class="fw-bold text-green">Rp {{ number_format($simpanan->nominal, 0, ',', '.') }}</td>
                            <td><span class="badge bg-green">Terverifikasi</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada transaksi simpanan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pinjaman Section -->
        @if($pinjamanAktif)
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">Pinjaman Aktif Anda</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="text-muted small">Total Pinjaman</div>
                        <div class="h3 mb-0">Rp {{ number_format($pinjamanAktif->total_pinjaman, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Sudah Dibayar</div>
                        <div class="h3 mb-0 text-success">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Sisa Pinjaman</div>
                        <div class="h3 mb-0 text-danger">Rp {{ number_format($pinjamanAktif->sisa_pinjaman, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Angsuran/Bulan</div>
                        <div class="h3 mb-0">Rp {{ number_format($pinjamanAktif->nominal_angsuran_per_bulan, 0, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Progress Pembayaran</span>
                        <span class="fw-bold">{{ number_format(($totalDibayar / $pinjamanAktif->total_pinjaman) * 100, 1) }}%</span>
                    </div>
                    <div class="progress progress-lg">
                        <div class="progress-bar bg-success" style="width: {{ ($totalDibayar / $pinjamanAktif->total_pinjaman) * 100 }}%"></div>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="card bg-red-lt">
                            <div class="card-body p-3">
                                <div class="text-muted small">Angsuran Terlambat</div>
                                <div class="h2 mb-0">{{ $angsuranTerlambat }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-blue-lt">
                            <div class="card-body p-3">
                                <div class="text-muted small">Sisa Angsuran</div>
                                <div class="h2 mb-0">{{ $sisaAngsuran }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($angsuranBerikutnya)
                <div class="alert alert-info mt-3 mb-0" role="alert">
                    <div class="d-flex">
                        <div class="me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                        </div>
                        <div class="flex-fill">
                            <h4 class="alert-title">Angsuran Jatuh Tempo!</h4>
                            <div class="text-muted">
                                Angsuran ke-{{ $angsuranBerikutnya->angsuran_ke }} sebesar <strong>Rp {{ number_format($angsuranBerikutnya->nominal, 0, ',', '.') }}</strong> jatuh tempo pada 
                                <strong>{{ formatDateLong($angsuranBerikutnya->tanggal_jatuh_tempo) }}</strong>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('angsuran.create') }}" class="btn btn-info btn-sm">Bayar Sekarang</a>
                                <a href="{{ route('angsuran.index') }}" class="btn btn-sm">Lihat Jadwal</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @else
        <!-- No Pinjaman -->
        <div class="card mt-3">
            <div class="card-body text-center py-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted mb-3" width="64" height="64" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" /><path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" /><path d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z" /><path d="M3 6v10c0 .888 .772 1.45 2 2" /><path d="M3 11c0 .888 .772 1.45 2 2" /></svg>
                <h3 class="mb-2">Tidak Ada Pinjaman Aktif</h3>
                <p class="text-muted">Anda dapat mengajukan pinjaman baru sesuai kebutuhan</p>
                <a href="{{ route('pinjaman.create') }}" class="btn btn-primary mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Ajukan Pinjaman
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Right Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Menu Cepat</h3>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('simpanan.index') }}" class="list-group-item list-group-item-action">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="avatar bg-green-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                            </div>
                        </div>
                        <div class="flex-fill">
                            <div class="font-weight-medium">Riwayat Simpanan</div>
                            <div class="text-muted small">Lihat semua transaksi simpanan</div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('pinjaman.create') }}" class="list-group-item list-group-item-action">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="avatar bg-blue-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" /><path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" /><path d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z" /><path d="M3 6v10c0 .888 .772 1.45 2 2" /><path d="M3 11c0 .888 .772 1.45 2 2" /></svg>
                            </div>
                        </div>
                        <div class="flex-fill">
                            <div class="font-weight-medium">Ajukan Pinjaman</div>
                            <div class="text-muted small">Buat pengajuan pinjaman baru</div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('angsuran.index') }}" class="list-group-item list-group-item-action">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="avatar bg-yellow-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" /></svg>
                            </div>
                        </div>
                        <div class="flex-fill">
                            <div class="font-weight-medium">Jadwal Angsuran</div>
                            <div class="text-muted small">Lihat jadwal pembayaran</div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('laporan.rekening-koran') }}" class="list-group-item list-group-item-action">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="avatar bg-purple-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
                            </div>
                        </div>
                        <div class="flex-fill">
                            <div class="font-weight-medium">Rekening Koran</div>
                            <div class="text-muted small">Cetak rekening koran</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Riwayat Pinjaman -->
        @if($riwayatPinjaman->count() > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Riwayat Pinjaman</h3>
            </div>
            <div class="card-body">
                <div class="divide-y">
                    @foreach($riwayatPinjaman as $pinjaman)
                    <div class="py-2">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <div class="text-muted small">{{ formatDate($pinjaman->tanggal_pengajuan) }}</div>
                                <div class="fw-bold">Rp {{ number_format($pinjaman->total_pinjaman, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                @if($pinjaman->status == 'lunas')
                                <span class="badge bg-success">Lunas</span>
                                @else
                                <span class="badge bg-blue">Berjalan</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-muted small">{{ $pinjaman->jangka_waktu }} bulan • {{ number_format($pinjaman->bunga_persen, 1) }}% bunga</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Info Card -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                    </div>
                    <div>
                        <h4 class="mb-1">Informasi Penting</h4>
                        <div class="text-muted small">
                            <ul class="mb-0 ps-3">
                                <li>Pastikan Anda membayar angsuran tepat waktu</li>
                                <li>Simpanan Anda aman dan terverifikasi</li>
                                <li>Hubungi pengurus untuk bantuan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
