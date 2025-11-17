<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Pencairan Pinjaman
        </h2>
    </x-slot>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pinjaman Siap Dicairkan</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>No. Pinjaman</th>
                                            <th>Anggota</th>
                                            <th>Nominal</th>
                                            <th>Lama</th>
                                            <th>Disetujui</th>
                                            <th class="w-1">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pinjamans as $pinjaman)
                                        <tr>
                                            <td>{{ $pinjaman->no_pinjaman }}</td>
                                            <td>{{ $pinjaman->anggota->user->name }}</td>
                                            <td>Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</td>
                                            <td>{{ $pinjaman->lama_pinjaman }} bulan</td>
                                            <td>{{ formatDate($pinjaman->tanggal_approve) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#disburseModal{{ $pinjaman->id }}">
                                                    Cairkan
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada pinjaman yang siap dicairkan.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{ $pinjamans->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals for each pinjaman -->
    @foreach($pinjamans as $pinjaman)
    <div class="modal fade" id="disburseModal{{ $pinjaman->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('pinjaman.doDisburse', $pinjaman) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Pencairan Pinjaman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Pencairan</label>
                            <input type="date" class="form-control" name="tanggal_pencairan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Metode Pencairan</label>
                            <select class="form-select" name="metode_pencairan" required>
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Rekening (jika transfer)</label>
                            <input type="text" class="form-control" name="nomor_rekening">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="catatan_pencairan" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Cairkan Pinjaman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</x-app-layout>
