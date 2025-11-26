<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="overflow-y-auto" style="max-height: 69vh;">

                        <div class="overflow-y-auto" style="max-height: 62vh">
                            <table class="table-auto w-full">
                                <thead class="dark:bg-gray-800 sticky top-0 z-10">
                                    <tr class="border-b text-left">
                                        <th class="p-2">#</th>
                                        <th class="p-2">Nomor Transaksi</th>
                                        <th class="p-2">Nomor Resep</th>
                                        <th class="p-2">Total Harga</th>
                                        <th class="p-2">Dibuat Oleh</th>
                                        <th class="p-2">Tanggal Dibuat</th>
                                        <th class="p-2">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($transactions as $key => $transaction)
                                        <tr class="border-b">
                                            <td class="p-2">{{ $transactions->firstItem() + $key }}</td>
                                            <td class="p-2">{{ $transaction->nomor_transaksi }}</td>
                                            <td class="p-2">{{ $transaction->resep->nomor_resep }}</td>
                                            <td class="p-2">Rp
                                                {{ number_format($transaction->total_harga, 0, ',', '.') }}
                                            </td>
                                            <td class="p-2">{{ $transaction->apoteker->name }}</td>
                                            <td class="p-2">
                                                {{ $transaction->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="p-3">
                                                <button
                                                    onclick="document.getElementById('detailmodal-{{ $transaction->id }}').classList.remove('hidden');"
                                                    class="button-detail">
                                                    Detail
                                                </button>

                                                <div id="detailmodal-{{ $transaction->id }}"
                                                    class="hidden fixed inset-0 z-50">
                                                    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                                                    <div class="flex items-center justify-center min-h-screen">
                                                        <div
                                                            class="bg-white dark:bg-gray-800 p-6 rounded-lg w-11/12 md:w-3/4 lg:w-2/3 max-w-4xl max-h-[90vh] overflow-auto relative z-10">

                                                            <h2 class="text-xl font-semibold mb-4">Detail transaksi</h2>

                                                            <p><strong>Nomor Transaksi:</strong>
                                                                {{ $transaction->nomor_transaksi }}
                                                            </p>
                                                            <p><strong>Nomor Resep:</strong>
                                                                {{ $transaction->resep->nomor_resep }}
                                                            </p>
                                                            <p><strong>Apoteker:</strong>
                                                                {{ $transaction->apoteker->name }}
                                                            </p>
                                                            <p><strong>Tanggal Pembuatan:</strong>
                                                                {{ $transaction->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                                            </p>

                                                            <h3 class="mt-4 font-semibold">Obat</h3>

                                                            @if (auth()->user()->role === 'apoteker')
                                                                <form class="mt-5"
                                                                    action="{{ route('transaksi.store') }}"
                                                                    method="POST">
                                                                    @csrf
                                                            @endif

                                                            <table class="table-auto w-full">
                                                                <thead>
                                                                    <tr class="border-b text-left">
                                                                        <th class="p-2">#</th>
                                                                        <th class="p-2">Nama Obat</th>
                                                                        <th class="p-2">Jumlah Obat</th>
                                                                        <th class="p-2">Harga Satuan</th>
                                                                        <th class="p-2">Subtotal</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    @foreach ($transaction->details as $row => $detail)
                                                                        <tr class="border-b">
                                                                            <td class="p-2">{{ $row + 1 }}
                                                                            </td>
                                                                            <td class="p-2">
                                                                                {{ $detail->obat->nama_obat }}</td>
                                                                            <td class="p-2">
                                                                                {{ $detail->jumlah }}</td>
                                                                            <td class="p-2">Rp
                                                                                {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                                                            </td>
                                                                            <td class="p-2">Rp
                                                                                {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                            <div class="mt-4 flex justify-end gap-2">
                                                                <button type="button"
                                                                    onclick="document.getElementById('detailmodal-{{ $transaction->id }}').classList.add('hidden');"
                                                                    class="button-secondary">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-5">
                            {{ $transactions->onEachSide(1)->links() }}
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
