<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Laporan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $bendahara;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        $this->bendahara = User::factory()->create();
        $this->bendahara->assignRole('bendahara');
    }

    /**
     * Test bendahara can view laporan keuangan
     */
    public function test_bendahara_can_view_laporan_keuangan(): void
    {
        $response = $this->actingAs($this->bendahara)
            ->get('/laporan/keuangan');

        $response->assertStatus(200);
        $response->assertViewIs('laporan.keuangan');
    }

    /**
     * Test bendahara can view laporan anggota
     */
    public function test_bendahara_can_view_laporan_anggota(): void
    {
        $response = $this->actingAs($this->bendahara)
            ->get('/laporan/anggota');

        $response->assertStatus(200);
        $response->assertViewIs('laporan.anggota');
    }

    /**
     * Test admin can view laporan keuangan
     */
    public function test_admin_can_view_laporan_keuangan(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/laporan/keuangan');

        $response->assertStatus(200);
        $response->assertViewIs('laporan.keuangan');
    }

    /**
     * Test admin can view laporan anggota
     */
    public function test_admin_can_view_laporan_anggota(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/laporan/anggota');

        $response->assertStatus(200);
        $response->assertViewIs('laporan.anggota');
    }

    /**
     * Test laporan filters work
     */
    public function test_laporan_with_filters(): void
    {
        $response = $this->actingAs($this->bendahara)
            ->get('/laporan/keuangan?date_from=2025-01-01&date_to=2025-01-31');

        $response->assertStatus(200);
    }
}
