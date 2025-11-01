{{-- resources/views/surveys/index.blade.php --}}
<x-layout.terminal>
    <x-slot:title>
        Daftar Desa
    </x-slot:title>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-2  ms-4 text-maroon"> 📊 Daftar Data Sensus Desa</h3>
        </div>
        <div class="date-time mt-3 mt-md-0 text-md-end">
            <div id="tanggal" class="fw-semibold text-muted"></div>
            <div id="jam" class="fw-semibold text-muted"></div>
        </div>
    </div>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg p-4 sm:p-6">

                {{-- WRAPPER RESPONSIVE TABLE --}}
                <div class="overflow-x-auto">
                    <table
                        class="min-w-full border border-gray-300 dark:border-gray-700 text-sm sm:text-base rounded-lg">
                        <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-3 sm:px-4 py-2 border dark:border-gray-700 text-left">No</th>
                                <th class="px-3 sm:px-4 py-2 border dark:border-gray-700 text-left">Kecamatan</th>
                                <th class="px-3 sm:px-4 py-2 border dark:border-gray-700 text-left">Desa</th>
                                <th class="px-3 sm:px-4 py-2 border dark:border-gray-700 text-left whitespace-nowrap">
                                    Jumlah Penduduk</th>
                                <th class="px-3 sm:px-4 py-2 border dark:border-gray-700 text-left">Potensi Lahan</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($surveys as $index => $survey)
                                @php
                                    // Prefer relation names if available, then string fields, then id fallback.
                                    $kecamatanLabel =
                                        data_get($survey, 'kecamatan.nama_kecamatan') ??
                                        (($survey->kecamatan && is_string($survey->kecamatan)
                                            ? $survey->kecamatan
                                            : null) ??
                                            (data_get($survey, 'kecamatan_id')
                                                ? 'ID: ' . data_get($survey, 'kecamatan_id')
                                                : '-'));

                                    $desaLabel =
                                        data_get($survey, 'kelurahan.nama_kelurahan') ??
                                        (($survey->desa && is_string($survey->desa) ? $survey->desa : null) ??
                                            (data_get($survey, 'kelurahan_id')
                                                ? 'ID: ' . data_get($survey, 'kelurahan_id')
                                                : '-'));

                                    $jumlahPenduduk =
                                        $survey->jumlah_penduduk === null || $survey->jumlah_penduduk === ''
                                            ? '-'
                                            : number_format($survey->jumlah_penduduk);
                                @endphp

                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td
                                        class="px-3 sm:px-4 py-2 border dark:border-gray-700 text-gray-800 dark:text-gray-200">
                                        {{ $index + 1 }}</td>

                                    <td class="px-3 sm:px-4 py-2 border dark:border-gray-700">
                                        {{ $kecamatanLabel }}
                                    </td>

                                    <td class="px-3 sm:px-4 py-2 border dark:border-gray-700">
                                        {{ $desaLabel }}
                                    </td>

                                    <td class="px-3 sm:px-4 py-2 border dark:border-gray-700">
                                        {{ $jumlahPenduduk }}
                                    </td>

                                    <td class="px-3 sm:px-4 py-2 border dark:border-gray-700 align-top w-full">
                                        @if ($survey->parcels->isEmpty())
                                            <div class="text-xs text-gray-500 dark:text-gray-400">(tidak ada potensi
                                                lahan)</div>
                                        @else
                                            @foreach ($survey->parcels as $parcel)
                                                <div class="mb-4">
                                                    @php
                                                        $colors = [
                                                            'persawahan' =>
                                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                            'perkebunan' =>
                                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                            'tambak' =>
                                                                'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                            'peternakan' =>
                                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                            'komoditas_lain' =>
                                                                'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                                        ];
                                                        $ptype = $parcel->type ?? 'tidak diketahui';
                                                        $badgeClass =
                                                            $colors[$ptype] ??
                                                            'bg-gray-100 text-gray-700 dark:bg-gray-900 dark:text-gray-300';
                                                        $fmt = function ($val, $decimals = 3) {
                                                            if ($val === null || $val === '') {
                                                                return null;
                                                            }
                                                            if (floor($val) == $val) {
                                                                return number_format($val, 0);
                                                            }
                                                            return number_format($val, $decimals);
                                                        };
                                                    @endphp

                                                    <div class="flex flex-wrap items-center space-x-2 mb-1">
                                                        <span
                                                            class="px-2 py-1 rounded text-xs font-semibold {{ $badgeClass }}">
                                                            {{ ucfirst(str_replace('_', ' ', $ptype)) }}
                                                        </span>
                                                    </div>

                                                    {{-- daftar tanaman / hasil pada parcel --}}
                                                    @if ($parcel->crops && $parcel->crops->count() > 0)
                                                        <ul
                                                            class="list-disc list-inside text-xs sm:text-sm text-gray-700 dark:text-gray-300 mb-1">
                                                            @foreach ($parcel->crops as $crop)
                                                                @php
                                                                    $luas = $fmt($crop->luas_hektare);
                                                                    $prod = $fmt($crop->produksi_ton);
                                                                    $satuan = $crop->satuan ?? 'ton';
                                                                @endphp
                                                                <li>
                                                                    <strong>{{ $crop->nama_tanaman }}</strong>
                                                                    @if ($luas !== null)
                                                                        — luas: {{ $luas }} ha
                                                                    @endif
                                                                    @if ($prod !== null)
                                                                        — produksi: {{ $prod }}
                                                                        {{ $satuan }}
                                                                    @endif
                                                                    @if ($crop->catatan)
                                                                        <div
                                                                            class="text-xs text-gray-500 dark:text-gray-400">
                                                                            ({{ $crop->catatan }})
                                                                        </div>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif

                                                    {{-- daftar ternak --}}
                                                    @if ($parcel->livestock && $parcel->livestock->count() > 0)
                                                        <ul
                                                            class="list-disc list-inside text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                                            @foreach ($parcel->livestock as $l)
                                                                <li>
                                                                    <strong>{{ $l->jenis_ternak }}</strong>
                                                                    @if (!is_null($l->jumlah))
                                                                        — {{ number_format($l->jumlah) }} ekor
                                                                    @endif
                                                                    @if ($l->produksi)
                                                                        — {{ $l->produksi }}
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Belum ada data sensus.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</x-layout.terminal>
