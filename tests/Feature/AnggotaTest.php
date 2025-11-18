<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Anggota;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnggotaTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $anggota;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create users with roles
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        $this->anggota = User::factory()->create();
        $this->anggota->assignRole('anggota');
    }

    /**
     * Test anggota can view list of members
     */
    public function test_anggota_index_authenticated(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/anggota');

        $response->assertStatus(200);
        $response->assertViewIs('anggota.index');
    }

    /**
     * Test unauthenticated user cannot view anggota
     */
    public function test_anggota_index_unauthenticated(): void
    {
        $response = $this->get('/anggota');

        $response->assertRedirect('/login');
    }

    /**
     * Test admin can create new anggota
     */
    public function test_admin_can_create_anggota(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/anggota/create');

        $response->assertStatus(200);
        $response->assertViewIs('anggota.create');
    }

    /**
     * Test admin can store anggota
     */
    public function test_admin_can_store_anggota(): void
    {
        $anggotaData = [
            'user_id' => $this->anggota->id,
            'no_anggota' => 'ANG001',
            'nik' => '1234567890123456',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Merdeka No. 1',
            'no_telepon' => '081234567890',
            'pekerjaan' => 'Karyawan',
            'status' => 'pending',
        ];

        $response = $this->actingAs($this->admin)
            ->post('/anggota', $anggotaData);

        $response->assertRedirect('/anggota');
        $this->assertDatabaseHas('anggotas', [
            'no_anggota' => 'ANG001',
            'nik' => '1234567890123456',
        ]);
    }

    /**
     * Test admin can view anggota detail
     */
    public function test_admin_can_view_anggota_detail(): void
    {
        $anggota = Anggota::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get("/anggota/{$anggota->id}");

        $response->assertStatus(200);
        $response->assertViewIs('anggota.show');
        $response->assertViewHas('anggota', $anggota);
    }

    /**
     * Test admin can update anggota
     */
    public function test_admin_can_update_anggota(): void
    {
        $anggota = Anggota::factory()->create();

        $updateData = [
            'user_id' => $anggota->user_id,
            'no_anggota' => $anggota->no_anggota,
            'nik' => $anggota->nik,
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => $anggota->tanggal_lahir,
            'jenis_kelamin' => $anggota->jenis_kelamin,
            'alamat' => 'Updated Address',
            'no_telepon' => '081987654321',
            'pekerjaan' => 'Entrepreneur',
            'status' => $anggota->status,
        ];

        $response = $this->actingAs($this->admin)
            ->patch("/anggota/{$anggota->id}", $updateData);

        $response->assertRedirect("/anggota/{$anggota->id}");
        $this->assertDatabaseHas('anggotas', [
            'id' => $anggota->id,
            'alamat' => 'Updated Address',
        ]);
    }

    /**
     * Test admin can delete anggota
     */
    public function test_admin_can_delete_anggota(): void
    {
        $anggota = Anggota::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete("/anggota/{$anggota->id}");

        $response->assertRedirect('/anggota');
        $this->assertDatabaseMissing('anggotas', [
            'id' => $anggota->id,
        ]);
    }

    /**
     * Test pengurus can approve anggota
     */
    public function test_pengurus_can_approve_anggota(): void
    {
        $pengurus = User::factory()->create();
        $pengurus->assignRole('pengurus');

        $anggota = Anggota::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($pengurus)
            ->post("/pengurus/approval/{$anggota->id}/approve");

        $response->assertRedirect();
        $this->assertDatabaseHas('anggotas', [
            'id' => $anggota->id,
            'status' => 'approved',
        ]);
    }

    /**
     * Test pengurus can reject anggota
     */
    public function test_pengurus_can_reject_anggota(): void
    {
        $pengurus = User::factory()->create();
        $pengurus->assignRole('pengurus');

        $anggota = Anggota::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($pengurus)
            ->post("/pengurus/approval/{$anggota->id}/reject", [
                'alasan_reject' => 'Data tidak lengkap',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('anggotas', [
            'id' => $anggota->id,
            'status' => 'rejected',
        ]);
    }
}
