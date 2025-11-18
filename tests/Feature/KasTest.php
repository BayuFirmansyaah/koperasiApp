<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasTest extends TestCase
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
     * Test bendahara can view kas transactions
     */
    public function test_bendahara_can_view_kas(): void
    {
        $response = $this->actingAs($this->bendahara)
            ->get('/kas');

        $response->assertStatus(200);
        $response->assertViewIs('kas.index');
    }

    /**
     * Test bendahara can create kas entry
     */
    public function test_bendahara_can_create_kas(): void
    {
        $response = $this->actingAs($this->bendahara)
            ->get('/kas/create');

        $response->assertStatus(200);
        $response->assertViewIs('kas.create');
    }

    /**
     * Test bendahara can store kas entry
     */
    public function test_bendahara_can_store_kas(): void
    {
        $kasData = [
            'tanggal_transaksi' => now()->format('Y-m-d H:i:s'),
            'jenis' => 'masuk',
            'kategori' => 'simpanan',
            'nominal' => 1000000,
            'keterangan' => 'Setoran simpanan',
        ];

        $response = $this->actingAs($this->bendahara)
            ->post('/kas', $kasData);

        $response->assertRedirect('/kas');
        $this->assertDatabaseHas('kas', [
            'jenis' => 'masuk',
            'nominal' => 1000000,
        ]);
    }

    /**
     * Test bendahara can view kas detail
     */
    public function test_bendahara_can_view_kas_detail(): void
    {
        $kas = Kas::factory()->create();

        $response = $this->actingAs($this->bendahara)
            ->get("/kas/{$kas->id}");

        $response->assertStatus(200);
        $response->assertViewIs('kas.show');
    }

    /**
     * Test bendahara can view kas laporan
     */
    public function test_bendahara_can_view_kas_laporan(): void
    {
        $response = $this->actingAs($this->bendahara)
            ->get('/kas-laporan');

        $response->assertStatus(200);
        $response->assertViewIs('kas.laporan');
    }

    /**
     * Test admin can view kas
     */
    public function test_admin_can_view_kas(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/kas');

        $response->assertStatus(200);
        $response->assertViewIs('kas.index');
    }
}
