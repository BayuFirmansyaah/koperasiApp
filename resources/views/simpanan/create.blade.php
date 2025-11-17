<x-app-layout>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Tambah Simpanan
                    </h2>
                    <div class="text-muted mt-1">Catat transaksi simpanan anggota</div>
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
                    <form action="{{ route('simpanan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Simpanan</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required">Anggota</label>
                                    <select name="anggota_id" class="form-select @error('anggota_id') is-invalid @enderror">
                                        <option value="">Pilih Anggota</option>
                                        @foreach($anggotas as $anggota)
                                        <option value="{{ $anggota->id }}" {{ old('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                            {{ $anggota->no_anggota }} - {{ $anggota->user->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('anggota_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Jenis Simpanan</label>
                                    <select name="jenis_simpanan_id" class="form-select @error('jenis_simpanan_id') is-invalid @enderror">
                                        <option value="">Pilih Jenis Simpanan</option>
                                        @foreach($jenisSimpanans as $jenis)
                                        <option value="{{ $jenis->id }}" {{ old('jenis_simpanan_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama_simpanan }} - {{ $jenis->deskripsi }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_simpanan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Nominal</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" name="nominal" class="form-control @error('nominal') is-invalid @enderror" placeholder="0" value="{{ old('nominal') }}" min="1000" step="1000">
                                            </div>
                                            @error('nominal')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Tanggal Simpanan</label>
                                            <input type="date" name="tanggal_transaksi" class="form-control @error('tanggal_transaksi') is-invalid @enderror" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}">
                                            @error('tanggal_transaksi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Metode Pembayaran</label>
                                    <div class="form-selectgroup">
                                        <label class="form-selectgroup-item">
                                            <input type="radio" name="metode_pembayaran" value="tunai" class="form-selectgroup-input" {{ old('metode_pembayaran', 'tunai') == 'tunai' ? 'checked' : '' }}>
                                            <span class="form-selectgroup-label">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="7" y="9" width="14" height="10" rx="2" /><circle cx="14" cy="14" r="2" /><path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" /></svg>
                                                Tunai
                                            </span>
                                        </label>
                                        <label class="form-selectgroup-item">
                                            <input type="radio" name="metode_pembayaran" value="transfer" class="form-selectgroup-input" {{ old('metode_pembayaran') == 'transfer' ? 'checked' : '' }}>
                                            <span class="form-selectgroup-label">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="3" /><line x1="3" y1="10" x2="21" y2="10" /><line x1="7" y1="15" x2="7.01" y2="15" /><line x1="11" y1="15" x2="13" y2="15" /></svg>
                                                Transfer
                                            </span>
                                        </label>
                                    </div>
                                    @error('metode_pembayaran')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Bukti Transfer (opsional)</label>
                                    <input type="file" name="bukti_transfer" class="form-control @error('bukti_transfer') is-invalid @enderror" accept="image/*">
                                    <small class="form-hint">Upload bukti transfer jika metode pembayaran transfer. Max 2MB.</small>
                                    @error('bukti_transfer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('simpanan.index') }}" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informasi</h3>
                        </div>
                        <div class="card-body">
                            <div class="text-muted small">
                                <p><strong>Catatan:</strong></p>
                                <ul class="mb-0">
                                    <li>Transaksi simpanan akan menunggu verifikasi dari bendahara</li>
                                    <li>Nominal minimal simpanan adalah Rp 1.000</li>
                                    <li>Jika metode transfer, disarankan untuk mengunggah bukti transfer</li>
                                    <li>Pastikan data anggota dan nominal sudah benar sebelum menyimpan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
