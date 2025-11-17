<x-app-layout>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Pengajuan Pinjaman
                    </h2>
                    <div class="text-muted mt-1">Ajukan pinjaman baru</div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('pinjaman.index') }}" class="btn">
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
                    <form action="{{ route('pinjaman.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Form Pengajuan Pinjaman</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Nominal Pinjaman</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" name="nominal" class="form-control @error('nominal') is-invalid @enderror" placeholder="0" value="{{ old('nominal') }}" min="500000" max="50000000" step="100000">
                                            </div>
                                            <small class="form-hint">Minimum Rp 500.000 - Maximum Rp 50.000.000</small>
                                            @error('nominal')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Lama Pinjaman</label>
                                            <div class="input-group">
                                                <input type="number" name="lama_pinjaman" class="form-control @error('lama_pinjaman') is-invalid @enderror" placeholder="12" value="{{ old('lama_pinjaman') }}" min="3" max="36">
                                                <span class="input-group-text">Bulan</span>
                                            </div>
                                            <small class="form-hint">Minimum 3 bulan - Maximum 36 bulan</small>
                                            @error('lama_pinjaman')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Tujuan Pinjaman</label>
                                    <textarea name="tujuan_pinjaman" rows="4" class="form-control @error('tujuan_pinjaman') is-invalid @enderror" placeholder="Jelaskan tujuan penggunaan pinjaman...">{{ old('tujuan_pinjaman') }}</textarea>
                                    @error('tujuan_pinjaman')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Dokumen Pendukung (opsional)</label>
                                    <input type="file" name="dokumen_pendukung" class="form-control @error('dokumen_pendukung') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-hint">Upload dokumen pendukung seperti slip gaji, BPKB, dll. Max 5MB.</small>
                                    @error('dokumen_pendukung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('pinjaman.index') }}" class="btn btn-link">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                    Ajukan Pinjaman
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
                                <p><strong>Syarat & Ketentuan:</strong></p>
                                <ul class="mb-3">
                                    <li>Anggota aktif minimal 3 bulan</li>
                                    <li>Tidak memiliki pinjaman aktif</li>
                                    <li>Tidak ada tunggakan angsuran</li>
                                    <li>Nominal pinjaman Rp 500rb - 50jt</li>
                                    <li>Tenor pinjaman 3 - 36 bulan</li>
                                </ul>
                                <p><strong>Proses Persetujuan:</strong></p>
                                <ol class="mb-0">
                                    <li>Pengajuan oleh anggota</li>
                                    <li>Review oleh pengurus</li>
                                    <li>Approval oleh bendahara</li>
                                    <li>Pencairan dana</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Simulasi Angsuran</h3>
                        </div>
                        <div class="card-body">
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
                    const bunga = 1.5; // 1.5% per bulan
                    const totalBunga = (nominal * bunga / 100) * tenor;
                    const totalPinjaman = nominal + totalBunga;
                    const angsuranPerBulan = totalPinjaman / tenor;

                    simulasiDiv.innerHTML = `
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Pokok Pinjaman</div>
                                <div class="datagrid-content">Rp ${nominal.toLocaleString('id-ID')}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Bunga (${bunga}%)</div>
                                <div class="datagrid-content">Rp ${totalBunga.toLocaleString('id-ID')}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Total Pinjaman</div>
                                <div class="datagrid-content"><strong>Rp ${totalPinjaman.toLocaleString('id-ID')}</strong></div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Angsuran/Bulan</div>
                                <div class="datagrid-content"><strong class="text-primary">Rp ${angsuranPerBulan.toLocaleString('id-ID')}</strong></div>
                            </div>
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
