<?php

namespace App\Http\Controllers\Apoteker;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obats = Obat::orderBy('id')->paginate(10);

        return view('obat.index', compact('obats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('obat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat'   => 'required|string|max:255',
            'stok'        => 'required|integer|min:0',
            'harga_jual'  => 'required|numeric|min:0',
        ]);

        Obat::create($validated);

        return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan.');
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
        $request->validate([
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $obat = Obat::where('kode_obat', $id)->first();
        if (!$obat) {
            return redirect()->route('obat.index')->with('error', 'Obat ' . $obat->kode_obat . ' - ' . $obat->nama_obat . ' tidak ditemukan.');
        }
        $obat->harga_jual = $request->harga_jual;
        $obat->stok = $request->stok;
        $obat->save();

        return redirect()->route('obat.index')->with('success', 'Obat ' . $obat->kode_obat . ' - ' . $obat->nama_obat . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (auth()->user()->role !== 'apoteker') {
            abort(403);
        }

        $obat = Obat::where('kode_obat', $id)->first();
        if (!$obat) {
            return redirect()->route('obat.index')->with('error', 'Obat tidak ditemukan.');
        }
        $obat->delete();
        return redirect()->route('obat.index')->with('success', 'Obat ' . $obat->nama_obat . ' berhasil dihapus.');
    }
}
