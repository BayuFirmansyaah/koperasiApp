<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detail Anggota</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('anggota.index') }}" class="btn btn-outline-secondary">Kembali</a>
                @can('update-anggota')
                <a href="{{ route('anggota.edit', $anggota) }}" class="btn btn-primary">Edit</a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-lg">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Informasi Umum</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Nama Lengkap</small>
                            <div>{{ $anggota->user->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">No. Anggota</small>
                            <div>{{ $anggota->no_anggota ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Email</small>
                            <div>{{ $anggota->user->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">No. Telepon</small>
                            <div>{{ $anggota->no_telepon }}</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Status</small>
                            <div>
                                @if($anggota->status == 'active')
                                <span class="badge bg-success">Active</span>
                                @elseif($anggota->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                                @elseif($anggota->status == 'inactive')
                                <span class="badge bg-secondary">Inactive</span>
                                @else
                                <span class="badge bg-danger">Suspended</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Daftar</small>
                            <div>{{ formatDateTimeLong($anggota->created_at) }}</div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Data Pribadi</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">NIK</small>
                            <div>{{ $anggota->nik }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Jenis Kelamin</small>
                            <div>{{ $anggota->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Tempat Lahir</small>
                            <div>{{ $anggota->tempat_lahir }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Lahir</small>
                            <div>{{ formatDateLong($anggota->tanggal_lahir) }}</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <small class="text-muted">Alamat</small>
                            <div>{{ $anggota->alamat }}</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Pekerjaan</small>
                            <div>{{ $anggota->pekerjaan }}</div>
                        </div>
                        @if($anggota->approved_at)
                        <div class="col-md-6">
                            <small class="text-muted">Disetujui Tanggal</small>
                            <div>{{ formatDateTimeLong($anggota->approved_at) }}</div>
                        </div>
                        @endif
                    </div>

                    @if($anggota->approvedBy)
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Disetujui Oleh</small>
                            <div>{{ $anggota->approvedBy->name }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3">Simpanan</h5>
                            <div class="mb-3">
                                <small class="text-muted">Total Simpanan</small>
                                <h4 class="mb-0">Rp {{ number_format($anggota->getTotalSimpananAttribute(), 0, ',', '.') }}</h4>
                            </div>
                            <small class="text-muted">Jumlah Transaksi: {{ $anggota->simpanans->count() }}</small>
                            @if($anggota->simpanans->isNotEmpty())
                            <div class="table-responsive mt-3">
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
                            <div class="text-muted text-center py-3">Belum ada transaksi</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3">Pinjaman Aktif</h5>
                            @php
                            $pinjamanAktif = $anggota->pinjamans->where('status', 'berjalan');
                            @endphp
                            @if($pinjamanAktif->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th class="text-end">Nominal</th>
                                            <th class="text-end">Sisa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pinjamanAktif as $pinjaman)
                                        <tr>
                                            <td>{{ formatDate($pinjaman->tanggal_pengajuan) }}</td>
                                            <td class="text-end">Rp {{ number_format($pinjaman->nominal ?? 0, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format(($pinjaman->nominal ?? 0) - ($pinjaman->angsurans->sum('nominal_bayar') ?? 0), 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-muted text-center py-3">Tidak ada pinjaman aktif</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
