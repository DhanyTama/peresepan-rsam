<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResepDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'resep_id',
        'obat_id',
        'jumlah',
        'dosis',
    ];

    public function resep()
    {
        return $this->belongsTo(Resep::class, 'resep_id');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
