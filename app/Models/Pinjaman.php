<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pinjaman extends Model
{
    use SoftDeletes;

    protected $table = 'pinjamans';

    protected $fillable = [
        'anggota_id',
        'no_pinjaman',
        'nominal_pinjaman',
        'bunga_persen',
        'nominal_bunga',
        'total_pinjaman',
        'tenor_bulan',
        'nominal_angsuran_per_bulan',
        'tanggal_pengajuan',
        'tanggal_approve',
        'tanggal_pencairan',
        'status',
        'approved_by_pengurus',
        'approved_by_bendahara',
        'alasan_pengajuan',
        'alasan_reject',
        'sisa_pinjaman',
    ];

    protected $casts = [
        'nominal_pinjaman' => 'decimal:2',
        'bunga_persen' => 'decimal:2',
        'nominal_bunga' => 'decimal:2',
        'total_pinjaman' => 'decimal:2',
        'nominal_angsuran_per_bulan' => 'decimal:2',
        'sisa_pinjaman' => 'decimal:2',
        'tanggal_pengajuan' => 'date',
        'tanggal_approve' => 'date',
        'tanggal_pencairan' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function approvedByPengurus()
    {
        return $this->belongsTo(User::class, 'approved_by_pengurus');
    }

    public function approvedByBendahara()
    {
        return $this->belongsTo(User::class, 'approved_by_bendahara');
    }

    public function angsurans()
    {
        return $this->hasMany(Angsuran::class);
    }

    public function kas()
    {
        return $this->morphOne(Kas::class, 'referensi');
    }
}
