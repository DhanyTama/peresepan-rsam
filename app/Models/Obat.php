<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Obat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'stok',
        'harga_jual',
    ];

    public function resepDetails()
    {
        return $this->hasMany(ResepDetail::class, 'obat_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'obat_id');
    }
}
