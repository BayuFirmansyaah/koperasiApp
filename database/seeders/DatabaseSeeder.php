<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🌱 Seeding database with sample data...');
        $this->command->info('');

        // Run seeders in correct order
        $this->command->info('📋 Step 1: Setting up roles & permissions...');
        $this->call(RolePermissionSeeder::class);
        
        $this->command->info('');
        $this->command->info('💰 Step 2: Setting up jenis simpanan...');
        $this->call(JenisSimpananSeeder::class);
        
        $this->command->info('');
        $this->command->info('⚙️  Step 3: Configuring settings...');
        $this->call(SettingSeeder::class);
        
        $this->command->info('');
        $this->command->info('👥 Step 4: Creating admin users...');
        $this->call(UserSeeder::class);
        
        $this->command->info('');
        $this->command->info('👤 Step 5: Creating sample anggota...');
        $this->call(AnggotaSeeder::class);
        
        $this->command->info('');
        $this->command->info('💵 Step 6: Creating simpanan transactions...');
        $this->call(SimpananSeeder::class);
        
        $this->command->info('');
        $this->command->info('🏦 Step 7: Creating pinjaman & angsuran...');
        $this->call(PinjamanSeeder::class);
        
        $this->command->info('');
        $this->command->info('💼 Step 8: Recording kas transactions...');
        $this->call(KasSeeder::class);

        $this->command->info('');
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('📊 Summary:');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('Users: ' . \App\Models\User::count() . ' (4 admin + 7 anggota)');
        $this->command->info('Anggota: ' . \App\Models\Anggota::count() . ' (5 active + 2 pending)');
        $this->command->info('Simpanan: ' . \App\Models\Simpanan::count() . ' transactions');
        $this->command->info('Pinjaman: ' . \App\Models\Pinjaman::count() . ' loans');
        $this->command->info('Angsuran: ' . \App\Models\Angsuran::count() . ' installments');
        $this->command->info('Kas: ' . \App\Models\Kas::count() . ' transactions');
        
        $saldoKas = \App\Models\Kas::latest('tanggal_transaksi')->first()->saldo_sesudah ?? 0;
        $this->command->info('Saldo Kas: Rp ' . number_format($saldoKas, 0, ',', '.'));
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        $this->command->info('');
        $this->command->info('🔑 Login Credentials:');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->warn('Super Admin:');
        $this->command->line('  Email: admin@koperasi.com');
        $this->command->line('  Password: password');
        $this->command->info('');
        $this->command->warn('Pengurus:');
        $this->command->line('  Email:  ');
        $this->command->line('  Password: password');
        $this->command->info('');
        $this->command->warn('Bendahara:');
        $this->command->line('  Email: bendahara@koperasi.com');
        $this->command->line('  Password: password');
        $this->command->info('');
        $this->command->warn('Anggota (sample):');
        $this->command->line('  Email: ahmad.subarjo@example.com');
        $this->command->line('  Password: password');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('');
    }
}

