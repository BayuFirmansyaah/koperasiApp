<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Pengajuan Pinjaman</h2>
            <a href="{{ route('pinjaman.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-lg">
            <div class="row">
                <div class="col-lg-8">
                    <form action="{{ route('pinjaman.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">Informasi Pinjaman</h5>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">Nominal Pinjaman</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="nominal" class="form-control @error('nominal') is-invalid @enderror" placeholder="0" value="{{ old('nominal') }}" min="500000" max="50000000" step="100000" required>
                                        </div>
                                        <small class="text-muted">Min Rp 500.000 - Max Rp 50.000.000</small>
                                        @error('nominal')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Lama Pinjaman</label>
                                        <div class="input-group">
                                            <input type="number" name="lama_pinjaman" class="form-control @error('lama_pinjaman') is-invalid @enderror" placeholder="12" value="{{ old('lama_pinjaman') }}" min="3" max="36" required>
                                            <span class="input-group-text">Bulan</span>
                                        </div>
                                        <small class="text-muted">Min 3 - Max 36 bulan</small>
                                        @error('lama_pinjaman')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Tujuan Pinjaman</label>
                                    <textarea name="tujuan_pinjaman" rows="4" class="form-control @error('tujuan_pinjaman') is-invalid @enderror" placeholder="Jelaskan tujuan penggunaan pinjaman..." required>{{ old('tujuan_pinjaman') }}</textarea>
                                    @error('tujuan_pinjaman')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Dokumen Pendukung</label>
                                    <input type="file" name="dokumen_pendukung" class="form-control @error('dokumen_pendukung') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">PDF, JPG, PNG. Max 5MB</small>
                                    @error('dokumen_pendukung')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary">Ajukan Pinjaman</button>
                                    <a href="{{ route('pinjaman.index') }}" class="btn btn-outline-secondary">Batal</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-3">Syarat & Ketentuan</h5>
                            <ul class="small mb-0">
                                <li>Anggota aktif minimal 3 bulan</li>
                                <li>Tidak ada pinjaman aktif</li>
                                <li>Tidak ada tunggakan</li>
                                <li>Nominal Rp 500rb - 50jt</li>
                                <li>Tenor 3 - 36 bulan</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3">Simulasi Angsuran</h5>
                            <div id="simulasi" class="text-muted small text-center py-3">
                                Isi nominal dan tenor untuk melihat simulasi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nominalInput = document.querySelector('input[name="nominal"]');
            const tenorInput = document.querySelector('input[name="lama_pinjaman"]');
            const simulasiDiv = document.getElementById('simulasi');

            function hitungSimulasi() {
                const nominal = parseFloat(nominalInput.value) || 0;
                const tenor = parseInt(tenorInput.value) || 0;

                if (nominal > 0 && tenor > 0) {
                    const bunga = 1.5;
                    const totalBunga = (nominal * bunga / 100) * tenor;
                    const totalPinjaman = nominal + totalBunga;
                    const angsuranPerBulan = totalPinjaman / tenor;

                    simulasiDiv.innerHTML = `
                        <div class="text-start small">
                            <div class="d-flex justify-content-between mb-2"><span>Pokok Pinjaman:</span> <strong>Rp ${nominal.toLocaleString('id-ID')}</strong></div>
                            <div class="d-flex justify-content-between mb-2"><span>Bunga (${bunga}%):</span> <strong>Rp ${totalBunga.toLocaleString('id-ID')}</strong></div>
                            <div class="d-flex justify-content-between mb-2 border-top pt-2"><span>Total Pinjaman:</span> <strong>Rp ${totalPinjaman.toLocaleString('id-ID')}</strong></div>
                            <div class="d-flex justify-content-between text-primary"><span>Angsuran/Bulan:</span> <strong>Rp ${angsuranPerBulan.toLocaleString('id-ID')}</strong></div>
                        </div>
                    `;
                } else {
                    simulasiDiv.innerHTML = '<div class="text-muted small text-center py-3">Isi nominal dan tenor untuk melihat simulasi</div>';
                }
            }

            nominalInput.addEventListener('input', hitungSimulasi);
            tenorInput.addEventListener('input', hitungSimulasi);
        });
    </script>
    @endpush
</x-app-layout>
