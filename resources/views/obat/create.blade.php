<x-app-layout :backUrl="route('obat.index')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Obat') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg max-h-[75vh]">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('obat.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="font-semibold mb-1 block">Nama Obat</label>
                            <input type="text" name="nama_obat" value="{{ old('nama_obat') }}"
                                class="w-full border rounded p-2 text-black dark:bg-gray-700 dark:text-white @error('nama_obat') border-red-500 @enderror"
                                placeholder="Contoh: Paracetamol" required>

                            @error('nama_obat')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="font-semibold mb-1 block">Stok</label>
                            <input type="number" name="stok" value="{{ old('stok') }}"
                                class="w-full border rounded p-2 text-black dark:bg-gray-700 dark:text-white @error('stok') border-red-500 @enderror"
                                placeholder="Contoh: 30" required>

                            @error('stok')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="font-semibold mb-1 block">Harga Jual</label>
                            <input type="number" name="harga_jual" value="{{ old('harga_jual') }}"
                                class="w-full border rounded p-2 text-black dark:bg-gray-700 dark:text-white @error('harga_jual') border-red-500 @enderror"
                                placeholder="Contoh: 15000" required>

                            @error('harga_jual')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                                Simpan Obat
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
