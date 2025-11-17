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
        Schema::create('pinjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained()->onDelete('cascade');
            $table->string('no_pinjaman')->unique();
            $table->decimal('nominal_pinjaman', 15, 2);
            $table->decimal('bunga_persen', 5, 2);
            $table->decimal('nominal_bunga', 15, 2);
            $table->decimal('total_pinjaman', 15, 2);
            $table->integer('tenor_bulan');
            $table->decimal('nominal_angsuran_per_bulan', 15, 2);
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_approve')->nullable();
            $table->date('tanggal_pencairan')->nullable();
            $table->enum('status', ['pending', 'approved_pengurus', 'approved_bendahara', 'dicairkan', 'berjalan', 'lunas', 'ditolak'])->default('pending');
            $table->foreignId('approved_by_pengurus')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by_bendahara')->nullable()->constrained('users')->onDelete('set null');
            $table->text('alasan_pengajuan');
            $table->text('alasan_reject')->nullable();
            $table->decimal('sisa_pinjaman', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjamans');
    }
};
