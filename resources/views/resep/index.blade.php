<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Resep') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="overflow-y-auto max-h-[69vh]">

                        @if (auth()->user()->role === 'dokter')
                            <div class="my-3">
                                <a href="{{ route('resep.create') }}"
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    <i class="fas fa-plus"></i>
                                    Buat Resep
                                </a>
                            </div>
                        @endif

                        <div class="overflow-y-auto"
                            style="max-height: {{ auth()->user()->role === 'dokter' ? '56vh' : '62vh' }};">
                            <table class="table-auto w-full">
                                <thead class="dark:bg-gray-800 sticky top-0 z-10">
                                    <tr class="border-b text-left">
                                        <th class="p-2">#</th>
                                        <th class="p-2">Nomor Resep</th>
                                        <th class="p-2">Pasien</th>
                                        <th class="p-2">Dokter</th>
                                        <th class="p-2">Status</th>
                                        <th class="p-2">Tanggal Dibuat</th>
                                        <th class="p-2">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($reseps as $key => $resep)
                                        <tr class="border-b">
                                            <td class="p-2">{{ $reseps->firstItem() + $key }}</td>
                                            <td class="p-2">{{ $resep->nomor_resep }}</td>
                                            <td class="p-2">{{ $resep->pasien->nama_pasien }}</td>
                                            <td class="p-2">{{ $resep->dokter->name }}</td>
                                            <td class="p-2">{{ $resep->status }}</td>
                                            <td class="p-2">
                                                {{ $resep->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="p-3">
                                                @if ($resep->over_stock_flag)
                                                    <span class="text-red-600 font-semibold text-sm">⚠ Jumlah obat
                                                        melebihi stok!</span>
                                                @endif
                                                <button
                                                    onclick="document.getElementById('detailmodal-{{ $resep->id }}').classList.remove('hidden');"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                    @if (auth()->user()->role === 'dokter')
                                                        Detail
                                                    @else
                                                        @if ($resep->status === 'Draft')
                                                            Buat Transaksi
                                                        @else
                                                            Detail
                                                        @endif
                                                    @endif
                                                </button>

                                                <div id="detailmodal-{{ $resep->id }}"
                                                    class="hidden fixed inset-0 z-50">
                                                    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                                                    <div class="flex items-center justify-center min-h-screen">
                                                        <div
                                                            class="bg-white dark:bg-gray-800 p-6 rounded-lg w-11/12 max-w-lg max-h-[80vh] overflow-auto relative z-10">

                                                            <h2 class="text-xl font-semibold mb-4">Detail Resep</h2>

                                                            <p><strong>Nomor Resep:</strong> {{ $resep->nomor_resep }}
                                                            </p>
                                                            <p><strong>Pasien:</strong>
                                                                {{ $resep->pasien->nama_pasien }}
                                                            </p>
                                                            <p><strong>Dokter:</strong> {{ $resep->dokter->name }}</p>
                                                            <p><strong>Status:</strong> {{ $resep->status }}</p>
                                                            <p><strong>Tanggal Dibuat:</strong>
                                                                {{ $resep->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
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
                                                                        <th class="p-2">Dosis</th>
                                                                        <th class="p-2">Jumlah Obat</th>
                                                                        <th class="p-2">Stok Obat</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                    @foreach ($resep->details as $row => $detail)
                                                                        <tr class="border-b">
                                                                            <td class="p-2">{{ $row + 1 }}
                                                                            </td>
                                                                            <td class="p-2">
                                                                                {{ $detail->obat->nama_obat }}</td>
                                                                            <td class="p-2">{{ $detail->dosis }}
                                                                            </td>
                                                                            <td class="p-2">
                                                                                {{ $detail->jumlah }}</td>

                                                                            @if (auth()->user()->role === 'apoteker')
                                                                                <input type="hidden" name="resep_id"
                                                                                    value="{{ $resep->id }}">
                                                                                <input type="hidden"
                                                                                    name="details[{{ $detail->id }}][jumlah]"
                                                                                    value="{{ $detail->jumlah }}">
                                                                            @endif

                                                                            <td class="p-2">
                                                                                {{ $detail->obat->stok }}</td>
                                                                            <td>
                                                                                @if ($detail->over_stock_flag)
                                                                                    <span
                                                                                        class="text-red-600 text-xs font-semibold">⚠
                                                                                        Melebihi stok</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                            <div class="mt-4 flex justify-end gap-2">
                                                                <button type="button"
                                                                    onclick="document.getElementById('detailmodal-{{ $resep->id }}').classList.add('hidden');"
                                                                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Tutup</button>
                                                                @if (auth()->user()->role === 'apoteker')
                                                                    @if ($resep->status === 'Draft')
                                                                        <button type="submit"
                                                                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                                                            Buat Transaksi
                                                                        </button>
                                                                    @endif
                                                                @else
                                                                    <a href="{{ route('resep.edit', ['id' => $resep->id]) }}"
                                                                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                                                        Edit Resep
                                                                    </a>
                                                                @endif
                                                            </div>
                                                            @if (auth()->user()->role === 'apoteker')
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                @if (auth()->user()->role === 'dokter')
                                                    <button
                                                        onclick="document.getElementById('deletemodal-{{ $resep->id }}').classList.remove('hidden');"
                                                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                                        Hapus
                                                    </button>

                                                    <div id="deletemodal-{{ $resep->id }}"
                                                        class="hidden fixed inset-0 z-50">
                                                        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                                                        <div class="flex items-center justify-center min-h-screen">
                                                            <div
                                                                class="bg-white dark:bg-gray-800 p-6 rounded-lg w-11/12 max-w-lg max-h-[80vh] overflow-auto relative z-10">

                                                                <h2 class="text-lg font-semibold mb-4">Konfirmasi Hapus
                                                                </h2>
                                                                <p>Apakah Anda yakin ingin menghapus resep
                                                                    <strong>{{ $resep->nomor_resep }}</strong>?
                                                                </p>
                                                                <div class="mt-4 flex justify-end gap-2">
                                                                    <button
                                                                        onclick="document.getElementById('deletemodal-{{ $resep->id }}').classList.add('hidden');"
                                                                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>

                                                                    <form
                                                                        action="{{ route('resep.destroy', $resep->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5">
                            {{ $reseps->onEachSide(1)->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
