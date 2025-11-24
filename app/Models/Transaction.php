<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_transaksi',
        'resep_id',
        'apoteker_id',
        'total_harga'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $prefix = 'TRX-' . date('Ymd');

            $last = self::where('nomor_transaksi', 'LIKE', $prefix . '%')
                ->orderBy('nomor_transaksi', 'desc')
                ->first();

            if ($last) {
                $lastNumber = intval(substr($last->nomor_transaksi, -4)) + 1;
            } else {
                $lastNumber = 1;
            }

            $model->nomor_transaksi = $prefix . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
        });
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    public function resep()
    {
        return $this->belongsTo(Resep::class, 'resep_id');
    }

    public function apoteker()
    {
        return $this->belongsTo(User::class, 'apoteker_id');
    }
}
