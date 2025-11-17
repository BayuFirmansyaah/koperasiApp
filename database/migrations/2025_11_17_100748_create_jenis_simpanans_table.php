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
        Schema::create('jenis_simpanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->string('kode', 10)->unique();
            $table->decimal('nominal_minimum', 15, 2)->default(0);
            $table->boolean('is_mandatory')->default(false);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_simpanans');
    }
};
