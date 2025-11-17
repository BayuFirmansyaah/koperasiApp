<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Anggota Management
            'view-anggota',
            'create-anggota',
            'update-anggota',
            'delete-anggota',
            'approve-anggota',
            
            // Simpanan Management
            'view-simpanan',
            'create-simpanan',
            'verify-simpanan',
            'view-own-simpanan',
            
            // Pinjaman Management
            'view-pinjaman',
            'create-pinjaman',
            'review-pinjaman',
            'approve-pinjaman',
            'disburse-pinjaman',
            'view-own-pinjaman',
            
            // Angsuran Management
            'view-angsuran',
            'verify-angsuran',
            'view-own-angsuran',
            
            // Kas Management
            'view-kas',
            'create-kas',
            'update-kas',
            
            // Laporan
            'view-laporan-global',
            'view-laporan-own',
            'export-laporan',
            
            // User & Settings
            'manage-users',
            'manage-roles',
            'manage-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        
        // Super Admin - Full Access
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Pengurus - Operational Manager
        $pengurus = Role::create(['name' => 'pengurus']);
        $pengurus->givePermissionTo([
            'view-anggota',
            'create-anggota',
            'update-anggota',
            'approve-anggota',
            'view-simpanan',
            'view-pinjaman',
            'review-pinjaman',
            'approve-pinjaman',
            'view-angsuran',
            'view-laporan-global',
            'export-laporan',
        ]);

        // Bendahara - Financial Manager
        $bendahara = Role::create(['name' => 'bendahara']);
        $bendahara->givePermissionTo([
            'view-anggota',
            'view-simpanan',
            'verify-simpanan',
            'view-pinjaman',
            'disburse-pinjaman',
            'view-angsuran',
            'verify-angsuran',
            'view-kas',
            'create-kas',
            'update-kas',
            'view-laporan-global',
            'export-laporan',
        ]);

        // Anggota - Member
        $anggota = Role::create(['name' => 'anggota']);
        $anggota->givePermissionTo([
            'view-own-simpanan',
            'create-pinjaman',
            'view-own-pinjaman',
            'view-own-angsuran',
            'view-laporan-own',
        ]);
    }
}

