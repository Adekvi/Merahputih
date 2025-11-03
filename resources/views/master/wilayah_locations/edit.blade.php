<x-layout.terminal title="{{ isset($wilayah_location) ? 'Edit Lokasi' : 'Tambah Lokasi' }}">
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800">{{ isset($wilayah_location) ? 'Edit Lokasi' : 'Tambah Lokasi' }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 shadow-lg p-6 rounded-2xl">

        @if ($errors->any())
            <div class="p-3 mb-4 text-sm rounded-lg bg-red-100 text-red-700 border border-red-300">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ isset($wilayah_location) ? route('wilayah_locations.update', $wilayah_location->id) : route('wilayah_locations.store') }}"
            method="POST" class="space-y-4">
            @csrf
            @if (isset($wilayah_location))
                @method('PUT')
            @endif

            <div>
                <label for="kelurahan_id" class="block text-gray-700 dark:text-gray-200 mb-1">Kelurahan</label>
                <select name="kelurahan_id" id="kelurahan_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Pilih Kelurahan</option>
                    @foreach ($kelurahans as $kec)
                        <option value="{{ $kec->id }}"
                            {{ isset($wilayah_location) && $wilayah_location->kelurahan_id == $kec->id ? 'selected' : '' }}>
                            {{ $kec->nama_kelurahan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="id_kecamatan" class="block text-gray-700 dark:text-gray-200 mb-1">ID Kecamatan</label>
                <input type="text" name="id_kecamatan" id="id_kecamatan"
                    value="{{ old('id_kecamatan', $wilayah_location->id_kecamatan ?? '') }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            <div>
                <label for="nama_kelurahan" class="block text-gray-700 dark:text-gray-200 mb-1">Nama Kelurahan</label>
                <input type="text" name="nama_kelurahan" id="nama_kelurahan"
                    value="{{ old('nama_kelurahan', $wilayah_location->nama_kelurahan ?? '') }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            <div>
                <label for="latitude" class="block text-gray-700 dark:text-gray-200 mb-1">Latitude</label>
                <input type="text" name="latitude" id="latitude"
                    value="{{ old('latitude', $wilayah_location->latitude ?? '') }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            <div>
                <label for="longitude" class="block text-gray-700 dark:text-gray-200 mb-1">Longitude</label>
                <input type="text" name="longitude" id="longitude"
                    value="{{ old('longitude', $wilayah_location->longitude ?? '') }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            <div>
                <label for="provider" class="block text-gray-700 dark:text-gray-200 mb-1">Provider</label>
                <input type="text" name="provider" id="provider"
                    value="{{ old('provider', $wilayah_location->provider ?? '') }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            <div>
                <label for="raw_response" class="block text-gray-700 dark:text-gray-200 mb-1">Raw Response
                    (JSON)</label>
                <textarea name="raw_response" id="raw_response" rows="4" class="w-full border rounded-lg px-3 py-2">{{ old('raw_response', $wilayah_location->raw_response ?? '') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('wilayah_locations.index') }}"
                    class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Batal</a>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">{{ isset($wilayah_location) ? 'Update' : 'Simpan' }}</button>
            </div>

        </form>
    </div>
</x-layout.terminal>
