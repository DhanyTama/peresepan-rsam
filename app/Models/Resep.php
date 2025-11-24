<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resep extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_resep',
        'pasien_id',
        'dokter_id',
        'status',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $prefix = 'RSP-' . date('Ymd');

            $last = self::where('nomor_resep', 'LIKE', $prefix . '%')
                ->orderBy('nomor_resep', 'desc')
                ->first();

            if ($last) {
                $lastNumber = intval(substr($last->nomor_resep, -4)) + 1;
            } else {
                $lastNumber = 1;
            }

            $model->nomor_resep = $prefix . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
        });
    }

    public function details()
    {
        return $this->hasMany(ResepDetail::class, 'resep_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'resep_id');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }
}
