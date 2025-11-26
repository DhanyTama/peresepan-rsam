<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pasien') }}
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
                                        <th class="p-2">Kode</th>
                                        <th class="p-2">Nama</th>
                                        <th class="p-2">Stok</th>
                                        <th class="p-2">Harga</th>
                                        <th class="p-2">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($obats as $key => $obat)
                                        <tr class="border-b">
                                            <td class="p-2">{{ $obats->firstItem() + $key }}</td>
                                            <td class="p-2">{{ $obat->kode_obat }}</td>
                                            <td class="p-2">{{ $obat->nama_obat }}</td>
                                            <td class="p-2">{{ $obat->stok }}</td>
                                            <td class="p-2">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}
                                            </td>
                                            <td class="p-3">
                                                <button
                                                    onclick="document.getElementById('detailmodal-{{ $obat->id }}').classList.remove('hidden');"
                                                    class="px-4 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-600 hover:text-white">
                                                    Detail
                                                </button>

                                                <div id="detailmodal-{{ $obat->id }}"
                                                    class="hidden fixed inset-0 z-50">
                                                    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                                                    <div class="flex items-center justify-center min-h-screen">
                                                        <div
                                                            class="bg-white dark:bg-gray-800 p-6 rounded-lg w-11/12 max-w-lg max-h-[80vh] overflow-auto relative z-10">

                                                            <h2 class="text-xl font-semibold mb-4">Detail Obat</h2>

                                                            <p><strong>Kode:</strong> {{ $obat->kode_obat }}</p>
                                                            <p><strong>Nama:</strong> {{ $obat->nama_obat }}</p>
                                                            <p><strong>Tanggal Perubahan:</strong>
                                                                {{ $obat->updated_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                                            </p>

                                                            <form class="mt-5"
                                                                action="{{ route('obat.update', $obat->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')

                                                                <div class="mb-2">
                                                                    <label class="font-semibold">Stok</label>
                                                                    <input type="number" name="stok"
                                                                        value="{{ $obat->stok }}"
                                                                        class="w-full border rounded p-2 text-black"
                                                                        required>
                                                                </div>

                                                                <div class="mb-2">
                                                                    <label class="font-semibold">Harga Jual</label>
                                                                    <input type="text"
                                                                        id="harga-{{ $obat->id }}"
                                                                        name="harga_jual"
                                                                        value="{{ number_format($obat->harga_jual, 0, ',', '') }}"
                                                                        class="w-full border rounded p-2 text-black"
                                                                        required>
                                                                </div>

                                                                <div class="mt-4 flex justify-end gap-2">
                                                                    <button type="button"
                                                                        onclick="document.getElementById('detailmodal-{{ $obat->id }}').classList.add('hidden');"
                                                                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Tutup</button>

                                                                    <button type="submit"
                                                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                                                                </div>
                                                            </form>
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
                            {{ $obats->onEachSide(1)->links() }}
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
