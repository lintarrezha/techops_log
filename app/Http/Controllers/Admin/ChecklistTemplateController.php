<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityType;
use App\Models\ChecklistTemplate;
use Illuminate\Http\Request;

class ChecklistTemplateController extends Controller
{
    public function index(ActivityType $activityType)
    {
        $templates = ChecklistTemplate::where('activity_type_id', $activityType->id)
            ->orderBy('urutan')
            ->get()
            ->groupBy('section_label');

        return view('admin.checklist-templates.index', compact('activityType', 'templates'));
    }

    public function create(ActivityType $activityType)
    {
        // Ambil section yang sudah ada
        $sections = ChecklistTemplate::where('activity_type_id', $activityType->id)
            ->distinct()
            ->pluck('section_label', 'section_name')
            ->toArray();

        return view('admin.checklist-templates.create', compact('activityType', 'sections'));
    }

    public function store(Request $request, ActivityType $activityType)
    {
        $request->validate([
            'section_label' => 'required|string|max:5',
            'section_name'  => 'required|string|max:100',
            'pertanyaan'    => 'required|string|max:255',
            'tipe_input'    => 'required|in:text,number,radio,textarea,select',
            'satuan'        => 'nullable|string|max:20',
            'is_required'   => 'boolean',
            'opsi_jawaban'  => 'nullable|string',
        ]);

        // Hitung urutan terakhir
        $lastUrutan = ChecklistTemplate::where('activity_type_id', $activityType->id)
            ->max('urutan') ?? 0;

        // Parse opsi jawaban
        $opsiJawaban = null;
        if (in_array($request->tipe_input, ['radio', 'select']) && $request->opsi_jawaban) {
            $opsiJawaban = array_filter(
                array_map('trim', explode("\n", $request->opsi_jawaban))
            );
            $opsiJawaban = array_values($opsiJawaban);
        }

        ChecklistTemplate::create([
            'activity_type_id' => $activityType->id,
            'section_label'    => strtoupper($request->section_label),
            'section_name'     => $request->section_name,
            'pertanyaan'       => $request->pertanyaan,
            'tipe_input'       => $request->tipe_input,
            'opsi_jawaban'     => $opsiJawaban,
            'satuan'           => $request->satuan,
            'is_required'      => $request->boolean('is_required'),
            'urutan'           => $lastUrutan + 1,
        ]);

        return redirect()->route('admin.checklist-templates.index', $activityType)
            ->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function edit(ActivityType $activityType, ChecklistTemplate $checklistTemplate)
    {
        $opsiText = $checklistTemplate->opsi_jawaban
            ? implode("\n", $checklistTemplate->opsi_jawaban)
            : '';

        return view('admin.checklist-templates.edit', compact('activityType', 'checklistTemplate', 'opsiText'));
    }

    public function update(Request $request, ActivityType $activityType, ChecklistTemplate $checklistTemplate)
    {
        $request->validate([
            'section_label' => 'required|string|max:5',
            'section_name'  => 'required|string|max:100',
            'pertanyaan'    => 'required|string|max:255',
            'tipe_input'    => 'required|in:text,number,radio,textarea,select',
            'satuan'        => 'nullable|string|max:20',
            'is_required'   => 'boolean',
            'opsi_jawaban'  => 'nullable|string',
        ]);

        $opsiJawaban = null;
        if (in_array($request->tipe_input, ['radio', 'select']) && $request->opsi_jawaban) {
            $opsiJawaban = array_filter(
                array_map('trim', explode("\n", $request->opsi_jawaban))
            );
            $opsiJawaban = array_values($opsiJawaban);
        }

        $checklistTemplate->update([
            'section_label' => strtoupper($request->section_label),
            'section_name'  => $request->section_name,
            'pertanyaan'    => $request->pertanyaan,
            'tipe_input'    => $request->tipe_input,
            'opsi_jawaban'  => $opsiJawaban,
            'satuan'        => $request->satuan,
            'is_required'   => $request->boolean('is_required'),
        ]);

        return redirect()->route('admin.checklist-templates.index', $activityType)
            ->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(ActivityType $activityType, ChecklistTemplate $checklistTemplate)
    {
        $checklistTemplate->delete();

        return back()->with('success', 'Pertanyaan berhasil dihapus.');
    }

    // Reorder urutan via drag or tombol
    public function moveUp(ActivityType $activityType, ChecklistTemplate $checklistTemplate)
    {
        $prev = ChecklistTemplate::where('activity_type_id', $activityType->id)
            ->where('urutan', '<', $checklistTemplate->urutan)
            ->orderByDesc('urutan')
            ->first();

        if ($prev) {
            [$checklistTemplate->urutan, $prev->urutan] = [$prev->urutan, $checklistTemplate->urutan];
            $checklistTemplate->save();
            $prev->save();
        }

        return back()->with('success', 'Urutan diperbarui.');
    }

    public function moveDown(ActivityType $activityType, ChecklistTemplate $checklistTemplate)
    {
        $next = ChecklistTemplate::where('activity_type_id', $activityType->id)
            ->where('urutan', '>', $checklistTemplate->urutan)
            ->orderBy('urutan')
            ->first();

        if ($next) {
            [$checklistTemplate->urutan, $next->urutan] = [$next->urutan, $checklistTemplate->urutan];
            $checklistTemplate->save();
            $next->save();
        }

        return back()->with('success', 'Urutan diperbarui.');
    }
}
