<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Sensus Desa</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size:12px; color:#111; }
        .header { text-align:center; margin-bottom:10px; }
        .meta { font-size:11px; margin-bottom:8px; }
        table { width:100%; border-collapse: collapse; margin-bottom:8px; }
        table thead th { background:#f2f2f2; padding:6px; border:1px solid #ddd; font-size:11px; }
        table tbody td { padding:6px; border:1px solid #ddd; vertical-align:top; font-size:11px; }
        .small { font-size:10px; color:#666; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Report Data Sensus Desa</h2>
        <div class="meta">Di-generate pada: {{ $generated_at->format('d M Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:40px">No</th>
                <th>Kecamatan</th>
                <th>Desa</th>
                <th>Jumlah Penduduk</th>
                <th>Potensi Lahan (ringkasan)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($surveys as $i => $s)
                <tr>
                    <td style="text-align:center">{{ $i+1 }}</td>
                    <td>{{ optional($s->kecamatan)->nama_kecamatan ?? ($s->kecamatan ?? '-') }}</td>
                    <td>{{ optional($s->kelurahan)->nama_kelurahan ?? ($s->desa ?? '-') }}</td>
                    <td style="text-align:right">{{ $s->jumlah_penduduk ?? '-' }}</td>
                    <td>
                        @if($s->parcels->isEmpty())
                            <span class="small">(tidak ada potensi)</span>
                        @else
                            @foreach($s->parcels as $p)
                                <div><strong>{{ ucfirst(str_replace('_',' ',$p->type ?? '')) }}</strong>
                                    @if($p->crops->count())
                                        : {{ $p->crops->pluck('nama_tanaman')->join(', ') }}
                                    @endif
                                    @if($p->livestock->count())
                                        @if($p->crops->count()), @endif
                                        {{ $p->livestock->pluck('jenis_ternak')->join(', ') }}
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="small">Total record: {{ $surveys->count() }}</div>
</body>
</html>
