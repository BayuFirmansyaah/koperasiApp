<x-app-layout>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Detail Anggota
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('anggota.index') }}" class="btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                            Kembali
                        </a>
                        @can('update-anggota')
                        <a href="{{ route('anggota.edit', $anggota) }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                            Edit
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($anggota->foto)
                            <img src="{{ Storage::url($anggota->foto) }}" class="avatar avatar-xl mb-3 rounded">
                            @else
                            <span class="avatar avatar-xl mb-3">{{ substr($anggota->user->name, 0, 2) }}</span>
                            @endif
                            <h3 class="m-0 mb-1">{{ $anggota->user->name }}</h3>
                            <div class="text-muted mb-3">{{ $anggota->no_anggota ?? 'Belum ada nomor' }}</div>
                            <div class="mb-3">
                                @if($anggota->status == 'active')
                                <span class="badge bg-success badge-pill">Active</span>
                                @elseif($anggota->status == 'pending')
                                <span class="badge bg-warning badge-pill">Pending</span>
                                @elseif($anggota->status == 'inactive')
                                <span class="badge bg-secondary badge-pill">Inactive</span>
                                @else
                                <span class="badge bg-danger badge-pill">Suspended</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title mb-3">Kontak</h4>
                            <div class="mb-2 d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                                <div>{{ $anggota->user->email }}</div>
                            </div>
                            <div class="mb-2 d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                                <div>{{ $anggota->no_telepon }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Pribadi</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">NIK</div>
                                <div class="col-md-8">{{ $anggota->nik }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Tempat, Tanggal Lahir</div>
                                <div class="col-md-8">{{ $anggota->tempat_lahir }}, {{ formatDateLong($anggota->tanggal_lahir) }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Jenis Kelamin</div>
                                <div class="col-md-8">{{ $anggota->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Alamat</div>
                                <div class="col-md-8">{{ $anggota->alamat }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Pekerjaan</div>
                                <div class="col-md-8">{{ $anggota->pekerjaan }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Tanggal Daftar</div>
                                <div class="col-md-8">{{ formatDateTimeLong($anggota->created_at) }}</div>
                            </div>
                            @if($anggota->approved_at)
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Disetujui Tanggal</div>
                                <div class="col-md-8">{{ formatDateTimeLong($anggota->approved_at) }}</div>
                            </div>
                            @endif
                            @if($anggota->approvedBy)
                            <div class="row mb-3">
                                <div class="col-md-4 fw-bold">Disetujui Oleh</div>
                                <div class="col-md-8">{{ $anggota->approvedBy->name }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Simpanan</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <small class="text-muted">Total Simpanan</small>
                                        <h3 class="mb-0">Rp {{ number_format($anggota->getTotalSimpananAttribute(), 0, ',', '.') }}</h3>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <small class="text-muted">Jumlah Transaksi</small>
                                        <h3 class="mb-0">{{ $anggota->simpanans->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                            @if($anggota->simpanans->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jenis</th>
                                            <th class="text-end">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($anggota->simpanans->take(5) as $simpanan)
                                        <tr>
                                            <td>{{ formatDate($simpanan->tanggal_transaksi) }}</td>
                                            <td>{{ $simpanan->jenisSimpanan->nama ?? '-' }}</td>
                                            <td class="text-end">Rp {{ number_format($simpanan->nominal ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center text-muted py-3">
                                Belum ada transaksi simpanan
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Pinjaman Aktif</h3>
                        </div>
                        <div class="card-body">
                            @php
                            $pinjamanAktif = $anggota->pinjamans->where('status', 'berjalan');
                            @endphp
                            @if($pinjamanAktif->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th class="text-end">Jumlah Pinjaman</th>
                                            <th class="text-end">Sudah Dibayar</th>
                                            <th class="text-end">Sisa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pinjamanAktif as $pinjaman)
                                        <tr>
                                            <td>{{ formatDate($pinjaman->tanggal_pengajuan) }}</td>
                                            <td class="text-end">Rp {{ number_format($pinjaman->nominal ?? 0, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($pinjaman->angsurans->sum('nominal_bayar') ?? 0, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format(($pinjaman->nominal ?? 0) - ($pinjaman->angsurans->sum('nominal_bayar') ?? 0), 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center text-muted py-3">
                                Tidak ada pinjaman aktif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
