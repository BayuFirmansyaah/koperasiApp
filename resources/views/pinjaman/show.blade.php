<x-app-layout>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Detail Pinjaman
                    </h2>
                    <div class="text-muted mt-1">{{ $pinjaman->no_pinjaman }}</div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('pinjaman.index') }}" class="btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Pinjaman</h3>
                            <div class="card-actions">
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
                        </div>
                        <div class="card-body">
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">No. Pinjaman</div>
                                    <div class="datagrid-content"><strong>{{ $pinjaman->no_pinjaman }}</strong></div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Tanggal Pengajuan</div>
                                    <div class="datagrid-content">{{ $pinjaman->tanggal_pengajuan->format('d F Y') }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">No. Anggota</div>
                                    <div class="datagrid-content">{{ $pinjaman->anggota->no_anggota }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Nama Anggota</div>
                                    <div class="datagrid-content">{{ $pinjaman->anggota->user->name }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Nominal Pinjaman</div>
                                    <div class="datagrid-content">
                                        <strong class="text-primary h3 mb-0">Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Lama Pinjaman</div>
                                    <div class="datagrid-content">{{ $pinjaman->lama_pinjaman }} bulan</div>
                                </div>
                                @if($pinjaman->sisa_pinjaman)
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Sisa Pinjaman</div>
                                    <div class="datagrid-content">
                                        <strong class="text-danger">Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                                @endif
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Tujuan Pinjaman</div>
                                    <div class="datagrid-content">{{ $pinjaman->tujuan_pinjaman }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($pinjaman->catatan_pengurus || $pinjaman->reviewedBy)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Review Pengurus</h3>
                        </div>
                        <div class="card-body">
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Direview Oleh</div>
                                    <div class="datagrid-content">{{ $pinjaman->reviewedBy->name ?? '-' }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Tanggal Review</div>
                                    <div class="datagrid-content">{{ $pinjaman->reviewed_at ? $pinjaman->reviewed_at->format('d F Y H:i') : '-' }}</div>
                                </div>
                                @if($pinjaman->catatan_pengurus)
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Catatan</div>
                                    <div class="datagrid-content">{{ $pinjaman->catatan_pengurus }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pinjaman->catatan_bendahara || $pinjaman->approvedBy)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Approval Bendahara</h3>
                        </div>
                        <div class="card-body">
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Diapprove Oleh</div>
                                    <div class="datagrid-content">{{ $pinjaman->approvedBy->name ?? '-' }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Tanggal Approval</div>
                                    <div class="datagrid-content">{{ $pinjaman->approved_at ? $pinjaman->approved_at->format('d F Y H:i') : '-' }}</div>
                                </div>
                                @if($pinjaman->catatan_bendahara)
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Catatan</div>
                                    <div class="datagrid-content">{{ $pinjaman->catatan_bendahara }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pinjaman->status == 'berjalan' || $pinjaman->status == 'lunas')
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Jadwal Angsuran</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>Angsuran Ke</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Nominal</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pinjaman->angsurans as $angsuran)
                                    <tr>
                                        <td>{{ $angsuran->angsuran_ke }}</td>
                                        <td>{{ $angsuran->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                                        <td>Rp {{ number_format($angsuran->nominal_angsuran, 0, ',', '.') }}</td>
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
                                        <td colspan="5" class="text-center text-muted">Jadwal angsuran belum dibuat</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-md-4">
                    @if($pinjaman->dokumen_pendukung)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Dokumen Pendukung</h3>
                        </div>
                        <div class="card-body">
                            <a href="{{ Storage::url($pinjaman->dokumen_pendukung) }}" target="_blank" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
                                Download Dokumen
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Timeline</h3>
                        </div>
                        <div class="card-body">
                            <ul class="timeline">
                                <li class="timeline-event">
                                    <div class="timeline-event-icon bg-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                    </div>
                                    <div class="card timeline-event-card">
                                        <div class="card-body">
                                            <div class="text-muted small">{{ $pinjaman->tanggal_pengajuan->format('d/m/Y H:i') }}</div>
                                            <div>Pengajuan pinjaman</div>
                                        </div>
                                    </div>
                                </li>
                                @if($pinjaman->reviewed_at)
                                <li class="timeline-event">
                                    <div class="timeline-event-icon bg-{{ in_array($pinjaman->status, ['rejected_pengurus']) ? 'danger' : 'info' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                    </div>
                                    <div class="card timeline-event-card">
                                        <div class="card-body">
                                            <div class="text-muted small">{{ $pinjaman->reviewed_at->format('d/m/Y H:i') }}</div>
                                            <div>{{ in_array($pinjaman->status, ['rejected_pengurus']) ? 'Ditolak' : 'Direview' }} oleh {{ $pinjaman->reviewedBy->name }}</div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($pinjaman->approved_at)
                                <li class="timeline-event">
                                    <div class="timeline-event-icon bg-{{ in_array($pinjaman->status, ['rejected_bendahara']) ? 'danger' : 'success' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                    </div>
                                    <div class="card timeline-event-card">
                                        <div class="card-body">
                                            <div class="text-muted small">{{ $pinjaman->approved_at->format('d/m/Y H:i') }}</div>
                                            <div>{{ in_array($pinjaman->status, ['rejected_bendahara']) ? 'Ditolak' : 'Diapprove' }} oleh {{ $pinjaman->approvedBy->name }}</div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($pinjaman->tanggal_pencairan)
                                <li class="timeline-event">
                                    <div class="timeline-event-icon bg-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" /></svg>
                                    </div>
                                    <div class="card timeline-event-card">
                                        <div class="card-body">
                                            <div class="text-muted small">{{ $pinjaman->tanggal_pencairan->format('d/m/Y') }}</div>
                                            <div>Pinjaman dicairkan</div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
