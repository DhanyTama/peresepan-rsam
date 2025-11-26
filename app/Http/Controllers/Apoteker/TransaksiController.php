<?php

namespace App\Http\Controllers\Apoteker;

use App\Http\Controllers\Controller;
use App\Models\Resep;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->role === 'apoteker') {
            abort(403);
        }

        $transactions = Transaction::with(['resep.dokter', 'resep.pasien', 'apoteker'])
            ->latest()
            ->paginate(10);

        return view('transaksi.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->role === 'apoteker') {
            abort(403);
        }

        $request->validate([
            'nomor_resep' => 'required',
            'details' => 'required|array',
            'details.*.jumlah' => 'required|integer|min:1',
        ]);

        $resep = Resep::where('nomor_resep', $request->nomor_resep)->first();
        if (!$resep) {
            return back()->withErrors([
                'nomor_resep' => 'Resep tidak ditemukan.'
            ]);
        }

        if ($resep->status == 'Draft') {
            return back()->withErrors([
                'nomor_resep' => 'Resep belum diproses.'
            ]);
        }

        if ($resep->status == 'Completed') {
            return back()->withErrors([
                'nomor_resep' => 'Resep sudah selesai.'
            ]);
        }

        $transaksi = new Transaction();
        $transaksi->resep_id = $resep->id;
        $transaksi->apoteker_id = auth()->user()->id;
        $transaksi->total_harga = 0;

        $detailTransaksiData = [];

        foreach ($request->details as $index => $detailData) {
            $detailResep = $resep->details()->where('id', $index)->first();
            if (!$detailResep) {
                return back()->withErrors([
                    "details.$index" => "Detail resep tidak ditemukan."
                ])->withInput();
            }

            $obat = $detailResep->obat;
            if (!$obat) {
                return back()->withErrors([
                    "items.$index.kode_obat" => "Obat tidak ditemukan."
                ])->withInput();
            }

            if ($detailData['jumlah'] > $obat->stok) {
                return back()->withErrors([
                    "details.$index.jumlah" => "Inputan jumlah obat {$obat->nama_obat} melebihi stok tersedia ({$obat->stok})."
                ])->withInput();
            }

            $detailResep->jumlah = $detailData['jumlah'];
            $detailResep->save();

            $subtotal = $obat->harga_jual * $detailData['jumlah'];
            $detailTransaksiData[] = [
                'obat_id' => $obat->id,
                'jumlah' => $detailData['jumlah'],
                'harga_satuan' => $obat->harga_jual,
                'subtotal' => $subtotal,
            ];

            $obat->stok = max(0, $obat->stok - $detailData['jumlah']);
            $obat->save();

            $transaksi->total_harga += $subtotal;
        }

        if ($transaksi->save()) {
            foreach ($detailTransaksiData as $data) {
                $data['transaction_id'] = $transaksi->id;
                TransactionDetail::create($data);
            }

            $resep->status = 'Completed';
            if ($resep->save()) {
                return redirect()->route('transaksi.index')->with('success', 'Transaksi selesai.');
            }
            return back()->withErrors([
                'general' => 'Gagal memperbarui status resep.'
            ])->withInput();
        }
        return back()->withErrors([
            'general' => 'Gagal membuat transaksi.'
        ])->withInput();
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
        abort(404);
    }
}
