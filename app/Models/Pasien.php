<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasien extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_pasien',
        'nama_pasien',
        'alamat',
        'no_telepon',
    ];

    public function resep()
    {
        return $this->hasMany(Resep::class, 'pasien_id');
    }
}
