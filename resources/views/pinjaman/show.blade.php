<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2>Detail Pinjaman</h2>
                <small class="text-muted">{{ $pinjaman->no_pinjaman }}</small>
            </div>
            <a href="{{ route('pinjaman.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-lg">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5>Informasi Pinjaman</h5>
                        @if($pinjaman->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                        @elseif($pinjaman->status == 'approved_pengurus')
                        <span class="badge bg-info">Approved Pengurus</span>
                        @elseif($pinjaman->status == 'approved_bendahara')
                        <span class="badge bg-primary">Siap Dicairkan</span>
                        @elseif($pinjaman->status == 'berjalan')
                        <span class="badge bg-success">Berjalan</span>
                        @elseif($pinjaman->status == 'lunas')
                        <span class="badge bg-secondary">Lunas</span>
                        @else
                        <span class="badge bg-danger">Rejected</span>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Pengajuan</small>
                            <div>{{ $pinjaman->tanggal_pengajuan->format('d F Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Nama Anggota</small>
                            <div>{{ $pinjaman->anggota->user->name }}</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Nominal Pinjaman</small>
                            <div class="h4 text-primary mb-0">Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Lama Pinjaman</small>
                            <div>{{ $pinjaman->lama_pinjaman }} bulan</div>
                        </div>
                    </div>

                    @if($pinjaman->sisa_pinjaman)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Sisa Pinjaman</small>
                            <div class="text-danger">Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <small class="text-muted">Tujuan Pinjaman</small>
                            <div>{{ $pinjaman->tujuan_pinjaman }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    @if($pinjaman->catatan_pengurus || $pinjaman->reviewedBy)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-3">Review Pengurus</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Direview Oleh</small>
                                    <div>{{ $pinjaman->reviewedBy->name ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Tanggal Review</small>
                                    <div>{{ $pinjaman->reviewed_at ? $pinjaman->reviewed_at->format('d F Y H:i') : '-' }}</div>
                                </div>
                            </div>
                            @if($pinjaman->catatan_pengurus)
                            <div class="mt-3">
                                <small class="text-muted">Catatan</small>
                                <div>{{ $pinjaman->catatan_pengurus }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($pinjaman->catatan_bendahara || $pinjaman->approvedBy)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-3">Approval Bendahara</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Diapprove Oleh</small>
                                    <div>{{ $pinjaman->approvedBy->name ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Tanggal Approval</small>
                                    <div>{{ $pinjaman->approved_at ? $pinjaman->approved_at->format('d F Y H:i') : '-' }}</div>
                                </div>
                            </div>
                            @if($pinjaman->catatan_bendahara)
                            <div class="mt-3">
                                <small class="text-muted">Catatan</small>
                                <div>{{ $pinjaman->catatan_bendahara }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($pinjaman->status == 'berjalan' || $pinjaman->status == 'lunas')
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3">Jadwal Angsuran</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Ke</th>
                                            <th>Jatuh Tempo</th>
                                            <th class="text-end">Nominal</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pinjaman->angsurans as $angsuran)
                                        <tr>
                                            <td>{{ $angsuran->angsuran_ke }}</td>
                                            <td>{{ $angsuran->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                                            <td class="text-end">Rp {{ number_format($angsuran->nominal_angsuran, 0, ',', '.') }}</td>
                                            <td>{{ $angsuran->tanggal_bayar ? $angsuran->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if($angsuran->status == 'lunas')
                                                <span class="badge bg-success">Lunas</span>
                                                @elseif($angsuran->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @elseif($angsuran->status == 'terlambat')
                                                <span class="badge bg-danger">Terlambat</span>
                                                @else
                                                <span class="badge bg-secondary">Belum Bayar</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">Jadwal angsuran belum dibuat</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    @if($pinjaman->dokumen_pendukung)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-3">Dokumen Pendukung</h5>
                            <a href="{{ Storage::url($pinjaman->dokumen_pendukung) }}" target="_blank" class="btn btn-primary w-100">Download</a>
                        </div>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3">Timeline</h5>
                            <div class="small">
                                <div class="mb-3 pb-2 border-bottom">
                                    <div class="text-muted">{{ $pinjaman->tanggal_pengajuan->format('d/m/Y') }}</div>
                                    <div>Pengajuan pinjaman</div>
                                </div>
                                @if($pinjaman->reviewed_at)
                                <div class="mb-3 pb-2 border-bottom">
                                    <div class="text-muted">{{ $pinjaman->reviewed_at->format('d/m/Y') }}</div>
                                    <div>{{ in_array($pinjaman->status, ['rejected_pengurus']) ? 'Ditolak' : 'Direview' }} oleh {{ $pinjaman->reviewedBy->name ?? '-' }}</div>
                                </div>
                                @endif
                                @if($pinjaman->approved_at)
                                <div class="mb-3 pb-2 border-bottom">
                                    <div class="text-muted">{{ $pinjaman->approved_at->format('d/m/Y') }}</div>
                                    <div>{{ in_array($pinjaman->status, ['rejected_bendahara']) ? 'Ditolak' : 'Diapprove' }} oleh {{ $pinjaman->approvedBy->name ?? '-' }}</div>
                                </div>
                                @endif
                                @if($pinjaman->tanggal_pencairan)
                                <div>
                                    <div class="text-muted">{{ $pinjaman->tanggal_pencairan->format('d/m/Y') }}</div>
                                    <div>Pinjaman dicairkan</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
