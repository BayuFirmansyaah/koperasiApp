<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Simpanan;
use App\Models\JenisSimpanan;
use App\Models\Anggota;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpananTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $bendahara;
    private User $userAnggota;
    private Anggota $anggota;
    private JenisSimpanan $jenisSimpanan;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        $this->bendahara = User::factory()->create();
        $this->bendahara->assignRole('bendahara');
        
        $this->userAnggota = User::factory()->create();
        $this->userAnggota->assignRole('anggota');
        
        $this->anggota = Anggota::factory()->create(['user_id' => $this->userAnggota->id]);
        $this->jenisSimpanan = JenisSimpanan::factory()->create();
    }

    /**
     * Test anggota can view simpanan
     */
    public function test_anggota_can_view_simpanan(): void
    {
        $response = $this->actingAs($this->userAnggota)
            ->get('/simpanan');

        $response->assertStatus(200);
        $response->assertViewIs('simpanan.index');
    }

    /**
     * Test anggota can create simpanan
     */
    public function test_anggota_can_create_simpanan(): void
    {
        $response = $this->actingAs($this->userAnggota)
            ->get('/simpanan/create');

        $response->assertStatus(200);
        $response->assertViewIs('simpanan.create');
    }

    /**
     * Test anggota can submit simpanan
     */
    public function test_anggota_can_submit_simpanan(): void
    {
        $simpananData = [
            'anggota_id' => $this->anggota->id,
            'jenis_simpanan_id' => $this->jenisSimpanan->id,
            'tanggal_transaksi' => now()->format('Y-m-d'),
            'nominal' => 500000,
            'metode_pembayaran' => 'transfer',
            'keterangan' => 'Simpanan rutin',
        ];

        $response = $this->actingAs($this->userAnggota)
            ->post('/simpanan', $simpananData);

        $response->assertRedirect('/simpanan');
        $this->assertDatabaseHas('simpanans', [
            'anggota_id' => $this->anggota->id,
            'nominal' => 500000,
            'status' => 'pending',
        ]);
    }

    /**
     * Test bendahara can view simpanan for verification
     */
    public function test_bendahara_can_view_simpanan_verify(): void
    {
        Simpanan::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->bendahara)
            ->get('/simpanan-verify');

        $response->assertStatus(200);
        $response->assertViewIs('simpanan.verify');
    }

    /**
     * Test bendahara can verify simpanan
     */
    public function test_bendahara_can_verify_simpanan(): void
    {
        $simpanan = Simpanan::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->bendahara)
            ->post("/simpanan/{$simpanan->id}/verify");

        $response->assertRedirect();
        $this->assertDatabaseHas('simpanans', [
            'id' => $simpanan->id,
            'status' => 'verified',
        ]);
    }

    /**
     * Test bendahara can view simpanan detail
     */
    public function test_bendahara_can_view_simpanan_detail(): void
    {
        $simpanan = Simpanan::factory()->create();

        $response = $this->actingAs($this->bendahara)
            ->get("/simpanan/{$simpanan->id}");

        $response->assertStatus(200);
        $response->assertViewIs('simpanan.show');
    }
}
