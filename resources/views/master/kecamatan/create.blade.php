<x-layout.terminal title="Stats">
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Tambah Kecamatan</h2>
    </x-slot>

    <div class="max-w-lg mx-auto bg-white shadow p-6 rounded">
        <form action="{{ route('kecamatan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="block mb-1">Kabupaten</label>
                <select name="id_kabupaten" class="border rounded w-full p-2" required>
                    <option value="">-- Pilih Kabupaten --</option>
                    @foreach ($kabupatens as $kab)
                        <option value="{{ $kab->id }}">{{ $kab->nama_kabupaten }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="block mb-1">Nama Kecamatan</label>
                <input type="text" name="nama_kecamatan" class="border rounded w-full p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1">Status</label>
                <input type="text" name="status" class="border rounded w-full p-2">
            </div>

            <div class="text-right">
                <a href="{{ route('kecamatan.index') }}" class="px-3 py-2 text-gray-600">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</x-layout.terminal>
