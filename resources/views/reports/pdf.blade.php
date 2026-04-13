<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan TechOps Log</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            font-size: 16px;
            margin-bottom: 4px;
        }

        p.sub {
            font-size: 11px;
            color: #666;
            margin-bottom: 20px;
        }

        .summary {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .summary-box {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 16px;
            flex: 1;
            text-align: center;
        }

        .summary-box .val {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .summary-box .lbl {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        thead th {
            background: #f5f5f5;
            padding: 7px 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
        }

        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .badge {
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }

        .normal {
            background: #d1fae5;
            color: #065f46;
        }

        .ada_kendala {
            background: #fef3c7;
            color: #92400e;
        }

        .kritis {
            background: #fee2e2;
            color: #991b1b;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #aaa;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Laporan TechOps Log — Balai Teknik Pantai</h1>
    <p class="sub">Periode: {{ ucfirst($periode) }} &bull; Dicetak: {{ now()->translatedFormat('d F Y H:i') }}</p>

    <div class="summary">
        <div class="summary-box">
            <div class="val">{{ $summary['total'] }}</div>
            <div class="lbl">Total</div>
        </div>
        <div class="summary-box">
            <div class="val" style="color:#059669">{{ $summary['normal'] }}</div>
            <div class="lbl">Normal</div>
        </div>
        <div class="summary-box">
            <div class="val" style="color:#d97706">{{ $summary['ada_kendala'] }}</div>
            <div class="lbl">Ada Kendala</div>
        </div>
        <div class="summary-box">
            <div class="val" style="color:#dc2626">{{ $summary['kritis'] }}</div>
            <div class="lbl">Kritis</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal & Waktu</th>
                <th>Jenis Kegiatan</th>
                <th>Status</th>
                <th>Teknisi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->tanggal_kegiatan->format('d M Y H:i') }}</td>
                    <td>{{ $log->activityType->nama_kegiatan }}</td>
                    <td><span
                            class="badge {{ $log->status_kegiatan }}">{{ ucfirst(str_replace('_', ' ', $log->status_kegiatan)) }}</span>
                    </td>
                    <td>{{ $log->user->name }}</td>
                    <td>{{ Str::limit($log->catatan, 60) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:#999;padding:20px">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Dokumen ini digenerate otomatis oleh sistem TechOps Log &bull; Balai Teknik Pantai</div>
</body>

</html>
