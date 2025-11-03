<?php

namespace App\Exports;

use App\Models\Survey;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SurveysExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query;
    }

    public function collection()
    {
        $q = Survey::query()
            ->with(['kecamatan', 'kelurahan', 'parcels.crops', 'parcels.livestock']);

        if ($this->query instanceof \Closure) {
            ($this->query)($q);
        }

        return $q->get();
    }

    public function map($survey): array
    {
        $peternakan = [];
        $pertanian = [];
        $perkebunan = [];
        $tambak = [];
        $komoditas_lain = [];
        $luasTotal = 0;

        foreach ($survey->parcels as $parcel) {
            $type = $parcel->type ?? '';

            if (!empty($parcel->crops) && $parcel->crops->count()) {
                foreach ($parcel->crops as $c) {
                    $nama = $c->nama_tanaman ?? '';
                    $luas = $c->luas_hektare;
                    $prod = $c->produksi_ton;
                    if ($luas !== null && is_numeric($luas)) $luasTotal += (float) $luas;

                    $fragment = $nama;
                    if ($luas !== null) $fragment .= ' (luas: ' . $luas . ' ha)';
                    if ($prod !== null) $fragment .= ' (produksi: ' . $prod . ')';

                    if ($type === 'persawahan') $pertanian[] = $fragment;
                    else if ($type === 'perkebunan') $perkebunan[] = $fragment;
                    else if ($type === 'tambak') $tambak[] = $fragment;
                    else $komoditas_lain[] = $fragment;
                }
            }

            if (!empty($parcel->livestock) && $parcel->livestock->count()) {
                foreach ($parcel->livestock as $l) {
                    $jenis = $l->jenis_ternak ?? '';
                    $jumlah = $l->jumlah;
                    $fragment = $jenis;
                    if ($jumlah !== null) $fragment .= ' (' . $jumlah . ' ekor)';
                    $peternakan[] = $fragment;
                }
            }
        }

        return [
            $survey->id,
            $survey->kecamatan->nama_kecamatan ?? ($survey->kecamatan ?? ''),
            $survey->kelurahan->nama_kelurahan ?? ($survey->desa ?? ''),
            $survey->jumlah_penduduk ?? '',
            implode(' ; ', $peternakan),
            implode(' ; ', $pertanian),
            implode(' ; ', $perkebunan),
            implode(' ; ', $tambak),
            implode(' ; ', $komoditas_lain),
            $luasTotal ?: '',
            optional($survey->created_at)->format('Y-m-d H:i:s') ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kecamatan',
            'Desa/Kelurahan',
            'Jumlah Penduduk',
            'Peternakan',
            'Pertanian',
            'Perkebunan',
            'Tambak',
            'Komoditas Lain',
            'Luas Lahan',
            'Dibuat Tanggal',
        ];
    }
}
