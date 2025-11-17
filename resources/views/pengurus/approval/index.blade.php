<x-app-layout>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Persetujuan Anggota
                    </h2>
                    <div class="text-muted mt-1">Daftar anggota yang menunggu persetujuan</div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-body">
                    @if($pendingAnggotas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIK</th>
                                    <th>Email</th>
                                    <th>No. Telepon</th>
                                    <th>Pekerjaan</th>
                                    <th>Tanggal Daftar</th>
                                    <th class="w-1">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingAnggotas as $anggota)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($anggota->foto)
                                            <span class="avatar me-2" style="background-image: url({{ Storage::url($anggota->foto) }})"></span>
                                            @else
                                            <span class="avatar me-2">{{ substr($anggota->user->name, 0, 2) }}</span>
                                            @endif
                                            <div>{{ $anggota->user->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $anggota->nik }}</td>
                                    <td>{{ $anggota->user->email }}</td>
                                    <td>{{ $anggota->no_telepon }}</td>
                                    <td>{{ $anggota->pekerjaan }}</td>
                                    <td>{{ formatDateTime($anggota->created_at) }}</td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <a href="{{ route('anggota.show', $anggota) }}" class="btn btn-sm btn-ghost-secondary" title="Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2" /><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" /></svg>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $anggota->id }}" title="Setujui">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $anggota->id }}" title="Tolak">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Approve Modal -->
                                <div class="modal modal-blur fade" id="approveModal{{ $anggota->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="modal-title">Setujui Anggota?</div>
                                                <div>Anda yakin ingin menyetujui <strong>{{ $anggota->user->name }}</strong> sebagai anggota koperasi?</div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('pengurus.approval.approve', $anggota) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Setujui</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal modal-blur fade" id="rejectModal{{ $anggota->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('pengurus.approval.reject', $anggota) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Anggota</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Alasan Penolakan</label>
                                                        <textarea name="keterangan" class="form-control" rows="3" required placeholder="Masukkan alasan penolakan..."></textarea>
                                                    </div>
                                                    <div class="text-muted">
                                                        Anda akan menolak pendaftaran <strong>{{ $anggota->user->name }}</strong>. Pastikan memberikan alasan yang jelas.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Tolak</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($pendingAnggotas->hasPages())
                    <div class="mt-3">
                        {{ $pendingAnggotas->links() }}
                    </div>
                    @endif
                    @else
                    <div class="empty">
                        <div class="empty-img">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15a3.5 3.5 0 0 0 5 0" /></svg>
                        </div>
                        <p class="empty-title">Tidak ada anggota yang menunggu persetujuan</p>
                        <p class="empty-subtitle text-muted">
                            Semua pendaftaran anggota telah diproses
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
