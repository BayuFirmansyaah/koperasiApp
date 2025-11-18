<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use App\Models\Anggota;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AngsuranTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $bendahara;
    private User $userAnggota;
    private Anggota $anggota;
    private Pinjaman $pinjaman;

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
        $this->pinjaman = Pinjaman::factory()->create([
            'anggota_id' => $this->anggota->id,
            'status' => 'berjalan',
        ]);
    }

    /**
     * Test anggota can view angsuran
     */
    public function test_anggota_can_view_angsuran(): void
    {
        $response = $this->actingAs($this->userAnggota)
            ->get('/angsuran');

        $response->assertStatus(200);
        $response->assertViewIs('angsuran.index');
    }

    /**
     * Test anggota can create angsuran payment
     */
    public function test_anggota_can_create_angsuran(): void
    {
        $response = $this->actingAs($this->userAnggota)
            ->get('/angsuran/create');

        $response->assertStatus(200);
        $response->assertViewIs('angsuran.create');
    }

    /**
     * Test anggota can submit angsuran payment
     */
    public function test_anggota_can_submit_angsuran(): void
    {
        $angsuranData = [
            'pinjaman_id' => $this->pinjaman->id,
            'angsuran_ke' => 1,
            'tanggal_jatuh_tempo' => now()->addMonth()->format('Y-m-d'),
            'nominal_angsuran' => 500000,
            'denda' => 0,
        ];

        $response = $this->actingAs($this->userAnggota)
            ->post('/angsuran', $angsuranData);

        $response->assertRedirect('/angsuran');
        $this->assertDatabaseHas('angsurans', [
            'pinjaman_id' => $this->pinjaman->id,
            'nominal_angsuran' => 500000,
            'status' => 'pending',
        ]);
    }

    /**
     * Test bendahara can view angsuran for verification
     */
    public function test_bendahara_can_view_angsuran_verify(): void
    {
        Angsuran::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->bendahara)
            ->get('/angsuran-verify');

        $response->assertStatus(200);
        $response->assertViewIs('angsuran.verify');
    }

    /**
     * Test bendahara can verify angsuran
     */
    public function test_bendahara_can_verify_angsuran(): void
    {
        $angsuran = Angsuran::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->bendahara)
            ->post("/angsuran/{$angsuran->id}/verify");

        $response->assertRedirect();
        $this->assertDatabaseHas('angsurans', [
            'id' => $angsuran->id,
            'status' => 'verified',
        ]);
    }

    /**
     * Test anggota can view angsuran detail
     */
    public function test_anggota_can_view_angsuran_detail(): void
    {
        $angsuran = Angsuran::factory()->create(['pinjaman_id' => $this->pinjaman->id]);

        $response = $this->actingAs($this->userAnggota)
            ->get("/angsuran/{$angsuran->id}");

        $response->assertStatus(200);
        $response->assertViewIs('angsuran.show');
    }
}
