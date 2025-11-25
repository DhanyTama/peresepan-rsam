<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Resep') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg max-h-[75vh]">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('resep.create') }}"
                        class="px-4 py-2 border border-green-600 text-green-600 rounded-md hover:bg-green-600 hover:text-white">
                        Buat Resep
                    </a>

                    <table class="table-auto w-full mt-5">
                        <thead>
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
                                    <td class="p-2">{{ $key + 1 }}</td>
                                    <td class="p-2">{{ $resep->nomor_resep }}</td>
                                    <td class="p-2">{{ $resep->pasien->nama_pasien }}</td>
                                    <td class="p-2">{{ $resep->dokter->name }}</td>
                                    <td class="p-2">{{ $resep->status }}</td>
                                    <td class="p-2">
                                        {{ $resep->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="p-3">
                                        <button
                                            onclick="document.getElementById('detailmodal-{{ $resep->id }}').classList.remove('hidden');"
                                            class="px-4 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-600 hover:text-white">
                                            Detail
                                        </button>
                                        <button
                                            onclick="document.getElementById('deletemodal-{{ $resep->id }}').classList.remove('hidden');"
                                            class="px-4 py-2 border border-red-600 text-red-600 rounded-md hover:bg-red-600 hover:text-white">
                                            Hapus
                                        </button>

                                        <div id="detailmodal-{{ $resep->id }}" class="hidden fixed inset-0 z-50">
                                            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                                            <div class="flex items-center justify-center min-h-screen">
                                                <div
                                                    class="bg-white dark:bg-gray-800 p-6 rounded-lg w-11/12 max-w-lg max-h-[80vh] overflow-auto relative z-10">

                                                    <h2 class="text-xl font-semibold mb-4">Detail Resep</h2>

                                                    <p><strong>Nomor Resep:</strong> {{ $resep->nomor_resep }}</p>
                                                    <p><strong>Pasien:</strong> {{ $resep->pasien->nama_pasien }}</p>
                                                    <p><strong>Dokter:</strong> {{ $resep->dokter->name }}</p>
                                                    <p><strong>Status:</strong> {{ $resep->status }}</p>
                                                    <p><strong>Tanggal Dibuat:</strong>
                                                        {{ $resep->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                                    </p>

                                                    <h3 class="mt-4 font-semibold">Obat</h3>
                                                    <table class="table-auto w-full">
                                                        <thead>
                                                            <tr class="border-b text-left">
                                                                <th class="p-2">#</th>
                                                                <th class="p-2">Nama Obat</th>
                                                                <th class="p-2">Dosis</th>
                                                                <th class="p-2">Jumlah</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach ($resep->details as $row => $detail)
                                                                <tr class="border-b">
                                                                    <td class="p-2">{{ $row + 1 }}</td>
                                                                    <td class="p-2">
                                                                        {{ $detail->obat->nama_obat }}</td>
                                                                    <td class="p-2">{{ $detail->dosis }}</td>
                                                                    <td class="p-2">{{ $detail->jumlah }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>

                                                    <div class="mt-4 text-right">
                                                        <button
                                                            onclick="document.getElementById('detailmodal-{{ $resep->id }}').classList.add('hidden');"
                                                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                                            Tutup
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="deletemodal-{{ $resep->id }}" class="hidden fixed inset-0 z-50">
                                            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                                            <div class="flex items-center justify-center min-h-screen">
                                                <div
                                                    class="bg-white dark:bg-gray-800 p-6 rounded-lg w-11/12 max-w-lg max-h-[80vh] overflow-auto relative z-10">

                                                    <h2 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h2>
                                                    <p>Apakah Anda yakin ingin menghapus resep
                                                        <strong>{{ $resep->nomor_resep }}</strong>?
                                                    </p>
                                                    <div class="mt-4 flex justify-end gap-2">
                                                        <button
                                                            onclick="document.getElementById('deletemodal-{{ $resep->id }}').classList.add('hidden');"
                                                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>

                                                        <form action="{{ route('resep.destroy', $resep->id) }}"
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-5">
                        {{ $reseps->onEachSide(1)->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
