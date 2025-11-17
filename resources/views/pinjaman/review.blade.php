<x-app-layout>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Review Pinjaman
                    </h2>
                    <div class="text-muted mt-1">Review pengajuan pinjaman dari anggota</div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                    </div>
                    <div>{{ session('success') }}</div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            <div class="row row-cards">
                @forelse($pinjamans as $pinjaman)
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $pinjaman->no_pinjaman }}</h3>
                            <div class="card-actions">
                                <span class="badge bg-warning">Pending</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <span class="avatar me-2">{{ substr($pinjaman->anggota->user->name, 0, 2) }}</span>
                                    <div>
                                        <div><strong>{{ $pinjaman->anggota->user->name }}</strong></div>
                                        <div class="text-muted small">{{ $pinjaman->anggota->no_anggota }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Tanggal Pengajuan</div>
                                    <div class="datagrid-content">{{ $pinjaman->tanggal_pengajuan->format('d/m/Y') }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Nominal</div>
                                    <div class="datagrid-content">
                                        <strong class="text-primary">Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Tenor</div>
                                    <div class="datagrid-content">{{ $pinjaman->lama_pinjaman }} bulan</div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Tujuan Pinjaman:</label>
                                <p class="text-muted small">{{ Str::limit($pinjaman->tujuan_pinjaman, 100) }}</p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-list w-100">
                                <a href="{{ route('pinjaman.show', $pinjaman) }}" class="btn btn-sm w-100" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                                    Detail
                                </a>
                                <button type="button" class="btn btn-sm btn-success w-100" data-bs-toggle="modal" data-bs-target="#approveModal{{ $pinjaman->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                    Approve
                                </button>
                                <button type="button" class="btn btn-sm btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $pinjaman->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Approve Modal -->
                    <div class="modal modal-blur fade" id="approveModal{{ $pinjaman->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('pinjaman.doReview', $pinjaman) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Approve Pengajuan Pinjaman</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Anda akan meng-approve pengajuan pinjaman:</p>
                                        <div class="alert alert-info">
                                            <div><strong>{{ $pinjaman->anggota->user->name }}</strong></div>
                                            <div>{{ $pinjaman->no_pinjaman }}</div>
                                            <div class="h3 mb-0">Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</div>
                                            <div class="small">{{ $pinjaman->lama_pinjaman }} bulan</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Catatan (opsional)</label>
                                            <textarea name="catatan_pengurus" rows="3" class="form-control" placeholder="Catatan untuk bendahara..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Approve</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Reject Modal -->
                    <div class="modal modal-blur fade" id="rejectModal{{ $pinjaman->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('pinjaman.doReview', $pinjaman) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tolak Pengajuan Pinjaman</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Anda akan menolak pengajuan pinjaman:</p>
                                        <div class="alert alert-warning">
                                            <div><strong>{{ $pinjaman->anggota->user->name }}</strong></div>
                                            <div>{{ $pinjaman->no_pinjaman }}</div>
                                            <div class="h3 mb-0">Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label required">Alasan Penolakan</label>
                                            <textarea name="catatan_pengurus" rows="3" class="form-control" placeholder="Jelaskan alasan penolakan..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5 text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" /></svg>
                            <div>Tidak ada pinjaman yang perlu direview</div>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            @if($pinjamans->hasPages())
            <div class="mt-3">
                {{ $pinjamans->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
