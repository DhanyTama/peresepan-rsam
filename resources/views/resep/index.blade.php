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

                        <div class="my-3 flex flex-wrap items-center justify-between gap-4">
                            @if (auth()->user()->role === 'dokter')
                                <a href="{{ route('resep.create') }}" class="button-success">
                                    <i class="fas fa-plus"></i> Buat Resep
                                </a>
                            @endif

                            <form method="GET" action="{{ route('resep.index') }}"
                                class="flex flex-wrap items-center gap-3">

                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari resep / pasien / dokter..."
                                    class="border p-2 rounded w-64 dark:bg-gray-700 dark:text-white"
                                    oninput="this.form.submit()">

                                <select name="status" class="border p-2 rounded dark:bg-gray-700 dark:text-white"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>
                                        Draft</option>
                                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>
                                        Diproses</option>
                                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>

                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="overstock" value="1"
                                        {{ request('overstock') ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span>Melebihi Stok</span>
                                </label>

                            </form>

                        </div>

                        <div class="overflow-y-auto" style="max-height: 54vh;">


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
                                            <td class="p-2 status-{{ ucFirst($resep->status) }}">
                                                {{ ucFirst($resep->status) }}</td>
                                            <td class="p-2">
                                                {{ $resep->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
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

                                                                <h2 class="text-xl font-semibold mb-4">Detail Resep</h2>

                                                                <p><strong>Nomor Resep:</strong>
                                                                    {{ $resep->nomor_resep }}</p>
                                                                <p><strong>Pasien:</strong>
                                                                    {{ $resep->pasien->nama_pasien }}</p>
                                                                <p><strong>Dokter:</strong> {{ $resep->dokter->name }}
                                                                </p>
                                                                <p><strong>Status:</strong>
                                                                    {{ ucFirst($resep->status) }}</p>
                                                                <p><strong>Tanggal Dibuat:</strong>
                                                                    {{ $resep->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                                                </p>

                                                                <h3 class="mt-4 font-semibold">Obat</h3>

                                                                @if (auth()->user()->role === 'apoteker')
                                                                    @if (ucFirst($resep->status) === 'Draft')
                                                                        <form class="mt-5"
                                                                            action="{{ route('resep.process', $resep->nomor_resep) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                        @else
                                                                            @if (ucFirst($resep->status) === 'Diproses')
                                                                                <form class="mt-5"
                                                                                    action="{{ route('transaksi.store') }}"
                                                                                    method="POST">
                                                                                    @csrf
                                                                            @endif
                                                                    @endif
                                                                @endif

                                                                <table class="table-auto w-full mt-3">
                                                                    <thead>
                                                                        <tr class="border-b text-left">
                                                                            <th class="p-2">#</th>
                                                                            <th class="p-2">Nama Obat</th>
                                                                            <th class="p-2">Dosis</th>
                                                                            <th class="p-2">Jumlah</th>
                                                                            <th class="p-2">Stok</th>
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
                                                                                    <input type="hidden"
                                                                                        name="nomor_resep"
                                                                                        value="{{ $resep->nomor_resep }}">
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

                                                                <div class="mt-6 flex justify-end gap-2">

                                                                    <button type="button" @click="openDetail = false"
                                                                        class="button-secondary">
                                                                        Tutup
                                                                    </button>

                                                                    @if (auth()->user()->role === 'apoteker')
                                                                        @if (ucFirst($resep->status) === 'Draft')
                                                                            <button type="submit"
                                                                                class="button-success">
                                                                                Proses Resep
                                                                            </button>
                                                                        @else
                                                                            @if (ucFirst($resep->status) === 'Diproses')
                                                                                <button type="submit"
                                                                                    class="button-success">
                                                                                    Selesaikan Transaksi
                                                                                </button>
                                                                            @endif
                                                                        @endif
                                                                    @endif

                                                                    @if (auth()->user()->role === 'dokter' && ucFirst($resep->status) === 'Draft')
                                                                        <a href="{{ route('resep.edit', $resep->nomor_resep) }}"
                                                                            class="button-success">
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
                                                </div>

                                                @if (auth()->user()->role === 'dokter' && ucFirst($resep->status) === 'Draft')
                                                    <div x-data="{ openDeleteConfirm: false }">

                                                        <button @click="openDeleteConfirm = true"
                                                            class="button-danger">
                                                            Hapus
                                                        </button>

                                                        <div x-show="openDeleteConfirm" x-cloak x-transition
                                                            class="fixed inset-0 z-50">
                                                            <div class="absolute inset-0 bg-black bg-opacity-50"
                                                                @click="openDeleteConfirm = false"></div>

                                                            <div
                                                                class="flex items-center justify-center min-h-screen p-4">
                                                                <div @click.stop
                                                                    class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-xl max-h-[80vh] overflow-auto relative z-10">

                                                                    <h2 class="text-lg font-semibold mb-4">Konfirmasi
                                                                        Hapus
                                                                    </h2>

                                                                    <p>
                                                                        Apakah Anda yakin ingin menghapus resep
                                                                        <strong>{{ $resep->nomor_resep }}</strong>?
                                                                    </p>

                                                                    <div class="mt-6 flex justify-end gap-2">
                                                                        <button type="button"
                                                                            @click="openDelete = false"
                                                                            class="button-secondary">
                                                                            Batal
                                                                        </button>

                                                                        <form
                                                                            action="{{ route('resep.destroy', $resep->nomor_resep) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="button-danger">
                                                                                Hapus
                                                                            </button>
                                                                        </form>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($resep->over_stock_flag)
                                                    <span class="text-red-600 font-semibold text-sm">⚠ Melebihi
                                                        stok!</span>
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
