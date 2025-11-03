<x-layout.terminal title="Data Kelurahan">
  <x-slot name="header">
    <h2 class="font-semibold text-2xl text-gray-800">Data Kelurahan</h2>
  </x-slot>

  <div class="max-w-6xl mx-auto bg-white dark:bg-gray-900 shadow-lg p-6 rounded-2xl">

    {{-- Notifikasi sukses --}}
    @if (session('success'))
      <div class="p-3 mb-4 text-sm rounded-lg bg-green-100 text-green-700 border border-green-300">
        {{ session('success') }}
      </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-5 gap-3">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Daftar Kelurahan</h3>
      <a href="{{ route('kelurahan.create') }}"
         style="display:inline-flex;align-items:center;gap:8px;
                background-color:#2563eb;color:#fff;
                padding:8px 16px;border-radius:8px;
                text-decoration:none;font-weight:500;
                transition:background-color 0.2s ease,transform 0.1s ease;"
         onmouseover="this.style.backgroundColor='#1d4ed8';this.style.transform='scale(1.03)'"
         onmouseout="this.style.backgroundColor='#2563eb';this.style.transform='scale(1)'"
         onmousedown="this.style.backgroundColor='#1e40af'"
         onmouseup="this.style.backgroundColor='#1d4ed8'">
          <i class="fas fa-plus" style="font-size:12px;"></i>
          <span>Tambah Kelurahan</span>
      </a>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
          <tr>
            <th class="px-4 py-3 border-b text-left">#</th>
            <th class="px-4 py-3 border-b text-left">Kecamatan</th>
            <th class="px-4 py-3 border-b text-left">Nama Kelurahan</th>
            <th class="px-4 py-3 border-b text-left">Status</th>
            <th class="px-4 py-3 border-b text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($kelurahans as $key => $kel)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
              <td class="px-4 py-3 border-b text-gray-700 dark:text-gray-300">
                {{ $key + $kelurahans->firstItem() }}
              </td>
              <td class="px-4 py-3 border-b text-gray-700 dark:text-gray-300">
                {{ $kel->kecamatan->nama_kecamatan ?? '-' }}
              </td>
              <td class="px-4 py-3 border-b text-gray-700 dark:text-gray-300">
                {{ $kel->nama_kelurahan }}
              </td>
              <td class="px-4 py-3 border-b text-gray-700 dark:text-gray-300">
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                  {{ $kel->status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                  {{ $kel->status ?? '-' }}
                </span>
              </td>
              <td class="px-4 py-3 border-b text-center">
                <div class="flex justify-center gap-3">
                  <a href="{{ route('kelurahan.edit', $kel->id) }}" class="text-blue-600 hover:text-blue-800 font-medium transition">Edit</a>
                  <form action="{{ route('kelurahan.destroy', $kel->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium transition">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-6 text-gray-500 dark:text-gray-400">
                Belum ada data kelurahan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="custom-pagination">
      {{ $kelurahans->links() }}
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
