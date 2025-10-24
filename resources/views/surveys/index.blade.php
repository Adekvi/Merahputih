{{-- resources/views/surveys/index.blade.php --}}
<x-app-layout>
        <x-slot:title>
        Daftar Desa
    </x-slot:title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            📊 Daftar Data Sensus Desa
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg p-4 sm:p-6">

                {{-- WRAPPER RESPONSIVE TABLE --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 dark:border-gray-700 text-sm sm:text-base rounded-lg">
                        <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-3 sm:px-4 py-2 border dark:border-gray-700 text-left">No</th>
                                <th class="px-3 sm:px-4 py-2 border dark:border-gray-700 text-left">Kecamatan</th>
                                <th class="px-3 sm:px-4 py-2 border dark:border-gray-700 text-left">Desa</th>
                                <th class="px-3 sm:px-4 py-2 border dark:border-gray-700 text-left whitespace-nowrap">Jumlah Penduduk</th>
                                <th class="px-3 sm:px-4 py-2 border dark:border-gray-700 text-left">Potensi Lahan</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($surveys as $index => $survey)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-3 sm:px-4 py-2 border dark:border-gray-700 text-gray-800 dark:text-gray-200">{{ $index + 1 }}</td>
                                    <td class="px-3 sm:px-4 py-2 border dark:border-gray-700">{{ $survey->kecamatan }}</td>
                                    <td class="px-3 sm:px-4 py-2 border dark:border-gray-700">{{ $survey->desa }}</td>
                                    <td class="px-3 sm:px-4 py-2 border dark:border-gray-700">{{ number_format($survey->jumlah_penduduk ?? 0) }}</td>

                                    <td class="px-3 sm:px-4 py-2 border dark:border-gray-700 align-top w-full">
                                        @foreach ($survey->parcels as $parcel)
                                            <div class="mb-4">
                                                @php
                                                    $colors = [
                                                        'persawahan' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                        'perkebunan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                        'tambak' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                        'peternakan' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                        'komoditas_lain' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                                    ];
                                                @endphp

                                                <div class="flex flex-wrap items-center space-x-2 mb-1">
                                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $colors[$parcel->type] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-900 dark:text-gray-300' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $parcel->type)) }}
                                                    </span>
                                                </div>

                                                {{-- daftar tanaman / hasil pada parcel --}}
                                                @if($parcel->crops->count() > 0)
                                                    <ul class="list-disc list-inside text-xs sm:text-sm text-gray-700 dark:text-gray-300 mb-1">
                                                        @foreach ($parcel->crops as $crop)
                                                            <li>
                                                                <strong>{{ $crop->nama_tanaman }}</strong>
                                                                @if(!is_null($crop->luas_hektare))
                                                                    — luas: {{ number_format($crop->luas_hektare, 3) }} ha
                                                                @endif
                                                                @if(!is_null($crop->produksi_ton))
                                                                    — produksi: {{ number_format($crop->produksi_ton, 3) }} {{ $crop->satuan ?? 'ton' }}
                                                                @endif
                                                                @if($crop->catatan)
                                                                    <div class="text-xs text-gray-500 dark:text-gray-400">({{ $crop->catatan }})</div>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                                {{-- daftar ternak --}}
                                                @if($parcel->livestock->count() > 0)
                                                    <ul class="list-disc list-inside text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                                        @foreach ($parcel->livestock as $l)
                                                            <li>
                                                                <strong>{{ $l->jenis_ternak }}</strong>
                                                                @if(!is_null($l->jumlah))
                                                                    — {{ number_format($l->jumlah) }} ekor
                                                                @endif
                                                                @if($l->produksi)
                                                                    — {{ $l->produksi }}
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endforeach
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
</x-app-layout>
