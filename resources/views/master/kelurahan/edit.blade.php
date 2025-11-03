<x-layout.terminal title="Edit Kelurahan">
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800">Edit Kelurahan</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 shadow-lg p-6 rounded-2xl">

        {{-- Notifikasi error --}}
        @if ($errors->any())
            <div class="p-3 mb-4 text-sm rounded-lg bg-red-100 text-red-700 border border-red-300">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('kelurahan.update', $kelurahan->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Kecamatan --}}
            <div>
                <label for="kecamatan_id" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Kecamatan</label>
                <select name="kecamatan_id" id="kecamatan_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Pilih Kecamatan</option>
                    @foreach ($kecamatans as $kec)
                        <option value="{{ $kec->id }}" {{ $kelurahan->kecamatan_id == $kec->id ? 'selected' : '' }}>
                            {{ $kec->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Nama Kelurahan --}}
            <div>
                <label for="nama_kelurahan" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Nama Kelurahan</label>
                <input type="text" name="nama_kelurahan" id="nama_kelurahan" 
                       value="{{ old('nama_kelurahan', $kelurahan->nama_kelurahan) }}" 
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Status</label>
                <select name="status" id="status" class="w-full border rounded-lg px-3 py-2">
                    <option value="Aktif" {{ $kelurahan->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ $kelurahan->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('kelurahan.index') }}" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Update</button>
            </div>

        </form>
    </div>
</x-layout.terminal>
