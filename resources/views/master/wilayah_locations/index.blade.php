<x-layout.terminal title="Wilayah Locations">
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800">Wilayah Locations</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto bg-white dark:bg-gray-900 shadow-lg p-6 rounded-2xl">

        @if (session('success'))
            <div class="p-3 mb-4 text-sm rounded-lg bg-green-100 text-green-700 border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Daftar Lokasi</h3>
            <a href="{{ route('wilayah_locations.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Tambah Lokasi</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-3 border-b">#</th>
                        <th class="px-4 py-3 border-b">Kelurahan</th>
                        <th class="px-4 py-3 border-b">ID Kecamatan</th>
                        <th class="px-4 py-3 border-b">Nama Kelurahan</th>
                        <th class="px-4 py-3 border-b">Latitude</th>
                        <th class="px-4 py-3 border-b">Longitude</th>
                        <th class="px-4 py-3 border-b">Provider</th>
                        <th class="px-4 py-3 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $key => $loc)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-3 border-b">{{ $key + $locations->firstItem() }}</td>
                            <td class="px-4 py-3 border-b">{{ $loc->kelurahan->nama_kelurahan ?? '-' }}</td>
                            <td class="px-4 py-3 border-b">{{ $loc->id_kecamatan ?? '-' }}</td>
                            <td class="px-4 py-3 border-b">{{ $loc->nama_kelurahan ?? '-' }}</td>
                            <td class="px-4 py-3 border-b">{{ $loc->latitude ?? '-' }}</td>
                            <td class="px-4 py-3 border-b">{{ $loc->longitude ?? '-' }}</td>
                            <td class="px-4 py-3 border-b">{{ $loc->provider ?? '-' }}</td>
                            <td class="px-4 py-3 border-b flex gap-2 justify-center">
                                <a href="{{ route('wilayah_locations.edit', $loc->id) }}"
                                    class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form action="{{ route('wilayah_locations.destroy', $loc->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus lokasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-gray-500 dark:text-gray-400">Belum ada data
                                lokasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="custom-pagination">
            {{ $locations->links() }}
        </div>
    </div>
    <style>
        /* === RESET PAGINATION STYLING === */
        .custom-pagination nav {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 1.5rem;
        }

        .custom-pagination nav>div:first-child {
            display: none !important;
            /* Hilangkan info "Showing..." */
        }

        .custom-pagination nav svg {
            display: none !important;
            /* Hilangkan ikon panah SVG besar */
        }

        /* === UL utama === */
        .custom-pagination nav ul {
            display: flex;
            gap: 6px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .custom-pagination nav ul li {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* === Tombol angka === */
        .custom-pagination nav ul li a,
        .custom-pagination nav ul li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        /* Hover dan active */
        .custom-pagination nav ul li a:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            color: #fff;
        }

        .custom-pagination nav ul li.active span {
            background-color: #2563eb;
            border-color: #2563eb;
            color: #fff;
        }

        .custom-pagination nav ul li.disabled span {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* === Tombol previous & next === */
        .custom-pagination nav ul li:first-child a::before {
            content: "‹";
            font-size: 16px;
            font-weight: bold;
        }

        .custom-pagination nav ul li:last-child a::after {
            content: "›";
            font-size: 16px;
            font-weight: bold;
        }

        /* Dark mode */
        .dark .custom-pagination nav ul li a,
        .dark .custom-pagination nav ul li span {
            background-color: #1f2937;
            border-color: #374151;
            color: #e5e7eb;
        }

        .dark .custom-pagination nav ul li a:hover {
            background-color: #2563eb;
            color: white;
        }
    </style>
</x-layout.terminal>
