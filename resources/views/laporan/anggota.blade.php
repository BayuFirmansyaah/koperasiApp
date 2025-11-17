<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Laporan Anggota
        </h2>
    </x-slot>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Data Anggota</h3>
                    <div class="card-actions">
                        <form method="GET" class="d-flex gap-2">
                            <select class="form-select" name="status" onchange="this.form.submit()">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row row-cards mb-4">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="subheader">Total Anggota</div>
                                    <div class="h2">{{ $summary['total'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="subheader">Aktif</div>
                                    <div class="h2 text-green">{{ $summary['active'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="subheader">Pending</div>
                                    <div class="h2 text-yellow">{{ $summary['pending'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="subheader">Tidak Aktif</div>
                                    <div class="h2 text-red">{{ $summary['inactive'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>No. Anggota</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Total Simpanan</th>
                                    <th>Total Pinjaman</th>
                                    <th>Tgl Bergabung</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($anggotas as $anggota)
                                <tr>
                                    <td>{{ $anggota->no_anggota }}</td>
                                    <td>{{ $anggota->user->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $anggota->status === 'active' ? 'green' : ($anggota->status === 'pending' ? 'yellow' : 'red') }}">
                                            {{ ucfirst($anggota->status) }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($anggota->simpanans->where('status', 'verified')->sum('nominal'), 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($anggota->pinjamans->whereIn('status', ['berjalan', 'lunas'])->sum('nominal'), 0, ',', '.') }}</td>
                                    <td>{{ formatDate($anggota->created_at) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
