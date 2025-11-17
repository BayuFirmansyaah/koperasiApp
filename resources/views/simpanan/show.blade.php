<x-app-layout>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Detail Simpanan
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('simpanan.index') }}" class="btn">
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
                            <h3 class="card-title">Informasi Simpanan</h3>
                            <div class="card-actions">
                                @if($simpanan->status == 'verified')
                                <span class="badge bg-success">Verified</span>
                                @elseif($simpanan->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                                @else
                                <span class="badge bg-danger">Rejected</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Tanggal Simpanan</div>
                                    <div class="datagrid-content">{{ formatDateLong($simpanan->tanggal_transaksi) }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">No. Anggota</div>
                                    <div class="datagrid-content">{{ $simpanan->anggota->no_anggota }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Nama Anggota</div>
                                    <div class="datagrid-content">
                                        <div class="d-flex align-items-center">
                                            @if($simpanan->anggota->foto)
                                            <span class="avatar me-2" style="background-image: url({{ Storage::url($simpanan->anggota->foto) }})"></span>
                                            @else
                                            <span class="avatar me-2">{{ substr($simpanan->anggota->user->name, 0, 2) }}</span>
                                            @endif
                                            <div>{{ $simpanan->anggota->user->name }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Jenis Simpanan</div>
                                    <div class="datagrid-content">
                                        <strong>{{ $simpanan->jenisSimpanan->nama_simpanan }}</strong>
                                        <div class="text-muted small">{{ $simpanan->jenisSimpanan->deskripsi }}</div>
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Nominal</div>
                                    <div class="datagrid-content">
                                        <strong class="text-primary h3 mb-0">Rp {{ number_format($simpanan->nominal, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Metode Pembayaran</div>
                                    <div class="datagrid-content">
                                        <span class="badge bg-{{ $simpanan->metode_pembayaran == 'tunai' ? 'info' : 'primary' }}">
                                            {{ ucfirst($simpanan->metode_pembayaran) }}
                                        </span>
                                    </div>
                                </div>
                                @if($simpanan->keterangan)
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Keterangan</div>
                                    <div class="datagrid-content">{{ $simpanan->keterangan }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($simpanan->status != 'pending')
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Verifikasi</h3>
                        </div>
                        <div class="card-body">
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Diverifikasi Oleh</div>
                                    <div class="datagrid-content">{{ $simpanan->verifiedBy->name ?? '-' }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Tanggal Verifikasi</div>
                                    <div class="datagrid-content">{{ $simpanan->verified_at ? $simpanan->verified_at->format('d F Y H:i') : '-' }}</div>
                                </div>
                                @if($simpanan->keterangan_verifikasi)
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Keterangan Verifikasi</div>
                                    <div class="datagrid-content">{{ $simpanan->keterangan_verifikasi }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-md-4">
                    @if($simpanan->bukti_transfer)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Bukti Transfer</h3>
                        </div>
                        <div class="card-body p-0">
                            <img src="{{ Storage::url($simpanan->bukti_transfer) }}" class="img-fluid" alt="Bukti Transfer">
                        </div>
                        <div class="card-footer">
                            <a href="{{ Storage::url($simpanan->bukti_transfer) }}" target="_blank" class="btn btn-sm btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5" /><path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5" /></svg>
                                Lihat Full Size
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
                                            <div class="text-muted small">{{ formatDateTime($simpanan->created_at) }}</div>
                                            <div>Transaksi dicatat</div>
                                        </div>
                                    </div>
                                </li>
                                @if($simpanan->verified_at)
                                <li class="timeline-event">
                                    <div class="timeline-event-icon bg-{{ $simpanan->status == 'verified' ? 'success' : 'danger' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                    </div>
                                    <div class="card timeline-event-card">
                                        <div class="card-body">
                                            <div class="text-muted small">{{ formatDateTime($simpanan->verified_at) }}</div>
                                            <div>{{ $simpanan->status == 'verified' ? 'Diverifikasi' : 'Ditolak' }} oleh {{ $simpanan->verifiedBy->name }}</div>
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
