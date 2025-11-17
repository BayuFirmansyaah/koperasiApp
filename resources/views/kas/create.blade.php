<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Tambah Transaksi Kas
        </h2>
    </x-slot>

    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="{{ route('kas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Form Transaksi Kas</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required">Tipe Transaksi</label>
                                    <select class="form-select @error('tipe') is-invalid @enderror" name="tipe" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="masuk">Kas Masuk</option>
                                        <option value="keluar">Kas Keluar</option>
                                    </select>
                                    @error('tipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Kategori</label>
                                    <input type="text" class="form-control @error('kategori') is-invalid @enderror" name="kategori" placeholder="Contoh: Iuran, Operasional, dll" required>
                                    @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Nominal</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('nominal') is-invalid @enderror" name="nominal" required>
                                    </div>
                                    @error('nominal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Tanggal Transaksi</label>
                                    <input type="date" class="form-control @error('tanggal_transaksi') is-invalid @enderror" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" required>
                                    @error('tanggal_transaksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" rows="3" required></textarea>
                                    @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Bukti Transaksi</label>
                                    <input type="file" class="form-control @error('bukti_transaksi') is-invalid @enderror" name="bukti_transaksi" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-hint">Format: PDF, JPG, PNG. Max 2MB</small>
                                    @error('bukti_transaksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('kas.index') }}" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
