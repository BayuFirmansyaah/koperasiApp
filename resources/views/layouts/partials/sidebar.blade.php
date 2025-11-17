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
    :routes="['anggota*', 'pengurus/approval*']"
    can="view-anggota">
    <x-slot:icon>
        <x-icon name="users" />
    </x-slot:icon>
    
    <x-sidebar-subitem route="anggota.index" can="view-anggota">
        Daftar Anggota
    </x-sidebar-subitem>
    <x-sidebar-subitem route="pengurus.approval.index" can="approve-anggota">
        Persetujuan Anggota
    </x-sidebar-subitem>
</x-sidebar-dropdown>

{{-- Simpanan --}}
<x-sidebar-dropdown 
    label="Simpanan" 
    id="menu-simpanan"
    :routes="['simpanan*']"
    can="view-simpanan">
    <x-slot:icon>
        <x-icon name="wallet" />
    </x-slot:icon>
    
    <x-sidebar-subitem route="simpanan.index" can="view-simpanan">
        Transaksi Simpanan
    </x-sidebar-subitem>
    <x-sidebar-subitem route="simpanan.verify" can="verify-simpanan">
        Verifikasi Simpanan
    </x-sidebar-subitem>
</x-sidebar-dropdown>

{{-- Pinjaman --}}
<x-sidebar-dropdown 
    label="Pinjaman" 
    id="menu-pinjaman"
    :routes="['pinjaman*']"
    can="view-pinjaman">
    <x-slot:icon>
        <x-icon name="coin" />
    </x-slot:icon>
    
    <x-sidebar-subitem route="pinjaman.create" can="create-pinjaman">
        Ajukan Pinjaman
    </x-sidebar-subitem>
    <x-sidebar-subitem route="pinjaman.index" can="view-pinjaman">
        Daftar Pinjaman
    </x-sidebar-subitem>
    <x-sidebar-subitem route="pinjaman.review" can="review-pinjaman">
        Review Pinjaman
    </x-sidebar-subitem>
    <x-sidebar-subitem route="pinjaman.approve" can="approve-pinjaman">
        Approve Pinjaman
    </x-sidebar-subitem>
    <x-sidebar-subitem route="pinjaman.disburse" can="disburse-pinjaman">
        Pencairan Pinjaman
    </x-sidebar-subitem>
</x-sidebar-dropdown>

{{-- Angsuran --}}
<x-sidebar-item route="angsuran.index" can="view-angsuran">
    <x-slot:icon>
        <x-icon name="credit-card" />
    </x-slot:icon>
    Angsuran
</x-sidebar-item>

{{-- Kas --}}
<x-sidebar-dropdown 
    label="Kas" 
    id="menu-kas"
    :routes="['kas*']"
    can="view-kas">
    <x-slot:icon>
        <x-icon name="building" />
    </x-slot:icon>
    
    <x-sidebar-subitem route="kas.index" can="view-kas">
        Transaksi Kas
    </x-sidebar-subitem>
    <x-sidebar-subitem route="kas.laporan" can="view-kas">
        Laporan Kas
    </x-sidebar-subitem>
</x-sidebar-dropdown>

{{-- Laporan --}}
<x-sidebar-dropdown 
    label="Laporan" 
    id="menu-laporan"
    :routes="['laporan*']"
    can="view-laporan">
    <x-slot:icon>
        <x-icon name="report" />
    </x-slot:icon>
    
    <x-sidebar-subitem route="laporan.keuangan" can="view-laporan">
        Laporan Keuangan
    </x-sidebar-subitem>
    <x-sidebar-subitem route="laporan.anggota" can="view-laporan">
        Laporan Anggota
    </x-sidebar-subitem>
</x-sidebar-dropdown>

{{-- Settings & Admin --}}
<x-sidebar-section title="Administrasi" />

<x-sidebar-item route="dashboard" can="manage-users">
    <x-slot:icon>
        <x-icon name="user-cog" />
    </x-slot:icon>
    Manajemen User
</x-sidebar-item>

<x-sidebar-item route="dashboard" can="manage-settings">
    <x-slot:icon>
        <x-icon name="settings" />
    </x-slot:icon>
    Pengaturan
</x-sidebar-item>
