<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pinjaman;
use App\Models\Anggota;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PinjamanTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $pengurus;
    private User $bendahara;
    private User $userAnggota;
    private Anggota $anggota;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        $this->pengurus = User::factory()->create();
        $this->pengurus->assignRole('pengurus');
        
        $this->bendahara = User::factory()->create();
        $this->bendahara->assignRole('bendahara');
        
        $this->userAnggota = User::factory()->create();
        $this->userAnggota->assignRole('anggota');
        
        $this->anggota = Anggota::factory()->create(['user_id' => $this->userAnggota->id]);
    }

    /**
     * Test anggota can view their own loans
     */
    public function test_anggota_can_view_pinjaman(): void
    {
        $response = $this->actingAs($this->userAnggota)
            ->get('/pinjaman');

        $response->assertStatus(200);
        $response->assertViewIs('pinjaman.index');
    }

    /**
     * Test anggota can create pinjaman request
     */
    public function test_anggota_can_create_pinjaman(): void
    {
        $response = $this->actingAs($this->userAnggota)
            ->get('/pinjaman/create');

        $response->assertStatus(200);
        $response->assertViewIs('pinjaman.create');
    }

    /**
     * Test anggota can submit pinjaman application
     */
    public function test_anggota_can_submit_pinjaman(): void
    {
        $pinjamanData = [
            'nominal' => 5000000,
            'lama_pinjaman' => 12,
            'tujuan_pinjaman' => 'Modal usaha',
        ];

        $response = $this->actingAs($this->userAnggota)
            ->post('/pinjaman', $pinjamanData);

        $response->assertRedirect('/pinjaman');
        $this->assertDatabaseHas('pinjamans', [
            'anggota_id' => $this->anggota->id,
            'nominal_pinjaman' => 5000000,
            'status' => 'pending',
        ]);
    }

    /**
     * Test pengurus can view pinjaman for review
     */
    public function test_pengurus_can_view_pinjaman_review(): void
    {
        Pinjaman::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->pengurus)
            ->get('/pinjaman/review');

        $response->assertStatus(200);
        $response->assertViewIs('pinjaman.review');
    }

    /**
     * Test pengurus can review pinjaman (approve)
     */
    public function test_pengurus_can_approve_pinjaman(): void
    {
        $pinjaman = Pinjaman::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->pengurus)
            ->post("/pinjaman/{$pinjaman->id}/review", [
                'action' => 'approve',
                'catatan_pengurus' => 'Direkomendasikan',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pinjamans', [
            'id' => $pinjaman->id,
            'status' => 'approved_pengurus',
        ]);
    }

    /**
     * Test pengurus can review pinjaman (reject)
     */
    public function test_pengurus_can_reject_pinjaman(): void
    {
        $pinjaman = Pinjaman::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->pengurus)
            ->post("/pinjaman/{$pinjaman->id}/review", [
                'action' => 'reject',
                'catatan_pengurus' => 'Tidak sesuai kriteria',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pinjamans', [
            'id' => $pinjaman->id,
            'status' => 'rejected_pengurus',
        ]);
    }

    /**
     * Test bendahara can view pinjaman for approval
     */
    public function test_bendahara_can_view_pinjaman_approval(): void
    {
        Pinjaman::factory()->create(['status' => 'approved_pengurus']);

        $response = $this->actingAs($this->bendahara)
            ->get('/pinjaman/approve');

        $response->assertStatus(200);
        $response->assertViewIs('pinjaman.approve');
    }

    /**
     * Test bendahara can approve pinjaman
     */
    public function test_bendahara_can_approve_pinjaman(): void
    {
        $pinjaman = Pinjaman::factory()->create(['status' => 'approved_pengurus']);

        $response = $this->actingAs($this->bendahara)
            ->post("/pinjaman/{$pinjaman->id}/approve", [
                'action' => 'approve',
                'catatan_bendahara' => 'Sudah verified',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pinjamans', [
            'id' => $pinjaman->id,
            'status' => 'approved_bendahara',
        ]);
    }

    /**
     * Test bendahara can reject pinjaman
     */
    public function test_bendahara_can_reject_pinjaman(): void
    {
        $pinjaman = Pinjaman::factory()->create(['status' => 'approved_pengurus']);

        $response = $this->actingAs($this->bendahara)
            ->post("/pinjaman/{$pinjaman->id}/approve", [
                'action' => 'reject',
                'catatan_bendahara' => 'Saldo tidak cukup',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pinjamans', [
            'id' => $pinjaman->id,
            'status' => 'rejected_bendahara',
        ]);
    }

    /**
     * Test bendahara can disburse pinjaman
     */
    public function test_bendahara_can_disburse_pinjaman(): void
    {
        $pinjaman = Pinjaman::factory()->create(['status' => 'approved_bendahara']);

        $response = $this->actingAs($this->bendahara)
            ->get('/pinjaman/disburse');

        $response->assertStatus(200);
        $response->assertViewIs('pinjaman.disburse');
    }

    /**
     * Test bendahara can execute pinjaman disbursal
     */
    public function test_bendahara_can_execute_disbursal(): void
    {
        $pinjaman = Pinjaman::factory()->create(['status' => 'approved_bendahara']);

        $response = $this->actingAs($this->bendahara)
            ->post("/pinjaman/{$pinjaman->id}/disburse", [
                'tanggal_pencairan' => now()->format('Y-m-d'),
                'metode_pencairan' => 'transfer',
                'nomor_rekening' => '1234567890',
                'catatan_pencairan' => 'Pencairan normal',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pinjamans', [
            'id' => $pinjaman->id,
            'status' => 'berjalan',
        ]);
    }

    /**
     * Test pinjaman workflow: from pending to berjalan
     */
    public function test_pinjaman_full_workflow(): void
    {
        // Create pending pinjaman
        $pinjaman = Pinjaman::factory()->create(['status' => 'pending']);
        $this->assertDatabaseHas('pinjamans', ['id' => $pinjaman->id, 'status' => 'pending']);

        // Pengurus approves
        $this->actingAs($this->pengurus)
            ->post("/pinjaman/{$pinjaman->id}/review", [
                'action' => 'approve',
                'catatan_pengurus' => 'OK',
            ]);
        $this->assertDatabaseHas('pinjamans', ['id' => $pinjaman->id, 'status' => 'approved_pengurus']);

        // Bendahara approves
        $this->actingAs($this->bendahara)
            ->post("/pinjaman/{$pinjaman->id}/approve", [
                'action' => 'approve',
                'catatan_bendahara' => 'OK',
            ]);
        $this->assertDatabaseHas('pinjamans', ['id' => $pinjaman->id, 'status' => 'approved_bendahara']);

        // Bendahara disburses
        $this->actingAs($this->bendahara)
            ->post("/pinjaman/{$pinjaman->id}/disburse", [
                'tanggal_pencairan' => now()->format('Y-m-d'),
                'metode_pencairan' => 'transfer',
                'nomor_rekening' => '1234567890',
                'catatan_pencairan' => 'OK',
            ]);
        $this->assertDatabaseHas('pinjamans', ['id' => $pinjaman->id, 'status' => 'berjalan']);
    }
}
