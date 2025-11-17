<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Laporan Keuangan
        </h2>
    </x-slot>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Keuangan Koperasi</h3>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Bulan</label>
                                <select class="form-select" name="month">
                                    @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tahun</label>
                                <select class="form-select" name="year">
                                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                            </div>
                        </div>
                    </form>

                    <div class="row row-cards mb-4">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h3 class="card-title text-white">Total Simpanan</h3>
                                    <div class="h3">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-blue text-white">
                                <div class="card-body">
                                    <h3 class="card-title text-white">Total Angsuran</h3>
                                    <div class="h3">Rp {{ number_format($totalAngsuran, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h3 class="card-title text-white">Total Pencairan</h3>
                                    <div class="h3">Rp {{ number_format($totalPencairan, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h3 class="card-title text-white">Saldo Kas</h3>
                                    <div class="h3">Rp {{ number_format($saldoAkhir->saldo_sesudah ?? 0, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Pemasukan</h3>
                                </div>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex align-items-center justify-content-between">
                                        <div>Simpanan</div>
                                        <div class="fw-bold">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center justify-content-between">
                                        <div>Angsuran Pinjaman</div>
                                        <div class="fw-bold">Rp {{ number_format($totalAngsuran, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center justify-content-between">
                                        <div>Kas Masuk Lain</div>
                                        <div class="fw-bold">Rp {{ number_format($kasMasuk, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center justify-content-between bg-success text-white">
                                        <div class="fw-bold">Total Pemasukan</div>
                                        <div class="fw-bold">Rp {{ number_format($totalSimpanan + $totalAngsuran + $kasMasuk, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Pengeluaran</h3>
                                </div>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex align-items-center justify-content-between">
                                        <div>Pencairan Pinjaman</div>
                                        <div class="fw-bold">Rp {{ number_format($totalPencairan, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center justify-content-between">
                                        <div>Kas Keluar Lain</div>
                                        <div class="fw-bold">Rp {{ number_format($kasKeluar, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="list-group-item d-flex align-items-center justify-content-between bg-danger text-white">
                                        <div class="fw-bold">Total Pengeluaran</div>
                                        <div class="fw-bold">Rp {{ number_format($totalPencairan + $kasKeluar, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
