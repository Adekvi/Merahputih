<?php

namespace App\Http\Controllers;

use App\Exports\SurveysExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Survey;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
     public function exportExcel(Request $request)
    {
        $filters = $request->all();
        $filename = 'surveys_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new SurveysExport($filters), $filename);
    }

    /**
     * Export surveys to PDF (simple table)
     * preserves filters passed in query.
     */
    public function exportPdf(Request $request)
    {
        // Build query same as index
        $q = Survey::with(['kecamatan', 'kelurahan', 'parcels.crops', 'parcels.livestock']);

        // contoh filter: kecamatan_id, kelurahan_id
        if ($request->filled('kecamatan_id')) {
            $q->where('kecamatan_id', $request->kecamatan_id);
        }
        if ($request->filled('kelurahan_id')) {
            $q->where('kelurahan_id', $request->kelurahan_id);
        }

        // tambahan filter lain (search, date range) => sesuaikan jika index pake filter lain
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function($qq) use ($s) {
                $qq->where('desa', 'like', "%{$s}%")
                   ->orWhere('kecamatan', 'like', "%{$s}%");
            });
        }

        $surveys = $q->orderBy('id','asc')->get();

        $pdf = Pdf::loadView('surveys.report_pdf', [
            'surveys' => $surveys,
            'generated_at' => now(),
        ])->setPaper('a4', 'portrait');

        $filename = 'surveys_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }
}

