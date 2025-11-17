<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSimpanan extends Model
{
    protected $fillable = [
        'nama',
        'kode',
        'nominal_minimum',
        'is_mandatory',
        'deskripsi',
    ];

    protected $casts = [
        'nominal_minimum' => 'decimal:2',
        'is_mandatory' => 'boolean',
    ];

    public function simpanans()
    {
        return $this->hasMany(Simpanan::class);
    }
}
