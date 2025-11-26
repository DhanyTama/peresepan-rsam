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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $prefix = 'OBT-';

            $last = self::where('kode_obat', 'LIKE', $prefix . '%')
                ->orderBy('kode_obat', 'desc')
                ->first();

            if ($last) {
                $lastNumber = intval(substr($last->kode_obat, -4)) + 1;
            } else {
                $lastNumber = 1;
            }

            $model->kode_obat = $prefix . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
        });
    }

    public function resepDetails()
    {
        return $this->hasMany(ResepDetail::class, 'obat_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'obat_id');
    }
}
