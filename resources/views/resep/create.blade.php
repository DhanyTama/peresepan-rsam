<x-app-layout :backUrl="route('resep.index')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Resep') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('resep.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="font-semibold block mb-1">Pasien</label>

                            @isset($pasien)
                                <input type="hidden" name="kode_pasien" value="{{ $pasien->kode_pasien }}">
                                <p class="p-2 bg-gray-200 dark:bg-gray-700 rounded text-black dark:text-white font-medium">
                                    {{ $pasien->kode_pasien }} - {{ $pasien->nama_pasien }}
                                </p>
                            @else
                                <select name="kode_pasien" class="w-full border rounded p-2 text-black" required>
                                    <option value="">Pilih pasien</option>
                                    @foreach ($pasiens as $p)
                                        <option value="{{ $p->kode_pasien }}"
                                            {{ old('kode_pasien') == $p->kode_pasien ? 'selected' : '' }}>
                                            {{ $p->kode_pasien }} - {{ $p->nama_pasien }}
                                        </option>
                                    @endforeach
                                </select>
                            @endisset
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-lg">Detail Obat</h3>

                                <button type="button" onclick="addItem()" class="button-detail">
                                    + Tambah Obat
                                </button>
                            </div>

                            <div id="items" class="space-y-3">

                                @php $oldItems = old('items', []); @endphp

                                @if (count($oldItems) > 0)
                                    @foreach ($oldItems as $i => $item)
                                        <div
                                            class="item bg-gray-100 dark:bg-gray-700 border rounded p-4 relative shadow-sm">
                                            <button type="button" onclick="removeItem(this)"
                                                class="absolute top-3 right-3 bg-red-500 text-white px-2 py-1 text-xs rounded shadow">
                                                Hapus
                                            </button>

                                            <div class="space-y-3">
                                                <select name="items[{{ $i }}][kode_obat]"
                                                    class="border p-2 rounded w-full text-black" required>
                                                    <option value="">Pilih obat</option>
                                                    @foreach ($obats as $o)
                                                        <option value="{{ $o->kode_obat }}"
                                                            {{ isset($item['kode_obat']) && $item['kode_obat'] == $o->kode_obat ? 'selected' : '' }}>
                                                            {{ $o->nama_obat }} (Stok: {{ $o->stok }})
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <input type="text" name="items[{{ $i }}][dosis]"
                                                    placeholder="Dosis (misal: 3x1)" value="{{ $item['dosis'] ?? '' }}"
                                                    class="border p-2 rounded w-full text-black" required>

                                                <input type="number" name="items[{{ $i }}][jumlah]"
                                                    placeholder="Jumlah" value="{{ $item['jumlah'] ?? '' }}"
                                                    class="border p-2 rounded w-full text-black" required>
                                            </div>
                                        </div>
                                    @endforeach

                                    @php $index = count($oldItems); @endphp
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
                                                placeholder="Dosis (misal: 3x1)"
                                                class="border p-2 rounded w-full text-black" required>

                                            <input type="number" name="items[0][jumlah]" placeholder="Jumlah"
                                                class="border p-2 rounded w-full text-black" required>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>

                        <div class="pt-4 flex justify-end gap-3">
                            <button type="submit" class="button-success">
                                Simpan Resep
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

                        <input type="text" name="items[${index}][dosis]" placeholder="Dosis (misal: 3x1)"
                            class="border p-2 rounded w-full text-black" required>

                        <input type="number" name="items[${index}][jumlah]" placeholder="Jumlah"
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
