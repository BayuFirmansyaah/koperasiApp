<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight" style="color: #1e293b;">
            Detail Transaksi Kas
        </h2>
    </x-slot>

    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Transaksi</h3>
                            <div class="card-actions">
                                <a href="{{ route('kas.index') }}" class="btn btn-outline-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                                    Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Tipe Transaksi</label>
                                        <div>
                                            <span class="badge bg-{{ $ka->tipe === 'masuk' ? 'green' : 'red' }}">
                                                {{ ucfirst($ka->tipe) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kategori</label>
                                        <div>{{ $ka->kategori }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Tanggal Transaksi</label>
                                        <div>{{ formatDateTimeLong($ka->tanggal_transaksi) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nominal</label>
                                        <div class="h2 {{ $ka->tipe === 'masuk' ? 'text-green' : 'text-red' }}">
                                            {{ $ka->tipe === 'masuk' ? '+' : '-' }} Rp {{ number_format($ka->nominal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Saldo Sebelum</label>
                                        <div>Rp {{ number_format($ka->saldo_sebelum, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Saldo Sesudah</label>
                                        <div>Rp {{ number_format($ka->saldo_sesudah, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Keterangan</label>
                                <div>{{ $ka->keterangan }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Dicatat Oleh</label>
                                <div>{{ $ka->user?->name ?? 'N/A' }}</div>
                            </div>

                            @if($ka->bukti_transaksi)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Bukti Transaksi</label>
                                <div>
                                    <a href="{{ Storage::url($ka->bukti_transaksi) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
                                        Lihat Bukti
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
