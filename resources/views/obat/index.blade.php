<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Obat') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="overflow-y-auto max-h-[69vh]">

                        <div class="my-3 flex flex-wrap items-center justify-between gap-4">
                            <a href="{{ route('obat.create') }}" class="button-success">
                                <i class="fas fa-plus"></i> Tambah Obat
                            </a>
                        </div>

                        <div class="overflow-y-auto" style="max-height: 54vh;">
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
                                            <td class="p-3 flex gap-2">

                                                <div x-data="{ openDetail: false }">

                                                    <button @click="openDetail = true" class="button-detail">
                                                        Detail
                                                    </button>

                                                    <div x-show="openDetail" x-cloak x-transition
                                                        class="fixed inset-0 z-50">
                                                        <div class="absolute inset-0 bg-black bg-opacity-50"
                                                            @click="openDetail = false"></div>

                                                        <div class="flex items-center justify-center min-h-screen p-4">
                                                            <div @click.stop
                                                                class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-xl max-h-[80vh] overflow-auto relative z-10">

                                                                <h2 class="text-xl font-semibold mb-4">Detail Obat</h2>

                                                                <p><strong>Kode:</strong> {{ $obat->kode_obat }}</p>
                                                                <p><strong>Nama:</strong> {{ $obat->nama_obat }}</p>
                                                                <p><strong>Tanggal Perubahan:</strong>
                                                                    {{ $obat->updated_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                                                </p>

                                                                <form class="mt-5"
                                                                    action="{{ route('obat.update', $obat->kode_obat) }}"
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
                                                                            id="harga-{{ $obat->kode_obat }}"
                                                                            name="harga_jual"
                                                                            value="{{ number_format($obat->harga_jual, 0, ',', '') }}"
                                                                            class="w-full border rounded p-2 text-black"
                                                                            required>
                                                                    </div>

                                                                    <div class="mt-4 flex justify-end gap-2">
                                                                        <button type="button"
                                                                            @click="openDetail = false"
                                                                            class="button-secondary">Tutup</button>

                                                                        <button type="submit"
                                                                            class="button-success">Simpan</button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div x-data="{ openDeleteConfirm: false }">

                                                    <button @click="openDeleteConfirm = true" class="button-danger">
                                                        Hapus
                                                    </button>

                                                    <div x-show="openDeleteConfirm" x-cloak x-transition
                                                        class="fixed inset-0 z-50">
                                                        <div class="absolute inset-0 bg-black bg-opacity-50"
                                                            @click="openDeleteConfirm = false"></div>

                                                        <div class="flex items-center justify-center min-h-screen p-4">
                                                            <div @click.stop
                                                                class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-xl max-h-[80vh] overflow-auto relative z-10">

                                                                <h2 class="text-lg font-semibold mb-4">Konfirmasi
                                                                    Hapus
                                                                </h2>

                                                                <p>
                                                                    Apakah Anda yakin ingin menghapus obat
                                                                    <strong>{{ $obat->kode_obat }}</strong>?
                                                                </p>

                                                                <div class="mt-6 flex justify-end gap-2">
                                                                    <button type="button"
                                                                        @click="openDeleteConfirm = false"
                                                                        class="button-secondary">
                                                                        Batal
                                                                    </button>

                                                                    <form
                                                                        action="{{ route('obat.destroy', $obat->kode_obat) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="button-danger">
                                                                            Hapus
                                                                        </button>
                                                                    </form>
                                                                </div>

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
                            {{ $obats->onEachSide(1)->links() }}
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
