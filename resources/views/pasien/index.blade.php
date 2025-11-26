<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pasien') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg max-h-[75vh]">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="table-auto w-full">
                        <thead>
                            <tr class="border-b text-left">
                                <th class="p-2">#</th>
                                <th class="p-2">Kode</th>
                                <th class="p-2">Nama</th>
                                <th class="p-2">Alamat</th>
                                <th class="p-2">No Telp</th>
                                <th class="p-2">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pasiens as $key => $pasien)
                                <tr class="border-b">
                                    <td class="p-2">{{ $key + 1 }}</td>
                                    <td class="p-2">{{ $pasien->kode_pasien }}</td>
                                    <td class="p-2">{{ $pasien->nama_pasien }}</td>
                                    <td class="p-2">{{ $pasien->alamat }}</td>
                                    <td class="p-2">{{ $pasien->no_telepon }}</td>
                                    <td class="p-3">
                                        <a href="{{ route('resep.create', ['kode_pasien' => $pasien->kode_pasien]) }}"
                                            class="button-detail">
                                            Buat Resep
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-5">
                        {{ $pasiens->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
