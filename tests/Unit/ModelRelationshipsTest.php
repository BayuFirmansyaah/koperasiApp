<?php

namespace Tests\Unit;

use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Models\Angsuran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Anggota has many pinjamans
     */
    public function test_anggota_has_many_pinjamans(): void
    {
        $anggota = Anggota::factory()->create();
        $pinjamans = Pinjaman::factory(3)->create(['anggota_id' => $anggota->id]);

        $this->assertCount(3, $anggota->pinjamans);
        $this->assertTrue($anggota->pinjamans->contains($pinjamans[0]));
    }

    /**
     * Test Anggota has many simpanans
     */
    public function test_anggota_has_many_simpanans(): void
    {
        $anggota = Anggota::factory()->create();
        $simpanans = Simpanan::factory(3)->create(['anggota_id' => $anggota->id]);

        $this->assertCount(3, $anggota->simpanans);
        $this->assertTrue($anggota->simpanans->contains($simpanans[0]));
    }

    /**
     * Test Anggota belongs to User
     */
    public function test_anggota_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $anggota = Anggota::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($anggota->user->is($user));
    }

    /**
     * Test Pinjaman belongs to Anggota
     */
    public function test_pinjaman_belongs_to_anggota(): void
    {
        $anggota = Anggota::factory()->create();
        $pinjaman = Pinjaman::factory()->create(['anggota_id' => $anggota->id]);

        $this->assertTrue($pinjaman->anggota->is($anggota));
    }

    /**
     * Test Pinjaman has many angsurans
     */
    public function test_pinjaman_has_many_angsurans(): void
    {
        $pinjaman = Pinjaman::factory()->create();
        $angsurans = Angsuran::factory(3)->create(['pinjaman_id' => $pinjaman->id]);

        $this->assertCount(3, $pinjaman->angsurans);
        $this->assertTrue($pinjaman->angsurans->contains($angsurans[0]));
    }

    /**
     * Test Pinjaman has approved by pengurus relationship
     */
    public function test_pinjaman_approved_by_pengurus_relationship(): void
    {
        $pengurus = User::factory()->create();
        $pinjaman = Pinjaman::factory()->create(['approved_by_pengurus' => $pengurus->id]);

        $this->assertTrue($pinjaman->approvedByPengurus->is($pengurus));
    }

    /**
     * Test Pinjaman alias relationship reviewedBy
     */
    public function test_pinjaman_reviewed_by_alias(): void
    {
        $pengurus = User::factory()->create();
        $pinjaman = Pinjaman::factory()->create(['approved_by_pengurus' => $pengurus->id]);

        $this->assertTrue($pinjaman->reviewedBy->is($pengurus));
    }

    /**
     * Test Pinjaman has approved by bendahara relationship
     */
    public function test_pinjaman_approved_by_bendahara_relationship(): void
    {
        $bendahara = User::factory()->create();
        $pinjaman = Pinjaman::factory()->create(['approved_by_bendahara' => $bendahara->id]);

        $this->assertTrue($pinjaman->approvedByBendahara->is($bendahara));
    }

    /**
     * Test Pinjaman alias relationship approvedBy
     */
    public function test_pinjaman_approved_by_alias(): void
    {
        $bendahara = User::factory()->create();
        $pinjaman = Pinjaman::factory()->create(['approved_by_bendahara' => $bendahara->id]);

        $this->assertTrue($pinjaman->approvedBy->is($bendahara));
    }

    /**
     * Test Simpanan belongs to Anggota
     */
    public function test_simpanan_belongs_to_anggota(): void
    {
        $anggota = Anggota::factory()->create();
        $simpanan = Simpanan::factory()->create(['anggota_id' => $anggota->id]);

        $this->assertTrue($simpanan->anggota->is($anggota));
    }

    /**
     * Test Simpanan verified by user relationship
     */
    public function test_simpanan_verified_by_relationship(): void
    {
        $bendahara = User::factory()->create();
        $simpanan = Simpanan::factory()->create(['verified_by' => $bendahara->id]);

        $this->assertTrue($simpanan->verifiedBy->is($bendahara));
    }

    /**
     * Test Angsuran belongs to Pinjaman
     */
    public function test_angsuran_belongs_to_pinjaman(): void
    {
        $pinjaman = Pinjaman::factory()->create();
        $angsuran = Angsuran::factory()->create(['pinjaman_id' => $pinjaman->id]);

        $this->assertTrue($angsuran->pinjaman->is($pinjaman));
    }

    /**
     * Test Angsuran verified by user relationship
     */
    public function test_angsuran_verified_by_relationship(): void
    {
        $bendahara = User::factory()->create();
        $angsuran = Angsuran::factory()->create(['verified_by' => $bendahara->id]);

        $this->assertTrue($angsuran->verifiedBy->is($bendahara));
    }
}
