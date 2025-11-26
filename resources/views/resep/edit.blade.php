<x-app-layout :backUrl="route('resep.index')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Resep') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('resep.update', $resep->nomor_resep) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="font-semibold block mb-1">Pasien</label>

                            <input type="hidden" name="kode_pasien" value="{{ $resep->pasien->kode_pasien }}">
                            <p class="p-2 bg-gray-200 dark:bg-gray-700 rounded text-black dark:text-white font-medium">
                                {{ $resep->pasien->kode_pasien }} - {{ $resep->pasien->nama_pasien }}
                            </p>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-lg">Detail Obat</h3>

                                <button type="button" onclick="addItem()" class="button-detail">
                                    + Tambah Obat
                                </button>
                            </div>

                            <div id="items" class="space-y-3">

                                @php
                                    $oldItems = old('items', []);
                                    $existing = [];

                                    if (count($oldItems) > 0) {
                                        $existing = $oldItems;
                                    } else {
                                        foreach ($resep->details as $d) {
                                            $existing[] = [
                                                'kode_obat' => $d->obat->kode_obat,
                                                'dosis' => $d->dosis,
                                                'jumlah' => $d->jumlah,
                                            ];
                                        }
                                    }
                                @endphp

                                @if (count($existing) > 0)
                                    @foreach ($existing as $i => $item)
                                        <div
                                            class="item bg-gray-100 dark:bg-gray-700 border rounded p-4 relative shadow-sm">

                                            <div class="space-y-3">

                                                <select name="items[{{ $i }}][kode_obat]"
                                                    class="border p-2 rounded w-full text-black" required>
                                                    <option value="">Pilih obat</option>
                                                    @foreach ($obats as $o)
                                                        <option value="{{ $o->kode_obat }}"
                                                            {{ $item['kode_obat'] == $o->kode_obat ? 'selected' : '' }}>
                                                            {{ $o->nama_obat }} (Stok: {{ $o->stok }})
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <input type="text" name="items[{{ $i }}][dosis]"
                                                    class="border p-2 rounded w-full text-black"
                                                    value="{{ $item['dosis'] }}" required>

                                                <input type="number" name="items[{{ $i }}][jumlah]"
                                                    class="border p-2 rounded w-full text-black"
                                                    value="{{ $item['jumlah'] }}" required>
                                            </div>
                                        </div>
                                    @endforeach

                                    @php $index = count($existing); @endphp
                                @else
                                    @php $index = 1; @endphp

                                    <div class="item bg-gray-100 dark:bg-gray-700 border rounded p-4 shadow-sm">
                                        <div class="space-y-3">
                                            <select name="items[0][kode_obat]"
                                                class="border p-2 rounded w-full text-black" required>
                                                <option value="">Pilih obat</option>
                                                @foreach ($obats as $o)
                                                    <option value="{{ $o->kode_obat }}">
                                                        {{ $o->nama_obat }} (Stok: {{ $o->stok }})
                                                    </option>
                                                @endforeach
                                            </select>

                                            <input type="text" name="items[0][dosis]"
                                                class="border p-2 rounded w-full text-black" required>

                                            <input type="number" name="items[0][jumlah]"
                                                class="border p-2 rounded w-full text-black" required>
                                        </div>
                                    </div>

                                @endif

                            </div>
                        </div>

                        <div class="pt-4 flex justify-end gap-3">
                            <button type="submit" class="button-success">
                                Update Resep
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        let index = {{ $index }};

        function addItem() {
            const items = document.getElementById('items');

            let html = `
                <div class="item bg-gray-100 dark:bg-gray-700 border rounded p-4 relative shadow-sm">
                    <button type="button" onclick="removeItem(this)"
                        class="absolute top-3 right-3 bg-red-500 text-white px-2 py-1 text-xs rounded shadow">
                        Hapus
                    </button>

                    <div class="space-y-3">

                        <select name="items[${index}][kode_obat]" class="border p-2 rounded w-full text-black" required>
                            <option value="">Pilih obat</option>
                            @foreach ($obats as $o)
                                <option value="{{ $o->kode_obat }}">{{ $o->nama_obat }} (Stok: {{ $o->stok }})</option>
                            @endforeach
                        </select>

                        <input type="text" name="items[${index}][dosis]"
                            class="border p-2 rounded w-full text-black" required>

                        <input type="number" name="items[${index}][jumlah]"
                            class="border p-2 rounded w-full text-black" required>

                    </div>
                </div>
            `;

            items.insertAdjacentHTML('beforeend', html);
            index++;
        }

        function removeItem(button) {
            button.parentElement.remove();
        }
    </script>

</x-app-layout>
