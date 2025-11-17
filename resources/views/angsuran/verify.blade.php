<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Verifikasi Angsuran
        </h2>
    </x-slot>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Angsuran Menunggu Verifikasi</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>No. Pinjaman</th>
                                            <th>Anggota</th>
                                            <th>Angsuran Ke-</th>
                                            <th>Nominal</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Metode</th>
                                            <th class="w-1">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($angsurans as $angsuran)
                                        <tr>
                                            <td>{{ $angsuran->pinjaman->no_pinjaman }}</td>
                                            <td>{{ $angsuran->pinjaman->anggota->user->name }}</td>
                                            <td>{{ $angsuran->angsuran_ke }}</td>
                                            <td>Rp {{ number_format($angsuran->nominal_bayar, 0, ',', '.') }}</td>
                                            <td>{{ formatDate($angsuran->tanggal_bayar) }}</td>
                                            <td>{{ ucfirst($angsuran->metode_pembayaran) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#verifyModal{{ $angsuran->id }}">
                                                    Verifikasi
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Tidak ada angsuran yang perlu diverifikasi.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{ $angsurans->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @foreach($angsurans as $angsuran)
    <div class="modal fade" id="verifyModal{{ $angsuran->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('angsuran.doVerify', $angsuran) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Verifikasi Angsuran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Aksi</label>
                            <select class="form-select" name="action" required>
                                <option value="approve">Setujui</option>
                                <option value="reject">Tolak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan_verifikasi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</x-app-layout>
