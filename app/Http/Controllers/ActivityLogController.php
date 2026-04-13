<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ActivityType;
use App\Models\ChecklistAnswer;
use App\Models\ChecklistTemplate;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    // Step 1: Pilih jenis kegiatan
    public function create()
    {
        $activityTypes = ActivityType::where('is_active', true)->get();
        return view('activity-logs.create', compact('activityTypes'));
    }

    // Step 2: Load form checklist berdasarkan jenis kegiatan (AJAX)
    public function getChecklist(ActivityType $activityType)
    {
        $templates = ChecklistTemplate::where('activity_type_id', $activityType->id)
            ->orderBy('urutan')
            ->get()
            ->groupBy('section_label');

        return view('activity-logs.partials.checklist-form', compact('templates', 'activityType'));
    }

    // Submit laporan
    public function store(Request $request)
    {
        $request->validate([
            'activity_type_id' => 'required|exists:activity_types,id',
            'status_kegiatan'  => 'required|in:normal,ada_kendala,kritis',
        ]);

        $log = ActivityLog::create([
            'user_id'           => auth()->id(),
            'activity_type_id'  => $request->activity_type_id,
            'tanggal_kegiatan'  => now(),
            'status_kegiatan'   => $request->status_kegiatan,
            'catatan'           => $request->catatan,
            'custom_sections'   => $request->custom_sections ?? null,
        ]);

        // Simpan jawaban checklist
        if ($request->has('answers')) {
            foreach ($request->answers as $templateId => $jawaban) {
                ChecklistAnswer::create([
                    'activity_log_id' => $log->id,
                    'template_id'     => $templateId,
                    'jawaban'         => is_array($jawaban) ? implode(', ', $jawaban) : $jawaban,
                ]);
            }
        }

        return redirect()->route('activity.index')
            ->with('success', "Laporan #{$log->id} berhasil disimpan.");
    }

    // Riwayat kegiatan
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'activityType'])
            ->latest('tanggal_kegiatan');

        if ($request->filled('type')) {
            $query->where('activity_type_id', $request->type);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_kegiatan', $request->bulan);
        }

        if ($request->filled('status')) {
            $query->where('status_kegiatan', $request->status);
        }

        $logs          = $query->paginate(15);
        $activityTypes = ActivityType::where('is_active', true)->get();

        return view('activity-logs.index', compact('logs', 'activityTypes'));
    }

    // Detail log
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load(['user', 'activityType', 'answers.template', 'issues']);
        return view('activity-logs.show', compact('activityLog'));
    }
}
