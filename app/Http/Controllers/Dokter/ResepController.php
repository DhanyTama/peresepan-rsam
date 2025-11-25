<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reseps = Resep::with(['dokter', 'pasien', 'details.obat'])
            ->where('dokter_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('resep.index', compact('reseps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pasien_id' => 'nullable|integer|exists:pasiens,id'
        ], [
            'pasien_id.exists' => 'Pasien tidak ditemukan.',
            'pasien_id.integer' => 'ID pasien harus angka.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('pasien.index')
                ->withErrors($validator)
                ->withInput();
        }

        $obats = Obat::all();
        if ($request->has('pasien_id')) {
            $pasien = Pasien::find($request->pasien_id);
            if (!$pasien) {
                return redirect()->route('pasien.index')->with('error', 'Pasien tidak ditemukan.');
            }

            return view('resep.create', compact('pasien', 'obats'));
        }
        $pasiens = Pasien::all();

        return view('resep.create', compact('pasiens', 'obats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pasien_id' => 'required',
            'items' => 'required|array|min:1',
            'items.*.obat_id' => 'required',
            'items.*.dosis' => 'required',
            'items.*.jumlah' => 'required|integer|min:1'
        ], [
            'pasien_id.required' => 'Pasien harus dipilih.',
            'items.*.obat_id.required' => 'Obat harus dipilih.',
            'items.*.dosis.required' => 'Dosis harus diisi.',
            'items.*.jumlah.required' => 'Jumlah harus diisi.',
            'items.*.jumlah.integer' => 'Jumlah harus berupa angka.',
            'items.*.jumlah.min' => 'Jumlah minimal adalah 1.',
        ]);

        foreach ($request->items as $index => $item) {
            $obat = Obat::find($item['obat_id']);

            if (!$obat) {
                return back()->withErrors([
                    "items.$index.obat_id" => "Obat tidak ditemukan."
                ])->withInput();
            }

            if ($item['jumlah'] > $obat->stok) {
                return back()->withErrors([
                    "items.$index.jumlah" => "Jumlah obat melebihi stok tersedia ({$obat->stok})."
                ])->withInput();
            }
        }

        $resep = Resep::create([
            'pasien_id' => $request->pasien_id,
            'dokter_id' => auth()->id(),
            'status' => 'Draft',
        ]);

        foreach ($request->items as $item) {
            $resep->details()->create($item);
        }

        return redirect()->route('resep.index')->with('success', 'Resep berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resep = Resep::find($id);
        if (!$resep) {
            return redirect()->route('resep.index')->with('error', 'Resep tidak ditemukan.');
        }
        $resep->delete();
        return redirect()->route('resep.index')->with('success', 'Resep berhasil dihapus.');
    }
}
