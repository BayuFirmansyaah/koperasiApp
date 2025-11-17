<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    protected $fillable = [
        'anggota_id',
        'jenis_simpanan_id',
        'tanggal_transaksi',
        'nominal',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
        'verified_by',
        'verified_at',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'nominal' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function jenisSimpanan()
    {
        return $this->belongsTo(JenisSimpanan::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function kas()
    {
        return $this->morphOne(Kas::class, 'referensi');
    }
}
