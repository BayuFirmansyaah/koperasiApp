<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Detail Angsuran
        </h2>
    </x-slot>

    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Angsuran</h3>
                            <div class="card-actions">
                                <a href="{{ route('angsuran.index') }}" class="btn btn-outline-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                                    Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">No. Pinjaman</label>
                                        <div>{{ $angsuran->pinjaman->no_pinjaman }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nama Anggota</label>
                                        <div>{{ $angsuran->pinjaman->anggota->user->name }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Angsuran Ke-</label>
                                        <div>{{ $angsuran->angsuran_ke }} dari {{ $angsuran->pinjaman->lama_pinjaman }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Tanggal Jatuh Tempo</label>
                                        <div>{{ formatDateLong($angsuran->tanggal_jatuh_tempo) }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nominal Angsuran</label>
                                        <div>Rp {{ number_format($angsuran->nominal, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status</label>
                                        <div>
                                            <span class="badge bg-{{ $angsuran->status === 'lunas' ? 'green' : ($angsuran->status === 'pending' ? 'yellow' : 'red') }}">
                                                {{ ucfirst($angsuran->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($angsuran->nominal_bayar)
                            <hr class="my-4">
                            <h4 class="card-title">Detail Pembayaran</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nominal Dibayar</label>
                                        <div>Rp {{ number_format($angsuran->nominal_bayar, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Tanggal Bayar</label>
                                        <div>{{ formatDateLong($angsuran->tanggal_bayar) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Metode Pembayaran</label>
                                        <div>{{ ucfirst($angsuran->metode_pembayaran) }}</div>
                                    </div>
                                    @if($angsuran->denda > 0)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Denda Keterlambatan</label>
                                        <div class="text-danger">Rp {{ number_format($angsuran->denda, 0, ',', '.') }}</div>
                                    </div>
                                    @endif
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
