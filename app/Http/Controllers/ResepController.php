<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class ResepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reseps = Resep::with(['dokter', 'pasien', 'details.obat']);
        if (auth()->user()->role === 'dokter') {
            $reseps = $reseps->where('dokter_id', auth()->user()->id);
        }

        if ($request->search) {
            $reseps->where('nomor_resep', 'LIKE', '%' . $request->search . '%')
                ->orWhereHas('pasien', function ($q) use ($request) {
                    $q->where('nama_pasien', 'LIKE', '%' . $request->search . '%');
                })
                ->orWhereHas('dokter', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search . '%');
                });
        }

        if ($request->status) {
            $reseps->where('status', $request->status);
        }
        $reseps = $reseps->latest()->get();

        foreach ($reseps as $resep) {
            $resep->over_stock_flag = false;
            if ($resep->status === 'Draft') {
                foreach ($resep->details as $detail) {
                    $detail->over_stock_flag = $detail->jumlah > $detail->obat->stok;

                    if ($detail->over_stock_flag) {
                        $resep->over_stock_flag = true;
                    }
                }
            }
        }

        if ($request->overstock == "1") {
            $reseps = $reseps->filter(function ($r) {
                return $r->over_stock_flag === true;
            });
        }

        $page = request()->get('page', 1);
        $perPage = 10;
        $paged = new LengthAwarePaginator(
            $reseps->slice(($page - 1) * $perPage, $perPage)->values(),
            $reseps->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('resep.index', [
            'reseps' => $paged
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (auth()->user()->role !== 'dokter') {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'kode_pasien' => 'nullable|exists:pasiens,kode_pasien',
        ], [
            'kode_pasien.exists' => 'Pasien tidak ditemukan.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('pasien.index')
                ->withErrors($validator)
                ->withInput();
        }

        $obats = Obat::all();
        if ($request->has('kode_pasien')) {
            $pasien = Pasien::where('kode_pasien', $request->kode_pasien)->first();
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
        if (auth()->user()->role !== 'dokter') {
            abort(403);
        }

        $request->validate([
            'kode_pasien' => 'required',
            'items' => 'required|array|min:1',
            'items.*.kode_obat' => 'required',
            'items.*.dosis' => 'required',
            'items.*.jumlah' => 'required|integer|min:1'
        ], [
            'kode_pasien.required' => 'Pasien harus dipilih.',
            'items.*.kode_obat.required' => 'Obat harus dipilih.',
            'items.*.dosis.required' => 'Dosis harus diisi.',
            'items.*.jumlah.required' => 'Jumlah harus diisi.',
            'items.*.jumlah.integer' => 'Jumlah harus berupa angka.',
            'items.*.jumlah.min' => 'Jumlah minimal adalah 1.',
        ]);

        $pasien = Pasien::where('kode_pasien', $request->kode_pasien)->first();
        if (!$pasien) {
            return redirect()->route('pasien.index')->with('error', 'Pasien tidak ditemukan.');
        }

        $createDetailReseps = [];
        foreach ($request->items as $index => $item) {
            $obat = Obat::where('kode_obat', $item['kode_obat'])->first();

            if (!$obat) {
                return back()->withErrors([
                    "items.$index.kode_obat" => "Obat tidak ditemukan."
                ])->withInput();
            }

            if ($item['jumlah'] > $obat->stok) {
                return back()->withErrors([
                    "items.$index.jumlah" => "Jumlah obat melebihi stok tersedia ({$obat->stok})."
                ])->withInput();
            }

            $createDetailReseps[] = [
                'obat_id' => $obat->id,
                'dosis' => $item['dosis'],
                'jumlah' => $item['jumlah'],
            ];
        }

        $resep = Resep::create([
            'pasien_id' => $pasien->id,
            'dokter_id' => auth()->user()->id,
            'status' => 'Draft',
        ]);

        foreach ($createDetailReseps as $item) {
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
        if (auth()->user()->role !== 'dokter') {
            abort(403);
        }

        $resep = Resep::with('details')->where('nomor_resep', $id)->first();
        if (!$resep) {
            return redirect()->route('resep.index')->with('error', 'Resep tidak ditemukan.');
        }

        $obats = Obat::all();

        return view('resep.edit', compact('resep', 'obats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (auth()->user()->role !== 'dokter') {
            abort(403);
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.kode_obat' => 'required',
            'items.*.dosis' => 'required',
            'items.*.jumlah' => 'required|integer|min:1'
        ], [
            'items.*.kode_obat.required' => 'Obat harus dipilih.',
            'items.*.dosis.required' => 'Dosis harus diisi.',
            'items.*.jumlah.required' => 'Jumlah harus diisi.',
            'items.*.jumlah.integer' => 'Jumlah harus berupa angka.',
            'items.*.jumlah.min' => 'Jumlah minimal adalah 1.',
        ]);

        $createDetailReseps = [];
        foreach ($request->items as $index => $item) {
            $obat = Obat::where('kode_obat', $item['kode_obat'])->first();

            if (!$obat) {
                return back()->withErrors([
                    "items.$index.kode_obat" => "Obat tidak ditemukan."
                ])->withInput();
            }

            if ($item['jumlah'] > $obat->stok) {
                return back()->withErrors([
                    "items.$index.jumlah" => "Jumlah obat melebihi stok tersedia ({$obat->stok})."
                ])->withInput();
            }

            $createDetailReseps[] = [
                'obat_id' => $obat->id,
                'dosis' => $item['dosis'],
                'jumlah' => $item['jumlah'],
            ];
        }

        $resep = Resep::where('nomor_resep', $id)->first();
        if (!$resep) {
            return redirect()->route('resep.index')->with('error', 'Resep tidak ditemukan.');
        }

        $resep->details()->delete();
        foreach ($createDetailReseps as $item) {
            $resep->details()->create($item);
        }

        return redirect()->route('resep.index')->with('success', 'Resep berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (auth()->user()->role !== 'dokter') {
            abort(403);
        }

        $resep = Resep::where('nomor_resep', $id)->first();
        if (!$resep) {
            return redirect()->route('resep.index')->with('error', 'Resep tidak ditemukan.');
        }
        $resep->delete();
        return redirect()->route('resep.index')->with('success', 'Resep berhasil dihapus.');
    }

    public function process(string $id)
    {
        if (auth()->user()->role !== 'apoteker') {
            abort(403);
        }

        $resep = Resep::where(['nomor_resep' => $id, 'status' => 'Draft'])->first();
        if (!$resep) {
            return redirect()->route('resep.index')->with('error', 'Resep tidak ditemukan.');
        }

        foreach ($resep->details as $key => $detail) {
            $obat = $detail->obat;
            if (!$obat) {
                return back()->withErrors([
                    "detail.$key.kode_obat" => "Obat tidak ditemukan."
                ])->withInput();
            }

            $totalJumlahObatDiproses = $obat->resepDetails()
                ->join('reseps', 'reseps.id', '=', 'resep_details.resep_id')
                ->where('reseps.status', 'Diproses')
                ->sum('resep_details.jumlah');

            $available_stock = $obat->stok - $totalJumlahObatDiproses;
            if ($detail->jumlah > $available_stock) {
                return back()->withErrors([
                    "details.$key.jumlah" => "Jumlah obat {$obat->nama_obat} melebihi stok tersedia ({$obat->stok}) + obat yang diproses ({$totalJumlahObatDiproses})."
                ])->withInput();
            }
        }

        $resep->status = 'Diproses';
        if ($resep->save()) {
            return redirect()->route('resep.index')->with('success', 'Resep berhasil diproses.');
        }
        return redirect()->route('resep.index')->with('error', 'Resep gagal diproses.');
    }
}
