<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Resep;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $resep_count = new Resep();
        if (auth()->user()->role === 'dokter') {
            $resep_count = $resep_count->where('dokter_id', auth()->user()->id);
        }
        $resep_count = $resep_count->count();
        $obat_count = Obat::count();
        $pasien_count = Pasien::count();

        return view('dashboard', compact('resep_count', 'obat_count', 'pasien_count'));
    }
}
