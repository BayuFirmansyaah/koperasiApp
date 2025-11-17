<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Bayar Angsuran
        </h2>
    </x-slot>

    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="{{ route('angsuran.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Form Pembayaran Angsuran</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required">Pinjaman</label>
                                    <select class="form-select @error('pinjaman_id') is-invalid @enderror" name="pinjaman_id" required>
                                        <option value="">Pilih Pinjaman</option>
                                        @foreach($pinjamans as $pinjaman)
                                        <option value="{{ $pinjaman->id }}">{{ $pinjaman->no_pinjaman }} - Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }}</option>
                                        @endforeach
                                    </select>
                                    @error('pinjaman_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Nominal Pembayaran</label>
                                    <input type="number" class="form-control @error('nominal') is-invalid @enderror" name="nominal" required>
                                    @error('nominal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Tanggal Bayar</label>
                                    <input type="date" class="form-control @error('tanggal_bayar') is-invalid @enderror" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required>
                                    @error('tanggal_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Metode Pembayaran</label>
                                    <select class="form-select @error('metode_pembayaran') is-invalid @enderror" name="metode_pembayaran" required>
                                        <option value="tunai">Tunai</option>
                                        <option value="transfer">Transfer Bank</option>
                                    </select>
                                    @error('metode_pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Bukti Transfer (jika transfer)</label>
                                    <input type="file" class="form-control @error('bukti_transfer') is-invalid @enderror" name="bukti_transfer" accept="image/*">
                                    @error('bukti_transfer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" rows="3"></textarea>
                                    @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('angsuran.index') }}" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
