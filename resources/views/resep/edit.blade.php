<x-app-layout :backUrl="route('resep.index')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Resep') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-y-scroll shadow-sm sm:rounded-lg max-h-[75vh]">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('resep.update', $resep->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="font-semibold">Pasien</label>

                            <input type="hidden" name="pasien_id" value="{{ $resep->pasien->id }}">
                            <p class="p-2 bg-gray-100 rounded text-black">
                                {{ $resep->pasien->kode_pasien }} - {{ $resep->pasien->nama_pasien }}
                            </p>
                        </div>

                        <h3 class="font-semibold mb-2">Detail Obat</h3>

                        <div id="items">

                            @php
                                $oldItems = old('items', $resep->details->toArray());
                                $index = 0;
                            @endphp

                            @foreach ($oldItems as $i => $item)
                                <div class="item border p-3 rounded mb-2 relative">
                                    <button type="button" onclick="removeItem(this)"
                                        class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 text-xs rounded">
                                        Hapus
                                    </button>

                                    <select name="items[{{ $index }}][obat_id]"
                                        class="border p-2 rounded w-full mb-2 text-black" required>
                                        <option value="">Pilih obat</option>
                                        @foreach ($obats as $o)
                                            <option value="{{ $o->id }}"
                                                {{ isset($item['obat_id']) && $item['obat_id'] == $o['id'] ? 'selected' : '' }}>
                                                {{ $o->nama_obat }} (Stok: {{ $o->stok }})
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="text" name="items[{{ $index }}][dosis]" placeholder="Dosis"
                                        value="{{ $item['dosis'] ?? '' }}"
                                        class="border p-2 rounded w-full mb-2 text-black" required>

                                    <input type="number" name="items[{{ $index }}][jumlah]"
                                        placeholder="Jumlah" value="{{ $item['jumlah'] ?? '' }}"
                                        class="border p-2 rounded w-full mb-2 text-black" required>
                                </div>
                                @php $index++; @endphp
                            @endforeach

                        </div>

                        <button type="button" onclick="addItem()"
                            class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
                            + Tambah Obat
                        </button>

                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                            Update Resep
                        </button>

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
                <div class="item border p-3 rounded mb-2 relative">
                    <button type="button" onclick="removeItem(this)"
                        class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 text-xs rounded">
                        Hapus
                    </button>

                    <select name="items[${index}][obat_id]" class="border p-2 rounded w-full mb-2 text-black" required>
                        <option value="">Pilih obat</option>
                        @foreach ($obats as $o)
                            <option value="{{ $o->id }}">{{ $o->nama_obat }} (Stok: {{ $o->stok }})</option>
                        @endforeach
                    </select>

                    <input type="text" name="items[${index}][dosis]" placeholder="Dosis (misal: 3x1)"
                        class="border p-2 rounded w-full mb-2 text-black" required>

                    <input type="number" name="items[${index}][jumlah]" placeholder="Jumlah"
                        class="border p-2 rounded w-full mb-2 text-black" required>
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
