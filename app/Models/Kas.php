<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $fillable = [
        'tanggal_transaksi',
        'jenis',
        'kategori',
        'nominal',
        'saldo_sebelum',
        'saldo_sesudah',
        'referensi_id',
        'referensi_type',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'nominal' => 'decimal:2',
        'saldo_sebelum' => 'decimal:2',
        'saldo_sesudah' => 'decimal:2',
    ];

    public function referensi()
    {
        return $this->morphTo();
    }

    // Alias for referensi (for backward compatibility)
    public function transactable()
    {
        return $this->morphTo('referensi');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
