<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-gauge-high text-indigo-500"></i>
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-10 rounded-2xl shadow-lg mb-10">
                <h1 class="text-3xl font-bold mb-2">
                    Selamat datang, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="opacity-90 text-lg">
                    Anda login sebagai <b class="font-semibold">{{ strtoupper(auth()->user()->role) }}</b>.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Resep</p>
                            <h3 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                                {{ $resep_count ?? 0 }}
                            </h3>
                        </div>
                        <div class="p-4 bg-indigo-100 dark:bg-indigo-900 rounded-xl">
                            <i class="fa-solid fa-notes-medical text-indigo-600 dark:text-indigo-300 text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Jenis Obat</p>
                            <h3 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                                {{ $obat_count ?? 0 }}
                            </h3>
                        </div>
                        <div class="p-4 bg-green-100 dark:bg-green-900 rounded-xl">
                            <i class="fa-solid fa-capsules text-green-600 dark:text-green-300 text-3xl"></i>
                        </div>
                    </div>
                </div>

                @if (auth()->user()->role === 'dokter')
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-xl transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Jumlah Pasien</p>
                                <h3 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                                    {{ $pasien_count ?? 0 }}
                                </h3>
                            </div>
                            <div class="p-4 bg-pink-100 dark:bg-pink-900 rounded-xl">
                                <i class="fa-solid fa-hospital-user text-pink-600 dark:text-pink-300 text-3xl"></i>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <div class="mt-12">
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-5">Aksi Cepat</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                    @if (auth()->user()->role === 'dokter')
                        <a href="{{ route('resep.create') }}"
                            class="flex items-center gap-4 bg-indigo-600 hover:bg-indigo-700 text-white p-5 rounded-xl shadow-lg transition transform hover:-translate-y-1">
                            <i class="fa-solid fa-plus text-2xl"></i>
                            <span class="text-lg font-medium">Buat Resep Baru</span>
                        </a>

                        <a href="{{ route('pasien.index') }}"
                            class="flex items-center gap-4 bg-green-600 hover:bg-green-700 text-white p-5 rounded-xl shadow-lg transition transform hover:-translate-y-1">
                            <i class="fa-solid fa-hospital-user text-2xl"></i>
                            <span class="text-lg font-medium">Lihat Semua Pasien</span>
                        </a>
                    @endif

                    <a href="{{ route('resep.index') }}"
                        class="flex items-center gap-4 bg-gray-800 hover:bg-black text-white p-5 rounded-xl shadow-lg transition transform hover:-translate-y-1">
                        <i class="fa-solid fa-file-medical text-2xl"></i>
                        <span class="text-lg font-medium">Lihat Semua Resep</span>
                    </a>

                    @if (auth()->user()->role === 'apoteker')
                        <a href="{{ route('obat.index') }}"
                            class="flex items-center gap-4 bg-green-600 hover:bg-green-700 text-white p-5 rounded-xl shadow-lg transition transform hover:-translate-y-1">
                            <i class="fa-solid fa-tablets text-2xl"></i>
                            <span class="text-lg font-medium">Kelola Obat</span>
                        </a>

                        <a href="{{ route('transaksi.index') }}"
                            class="flex items-center gap-4 bg-indigo-600 hover:bg-indigo-700 text-white p-5 rounded-xl shadow-lg transition transform hover:-translate-y-1">
                            <i class="fa-solid fa-money-check text-2xl"></i>
                            <span class="text-lg font-medium">Lihat Semua Transaksi</span>
                        </a>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
