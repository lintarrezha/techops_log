<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ActivityType;
use App\Models\ChecklistAnswer;
use App\Models\ChecklistTemplate;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
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

    public function exportPdf(ActivityLog $activityLog)
    {
        $activityLog->load(['user', 'activityType', 'answers.template', 'issues']);

        $pdf = Pdf::loadView('activity-logs.exports.pdf', compact('activityLog'))
            ->setPaper('a4', 'portrait');

        $filename = 'laporan-' . str_replace(' ', '-', strtolower($activityLog->activityType->nama_kegiatan))
            . '-' . $activityLog->tanggal_kegiatan->format('Y-m-d')
            . '.pdf';

        return $pdf->download($filename);
    }

    // Export detail kegiatan ke Word (DOCX)
    public function exportWord(ActivityLog $activityLog)
    {
        $activityLog->load(['user', 'activityType', 'answers.template', 'issues']);

        $statusLabel = match ($activityLog->status_kegiatan) {
            'normal'      => 'Normal',
            'ada_kendala' => 'Ada Kendala',
            'kritis'      => 'Kritis',
            default       => '-'
        };

        // Build rows HTML untuk info
        $infoRows = [
            ['ID Log',          '#' . $activityLog->id],
            ['Jenis Kegiatan',  $activityLog->activityType->nama_kegiatan],
            ['Tanggal & Waktu', $activityLog->tanggal_kegiatan->format('d F Y - H:i')],
            ['Teknisi',         $activityLog->user->name],
            ['Status',          $statusLabel],
        ];

        $infoHtml = '';
        foreach ($infoRows as [$label, $value]) {
            $infoHtml .= "<tr><td><b>{$label}</b></td><td>{$value}</td></tr>";
        }

        // Build checklist HTML
        $checklistHtml = '';
        if ($activityLog->answers->count()) {
            $grouped = $activityLog->answers->groupBy(fn($a) => $a->template->section_label ?? '-');
            foreach ($grouped as $label => $answers) {
                $sectionName   = $answers->first()->template->section_name ?? '';
                $checklistHtml .= "<h3>{$label}. {$sectionName}</h3><table>";
                foreach ($answers as $answer) {
                    $nilai         = ($answer->jawaban ?? '-') . ($answer->template->satuan ? ' ' . $answer->template->satuan : '');
                    $pertanyaan    = htmlspecialchars($answer->template->pertanyaan);
                    $checklistHtml .= "<tr><td>{$pertanyaan}</td><td><b>{$nilai}</b></td></tr>";
                }
                $checklistHtml .= '</table>';
            }
        }

        // Build issues HTML
        $issuesHtml = '';
        if ($activityLog->issues->count()) {
            foreach ($activityLog->issues as $i => $issue) {
                $no          = $i + 1;
                $judul       = htmlspecialchars($issue->judul_masalah);
                $kategori    = $issue->kategori ? '<p><i>Kategori: ' . htmlspecialchars($issue->kategori) . '</i></p>' : '';
                $status      = $issue->status === 'resolved' ? 'Resolved' : 'Open';
                $masalah     = htmlspecialchars($issue->deskripsi_masalah);
                $solusi      = $issue->solusi ? '<p><b>Solusi:</b> ' . htmlspecialchars($issue->solusi) . '</p>' : '';
                $issuesHtml .= "<p><b>{$no}. {$judul}</b></p>{$kategori}<p>Status: {$status}</p><p><b>Masalah:</b> {$masalah}</p>{$solusi}<br>";
            }
        }

        // Catatan
        $catatanHtml = $activityLog->catatan
            ? '<h2>Catatan Tambahan</h2><p>' . htmlspecialchars($activityLog->catatan) . '</p>'
            : '';

        // HTML lengkap dokumen
        $html = '
    <html xmlns:o="urn:schemas-microsoft-com:office:office"
          xmlns:w="urn:schemas-microsoft-com:office:word"
          xmlns="http://www.w3.org/TR/REC-html40">
    <head>
        <meta charset="UTF-8">
        <xml>
            <w:WordDocument>
                <w:View>Print</w:View>
                <w:Zoom>90</w:Zoom>
                <w:DoNotOptimizeForBrowser/>
            </w:WordDocument>
        </xml>
        <style>
            body        { font-family: Calibri, sans-serif; font-size: 11pt; color: #1f2937; }
            h1          { font-size: 18pt; color: #059669; margin-bottom: 4px; }
            h2          { font-size: 12pt; color: #059669; margin-top: 16px; margin-bottom: 6px; border-bottom: 1px solid #d1fae5; padding-bottom: 4px; }
            h3          { font-size: 10pt; color: #374151; margin-top: 12px; margin-bottom: 4px; font-weight: bold; }
            p           { font-size: 10pt; margin: 4px 0; }
            table       { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
            td          { padding: 5px 8px; border: 1px solid #e5e7eb; font-size: 10pt; vertical-align: top; }
            td:first-child { background-color: #f9fafb; width: 35%; }
            .footer     { font-size: 8pt; color: #9ca3af; margin-top: 24px; font-style: italic; }
            .subtitle   { font-size: 10pt; color: #6b7280; margin-bottom: 16px; }
        </style>
    </head>
    <body>
        <h1>TechOps Log &mdash; Balai Teknik Pantai</h1>
        <p class="subtitle">Laporan Kegiatan Operasional Teknis &bull; Dicetak: ' . now()->format('d F Y H:i') . '</p>

        <h2>Informasi Kegiatan</h2>
        <table>' . $infoHtml . '</table>

        ' . ($checklistHtml ? '<h2>Hasil Checklist</h2>' . $checklistHtml : '') . '

        ' . $catatanHtml . '

        ' . ($issuesHtml ? '<h2>Issue / Knowledge Base</h2>' . $issuesHtml : '') . '

        <p class="footer">Dokumen digenerate otomatis oleh sistem TechOps Log &bull; Balai Teknik Pantai</p>
    </body>
    </html>';

        $filename = 'laporan-'
            . str_replace(' ', '-', strtolower($activityLog->activityType->nama_kegiatan))
            . '-' . $activityLog->tanggal_kegiatan->format('Y-m-d')
            . '.doc';

        if (ob_get_level()) ob_end_clean();

        return response($html, 200, [
            'Content-Type'        => 'application/msword',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
