<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Issue;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todayLogs = ActivityLog::with('activityType')
            ->whereDate('tanggal_kegiatan', today())
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'kegiatan_hari_ini'   => ActivityLog::whereDate('tanggal_kegiatan', today())->count(),
            'kegiatan_bulan_ini'  => ActivityLog::whereMonth('tanggal_kegiatan', now()->month)->count(),
            'alert_aktif'         => ActivityLog::whereDate('tanggal_kegiatan', today())
                ->where('status_kegiatan', 'kritis')
                ->orWhere('status_kegiatan', 'ada_kendala')
                ->whereDate('tanggal_kegiatan', today())
                ->count(),
            'issue_resolved'      => Issue::whereMonth('created_at', now()->month)
                ->where('status', 'resolved')
                ->count(),
        ];

        return view('dashboard', compact('todayLogs', 'stats'));
    }
}
