<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Kegiatan #{{ $activityLog->id }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.6;
        }

        .header {
            border-bottom: 2px solid #059669;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .header h1 {
            font-size: 18px;
            color: #059669;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 10px;
            color: #6b7280;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 6px 10px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
        }

        .info-table td:first-child {
            background: #f9fafb;
            font-weight: bold;
            color: #6b7280;
            width: 30%;
        }

        .status-normal {
            color: #065f46;
            background: #d1fae5;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-ada_kendala {
            color: #92400e;
            background: #fef3c7;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-kritis {
            color: #991b1b;
            background: #fee2e2;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 16px 0 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #d1fae5;
        }

        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .checklist-table td {
            padding: 6px 10px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
        }

        .checklist-table td:first-child {
            background: #f9fafb;
            color: #4b5563;
            width: 45%;
        }

        .checklist-table td:last-child {
            font-weight: 600;
            color: #111827;
        }

        .catatan-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 10px 12px;
            font-size: 10px;
            color: #374151;
            margin-bottom: 16px;
        }

        .issue-box {
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 10px 12px;
            margin-bottom: 8px;
        }

        .issue-title {
            font-weight: bold;
            font-size: 10px;
            color: #111827;
            margin-bottom: 4px;
        }

        .issue-meta {
            font-size: 9px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .issue-solusi {
            background: #ecfdf5;
            border-left: 3px solid #059669;
            padding: 6px 10px;
            font-size: 10px;
            color: #065f46;
            margin-top: 6px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header">
        <h1>TechOps Log — Balai Teknik Pantai</h1>
        <p>Laporan Kegiatan Operasional Teknis &bull; Dicetak: {{ now()->format('d F Y H:i') }}</p>
    </div>

    {{-- Info Kegiatan --}}
    <table class="info-table">
        <tr>
            <td>ID Log</td>
            <td>#{{ $activityLog->id }}</td>
        </tr>
        <tr>
            <td>Jenis Kegiatan</td>
            <td>{{ $activityLog->activityType->nama_kegiatan }}</td>
        </tr>
        <tr>
            <td>Tanggal & Waktu</td>
            <td>{{ $activityLog->tanggal_kegiatan->format('l, d F Y — H:i') }}</td>
        </tr>
        <tr>
            <td>Teknisi</td>
            <td>{{ $activityLog->user->name }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>
                <span class="status-{{ $activityLog->status_kegiatan }}">
                    {{ ucfirst(str_replace('_', ' ', $activityLog->status_kegiatan)) }}
                </span>
            </td>
        </tr>
    </table>

    {{-- Jawaban Checklist --}}
    @if ($activityLog->answers->count())
        @php $grouped = $activityLog->answers->groupBy(fn($a) => $a->template->section_label ?? '-'); @endphp

        <div class="section-title">Hasil Checklist</div>

        @foreach ($grouped as $label => $answers)
            <div style="margin-bottom:4px; font-size:10px; font-weight:bold; color:#374151;">
                {{ $label }}. {{ $answers->first()->template->section_name }}
            </div>
            <table class="checklist-table">
                @foreach ($answers as $answer)
                    <tr>
                        <td>{{ $answer->template->pertanyaan }}</td>
                        <td>
                            {{ $answer->jawaban ?? '—' }}
                            @if ($answer->template->satuan)
                                {{ $answer->template->satuan }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        @endforeach
    @endif

    {{-- Catatan --}}
    @if ($activityLog->catatan)
        <div class="section-title">Catatan Tambahan</div>
        <div class="catatan-box">{{ $activityLog->catatan }}</div>
    @endif

    {{-- Issues --}}
    @if ($activityLog->issues->count())
        <div class="section-title">Issue / Knowledge Base</div>
        @foreach ($activityLog->issues as $issue)
            <div class="issue-box">
                <div class="issue-title">{{ $issue->judul_masalah }}</div>
                <div class="issue-meta">
                    @if ($issue->kategori)
                        Kategori: {{ $issue->kategori }} &bull;
                    @endif
                    Status: {{ $issue->status === 'resolved' ? '✓ Resolved' : 'Open' }}
                </div>
                <div style="font-size:10px; color:#4b5563;">{{ $issue->deskripsi_masalah }}</div>
                @if ($issue->solusi)
                    <div class="issue-solusi"><strong>Solusi:</strong> {{ $issue->solusi }}</div>
                @endif
            </div>
        @endforeach
    @endif

    <div class="footer">
        Dokumen ini digenerate otomatis oleh sistem TechOps Log &bull; Balai Teknik Pantai
    </div>

</body>

</html>
