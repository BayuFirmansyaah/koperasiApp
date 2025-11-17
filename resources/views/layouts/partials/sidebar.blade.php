{{-- Dashboard --}}
<x-sidebar-item route="dashboard">
    <x-slot:icon>
        <x-icon name="dashboard" />
    </x-slot:icon>
    Dashboard
</x-sidebar-item>

{{-- Anggota Management --}}
<x-sidebar-dropdown 
    label="Anggota" 
    id="menu-anggota"
    :routes="['anggota*', 'pengurus/approval*']">
    <x-slot:icon>
        <x-icon name="users" />
    </x-slot:icon>
    
    <x-sidebar-subitem route="anggota.index">
        Daftar Anggota
    </x-sidebar-subitem>
    <x-sidebar-subitem route="pengurus.approval.index">
        Persetujuan Anggota
    </x-sidebar-subitem>
</x-sidebar-dropdown>

{{-- Simpanan --}}
<x-sidebar-dropdown 
    label="Simpanan" 
    id="menu-simpanan"
    :routes="['simpanan*']">
    <x-slot:icon>
        <x-icon name="wallet" />
    </x-slot:icon>
    
    <x-sidebar-subitem route="simpanan.index">
        Transaksi Simpanan
    </x-sidebar-subitem>
    <x-sidebar-subitem route="simpanan.verify">
        Verifikasi Simpanan
    </x-sidebar-subitem>
</x-sidebar-dropdown>

{{-- Pinjaman --}}
<x-sidebar-dropdown 
    label="Pinjaman" 
    id="menu-pinjaman"
    :routes="['pinjaman*']">
    <x-slot:icon>
        <x-icon name="coin" />
    </x-slot:icon>
    
    <x-sidebar-subitem route="pinjaman.create">
        Ajukan Pinjaman
    </x-sidebar-subitem>
    <x-sidebar-subitem route="pinjaman.index">
        Daftar Pinjaman
    </x-sidebar-subitem>
    <x-sidebar-subitem route="pinjaman.review">
        Review Pinjaman
    </x-sidebar-subitem>
    <x-sidebar-subitem route="pinjaman.approve">
        Approve Pinjaman
    </x-sidebar-subitem>
    <x-sidebar-subitem route="pinjaman.disburse">
        Pencairan Pinjaman
    </x-sidebar-subitem>
</x-sidebar-dropdown>

{{-- Angsuran --}}
<x-sidebar-item route="angsuran.index">
    <x-slot:icon>
        <x-icon name="credit-card" />
    </x-slot:icon>
    Angsuran
</x-sidebar-item>

{{-- Kas --}}
<x-sidebar-dropdown 
    label="Kas" 
    id="menu-kas"
    :routes="['kas*']">
    <x-slot:icon>
        <x-icon name="building" />
    </x-slot:icon>
    
    <x-sidebar-subitem route="kas.index">
        Transaksi Kas
    </x-sidebar-subitem>
    <x-sidebar-subitem route="kas.laporan">
        Laporan Kas
    </x-sidebar-subitem>
</x-sidebar-dropdown>

{{-- Laporan --}}
<x-sidebar-dropdown 
    label="Laporan" 
    id="menu-laporan"
    :routes="['laporan*']">
    <x-slot:icon>
        <x-icon name="report" />
    </x-slot:icon>
    
    <x-sidebar-subitem route="laporan.keuangan">
        Laporan Keuangan
    </x-sidebar-subitem>
    <x-sidebar-subitem route="laporan.anggota">
        Laporan Anggota
    </x-sidebar-subitem>
</x-sidebar-dropdown>

{{-- Settings & Admin --}}
<x-sidebar-section title="Administrasi" />

<x-sidebar-item route="dashboard">
    <x-slot:icon>
        <x-icon name="user-cog" />
    </x-slot:icon>
    Manajemen User
</x-sidebar-item>

<x-sidebar-item route="dashboard">
    <x-slot:icon>
        <x-icon name="settings" />
    </x-slot:icon>
    Pengaturan
</x-sidebar-item>
