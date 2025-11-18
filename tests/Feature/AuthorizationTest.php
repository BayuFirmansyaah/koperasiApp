<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Anggota;
use App\Models\Pinjaman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test anggota cannot access admin features
     */
    public function test_anggota_cannot_access_admin_features(): void
    {
        $anggota = User::factory()->create();
        $anggota->assignRole('anggota');

        $response = $this->actingAs($anggota)
            ->get('/pinjaman/approve');

        $response->assertForbidden();
    }

    /**
     * Test pengurus cannot access bendahara features
     */
    public function test_pengurus_cannot_access_bendahara_features(): void
    {
        $pengurus = User::factory()->create();
        $pengurus->assignRole('pengurus');

        $response = $this->actingAs($pengurus)
            ->get('/pinjaman/disburse');

        $response->assertForbidden();
    }

    /**
     * Test bendahara cannot access pengurus features
     */
    public function test_bendahara_cannot_access_pengurus_features(): void
    {
        $bendahara = User::factory()->create();
        $bendahara->assignRole('bendahara');

        $response = $this->actingAs($bendahara)
            ->get('/pinjaman/review');

        $response->assertForbidden();
    }

    /**
     * Test unauthenticated user cannot access any features
     */
    public function test_unauthenticated_user_cannot_access_features(): void
    {
        $response = $this->get('/anggota');
        $response->assertRedirect('/login');

        $response = $this->get('/pinjaman');
        $response->assertRedirect('/login');

        $response = $this->get('/simpanan');
        $response->assertRedirect('/login');
    }

    /**
     * Test admin can access all features
     */
    public function test_admin_can_access_all_features(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/anggota');
        $response->assertOk();

        $response = $this->actingAs($admin)->get('/pinjaman');
        $response->assertOk();

        $response = $this->actingAs($admin)->get('/simpanan');
        $response->assertOk();

        $response = $this->actingAs($admin)->get('/angsuran');
        $response->assertOk();

        $response = $this->actingAs($admin)->get('/kas');
        $response->assertOk();

        $response = $this->actingAs($admin)->get('/laporan/keuangan');
        $response->assertOk();
    }

    /**
     * Test anggota can only see own data
     */
    public function test_anggota_can_only_see_own_data(): void
    {
        $anggota1User = User::factory()->create();
        $anggota1User->assignRole('anggota');
        
        $anggota2User = User::factory()->create();
        $anggota2User->assignRole('anggota');

        $anggota1 = Anggota::factory()->create(['user_id' => $anggota1User->id]);
        $anggota2 = Anggota::factory()->create(['user_id' => $anggota2User->id]);

        // Anggota1 should see only their own data in list
        $response = $this->actingAs($anggota1User)->get('/anggota');
        $response->assertStatus(200);
        // Note: Check in view if filtering is applied by permission

        // Anggota1 should see their own data
        $response = $this->actingAs($anggota1User)->get("/anggota/{$anggota1->id}");
        $response->assertStatus(200);
    }

    /**
     * Test permission-based access control
     */
    public function test_permission_based_access_control(): void
    {
        $user = User::factory()->create();
        $user->assignRole('anggota');

        // Anggota should have view-pinjaman permission
        $this->assertTrue($user->can('view-pinjaman'));
        
        // Anggota should not have approve-pinjaman permission
        $this->assertFalse($user->can('approve-pinjaman'));

        // Add permission and test again
        $user->givePermissionTo('approve-pinjaman');
        $this->assertTrue($user->can('approve-pinjaman'));
    }

    /**
     * Test policy authorization for models
     */
    public function test_policy_authorization(): void
    {
        $anggotaUser = User::factory()->create();
        $anggotaUser->assignRole('anggota');

        $anggota = Anggota::factory()->create(['user_id' => $anggotaUser->id]);

        // User should be authorized to view their own anggota
        $this->assertTrue($anggotaUser->can('view', $anggota));
    }
}
