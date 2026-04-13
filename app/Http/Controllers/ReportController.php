<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ActivityType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'bulanan');
        $bulan   = $request->get('bulan', now()->month);
        $tahun   = $request->get('tahun', now()->year);

        $query = ActivityLog::with(['user', 'activityType']);

        $query = match ($periode) {
            'harian'    => $query->whereDate('tanggal_kegiatan', today()),
            'mingguan'  => $query->whereBetween('tanggal_kegiatan', [now()->startOfWeek(), now()->endOfWeek()]),
            'bulanan'   => $query->whereMonth('tanggal_kegiatan', $bulan)->whereYear('tanggal_kegiatan', $tahun),
            'triwulan'  => $query->whereBetween('tanggal_kegiatan', [now()->firstOfQuarter(), now()->lastOfQuarter()]),
            'tahunan'   => $query->whereYear('tanggal_kegiatan', $tahun),
            default     => $query->whereMonth('tanggal_kegiatan', $bulan),
        };

        $logs          = $query->latest('tanggal_kegiatan')->get();
        $activityTypes = ActivityType::all();

        // Statistik ringkasan
        $summary = [
            'total'        => $logs->count(),
            'normal'       => $logs->where('status_kegiatan', 'normal')->count(),
            'ada_kendala'  => $logs->where('status_kegiatan', 'ada_kendala')->count(),
            'kritis'       => $logs->where('status_kegiatan', 'kritis')->count(),
        ];

        // Rekap per jenis kegiatan
        $rekapPerType = $logs->groupBy('activity_type_id')->map(function ($group) {
            return [
                'nama'        => $group->first()->activityType->nama_kegiatan,
                'total'       => $group->count(),
                'normal'      => $group->where('status_kegiatan', 'normal')->count(),
                'ada_kendala' => $group->where('status_kegiatan', 'ada_kendala')->count(),
                'kritis'      => $group->where('status_kegiatan', 'kritis')->count(),
            ];
        });

        return view('reports.index', compact('logs', 'summary', 'rekapPerType', 'activityTypes', 'periode', 'bulan', 'tahun'));
    }

    public function exportPdf(Request $request)
    {
        // Gunakan logic yang sama dengan index
        $periode = $request->get('periode', 'bulanan');
        $bulan   = $request->get('bulan', now()->month);
        $tahun   = $request->get('tahun', now()->year);

        $query = ActivityLog::with(['user', 'activityType']);
        $query = match ($periode) {
            'harian'   => $query->whereDate('tanggal_kegiatan', today()),
            'mingguan' => $query->whereBetween('tanggal_kegiatan', [now()->startOfWeek(), now()->endOfWeek()]),
            'bulanan'  => $query->whereMonth('tanggal_kegiatan', $bulan)->whereYear('tanggal_kegiatan', $tahun),
            'triwulan' => $query->whereBetween('tanggal_kegiatan', [now()->firstOfQuarter(), now()->lastOfQuarter()]),
            'tahunan'  => $query->whereYear('tanggal_kegiatan', $tahun),
            default    => $query->whereMonth('tanggal_kegiatan', $bulan),
        };

        $logs    = $query->latest('tanggal_kegiatan')->get();
        $summary = [
            'total'       => $logs->count(),
            'normal'      => $logs->where('status_kegiatan', 'normal')->count(),
            'ada_kendala' => $logs->where('status_kegiatan', 'ada_kendala')->count(),
            'kritis'      => $logs->where('status_kegiatan', 'kritis')->count(),
        ];

        $pdf = Pdf::loadView('reports.pdf', compact('logs', 'summary', 'periode', 'bulan', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("laporan-techops-{$periode}-{$bulan}-{$tahun}.pdf");
    }
}
