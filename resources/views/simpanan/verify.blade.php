<x-app-layout>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Verifikasi Simpanan
                    </h2>
                    <div class="text-muted mt-1">Verifikasi transaksi simpanan yang pending</div>
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

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Anggota</th>
                                <th>Nama Anggota</th>
                                <th>Jenis Simpanan</th>
                                <th>Nominal</th>
                                <th>Metode</th>
                                <th>Bukti</th>
                                <th class="w-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($simpanans as $simpanan)
                            <tr>
                                <td>{{ formatDate($simpanan->tanggal_transaksi) }}</td>
                                <td>{{ $simpanan->anggota->no_anggota }}</td>
                                <td>{{ $simpanan->anggota->user->name }}</td>
                                <td>{{ $simpanan->jenisSimpanan->nama_simpanan }}</td>
                                <td class="text-end"><strong>Rp {{ number_format($simpanan->nominal, 0, ',', '.') }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $simpanan->metode_pembayaran == 'tunai' ? 'info' : 'primary' }}">
                                        {{ ucfirst($simpanan->metode_pembayaran) }}
                                    </span>
                                </td>
                                <td>
                                    @if($simpanan->bukti_transfer)
                                    <a href="{{ Storage::url($simpanan->bukti_transfer) }}" target="_blank" class="btn btn-sm btn-icon btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                                    </a>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('simpanan.show', $simpanan) }}" class="btn btn-sm btn-icon btn-ghost-secondary" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-icon btn-success" data-bs-toggle="modal" data-bs-target="#verifyModal{{ $simpanan->id }}" title="Approve">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-icon btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $simpanan->id }}" title="Reject">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                                        </button>
                                    </div>

                                    <!-- Approve Modal -->
                                    <div class="modal modal-blur fade" id="verifyModal{{ $simpanan->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('simpanan.doVerify', $simpanan) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="approve">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Approve Simpanan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Anda akan meng-approve simpanan:</p>
                                                        <div class="alert alert-info">
                                                            <div><strong>{{ $simpanan->anggota->user->name }}</strong></div>
                                                            <div>{{ $simpanan->jenisSimpanan->nama_simpanan }}</div>
                                                            <div class="h3 mb-0">Rp {{ number_format($simpanan->nominal, 0, ',', '.') }}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Keterangan (opsional)</label>
                                                            <textarea name="keterangan_verifikasi" rows="3" class="form-control" placeholder="Keterangan verifikasi..."></textarea>
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
                                    <div class="modal modal-blur fade" id="rejectModal{{ $simpanan->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('simpanan.doVerify', $simpanan) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Simpanan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Anda akan menolak simpanan:</p>
                                                        <div class="alert alert-warning">
                                                            <div><strong>{{ $simpanan->anggota->user->name }}</strong></div>
                                                            <div>{{ $simpanan->jenisSimpanan->nama_simpanan }}</div>
                                                            <div class="h3 mb-0">Rp {{ number_format($simpanan->nominal, 0, ',', '.') }}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label required">Alasan Penolakan</label>
                                                            <textarea name="keterangan_verifikasi" rows="3" class="form-control" placeholder="Jelaskan alasan penolakan..." required></textarea>
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
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" /></svg>
                                    <div>Tidak ada simpanan yang perlu diverifikasi</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($simpanans->hasPages())
                <div class="card-footer">
                    {{ $simpanans->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
