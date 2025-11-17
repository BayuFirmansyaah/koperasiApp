<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pinjamans', function (Blueprint $table) {
            // Add missing columns for pengurus review
            if (!Schema::hasColumn('pinjamans', 'reviewed_by')) {
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('approved_by_pengurus');
            }
            if (!Schema::hasColumn('pinjamans', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
            
            // Add missing columns for bendahara note
            if (!Schema::hasColumn('pinjamans', 'catatan_pengurus')) {
                $table->text('catatan_pengurus')->nullable()->after('alasan_reject');
            }
            if (!Schema::hasColumn('pinjamans', 'catatan_bendahara')) {
                $table->text('catatan_bendahara')->nullable()->after('catatan_pengurus');
            }
            
            // Add missing columns for disburse
            if (!Schema::hasColumn('pinjamans', 'disbursed_by')) {
                $table->foreignId('disbursed_by')->nullable()->constrained('users')->onDelete('set null')->after('catatan_bendahara');
            }
            if (!Schema::hasColumn('pinjamans', 'metode_pencairan')) {
                $table->enum('metode_pencairan', ['tunai', 'transfer'])->nullable()->after('disbursed_by');
            }
            if (!Schema::hasColumn('pinjamans', 'nomor_rekening')) {
                $table->string('nomor_rekening')->nullable()->after('metode_pencairan');
            }
            if (!Schema::hasColumn('pinjamans', 'catatan_pencairan')) {
                $table->text('catatan_pencairan')->nullable()->after('nomor_rekening');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjamans', function (Blueprint $table) {
            $table->dropForeignIdFor('reviewed_by', 'users');
            $table->dropColumn([
                'reviewed_by',
                'reviewed_at',
                'catatan_pengurus',
                'catatan_bendahara',
                'disbursed_by',
                'metode_pencairan',
                'nomor_rekening',
                'catatan_pencairan'
            ]);
        });
    }
};
