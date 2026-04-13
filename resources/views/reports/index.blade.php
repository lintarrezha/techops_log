@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
    <div class="space-y-5">

        {{-- Filter Periode --}}
        <form method="GET" class="bg-white border border-gray-200 rounded-xl px-4 py-3 flex gap-3 flex-wrap items-center">
            <div class="flex gap-1">
                @foreach (['harian', 'mingguan', 'bulanan', 'triwulan', 'tahunan'] as $p)
                    <button type="submit" name="periode" value="{{ $p }}"
                        class="px-3 py-1.5 rounded-full text-xs font-medium transition
                           {{ $periode === $p ? 'bg-emerald-500 text-white' : 'border border-gray-200 text-gray-500 hover:border-gray-300' }}">
                        {{ ucfirst($p) }}
                    </button>
                @endforeach
            </div>

            @if (in_array($periode, ['bulanan', 'triwulan']))
                <select name="bulan"
                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-emerald-400">
                    @foreach (range(1, 12) as $b)
                        <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            @endif

            <select name="tahun"
                class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-emerald-400">
                @foreach (range(now()->year, now()->year - 3, -1) as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>

            <div class="ml-auto flex gap-2">
                <a href="{{ route('report.pdf', request()->all()) }}"
                    class="flex items-center gap-1.5 border border-gray-200 text-gray-500 hover:bg-gray-50 text-xs font-medium px-3 py-2 rounded-lg transition">
                    ⬇ Export PDF
                </a>
            </div>
        </form>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-4 gap-4">
            @php
                $summaryCards = [
                    ['label' => 'Total Kegiatan', 'value' => $summary['total'], 'color' => 'text-gray-800'],
                    ['label' => 'Normal', 'value' => $summary['normal'], 'color' => 'text-emerald-600'],
                    ['label' => 'Ada Kendala', 'value' => $summary['ada_kendala'], 'color' => 'text-amber-600'],
                    ['label' => 'Kritis', 'value' => $summary['kritis'], 'color' => 'text-red-600'],
                ];
            @endphp
            @foreach ($summaryCards as $sc)
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <p class="text-2xl font-mono font-medium {{ $sc['color'] }} mb-1">{{ $sc['value'] }}</p>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">{{ $sc['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Rekap per Jenis Kegiatan --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-800">Rekap per Jenis Kegiatan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Jenis Kegiatan</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Total</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Normal</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Ada Kendala</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Kritis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">%
                                Normal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($rekapPerType as $rekap)
                            @php $persen = $rekap['total'] > 0 ? round($rekap['normal'] / $rekap['total'] * 100) : 0; @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $rekap['nama'] }}</td>
                                <td class="px-4 py-3 text-center font-mono text-gray-600">{{ $rekap['total'] }}</td>
                                <td class="px-4 py-3 text-center font-mono text-emerald-600">{{ $rekap['normal'] }}</td>
                                <td class="px-4 py-3 text-center font-mono text-amber-600">{{ $rekap['ada_kendala'] }}</td>
                                <td class="px-4 py-3 text-center font-mono text-red-600">{{ $rekap['kritis'] }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-full bg-emerald-400 rounded-full"
                                                style="width:{{ $persen }}%"></div>
                                        </div>
                                        <span class="text-xs font-mono text-gray-500 w-8">{{ $persen }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-400">Tidak ada data pada
                                    periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Log Detail --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-800">Detail Log — {{ $logs->count() }} entri</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Kegiatan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Teknisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($logs as $log)
                            @php
                                $badgeColor = match ($log->status_kegiatan) {
                                    'normal' => 'bg-emerald-50 text-emerald-700',
                                    'ada_kendala' => 'bg-amber-50 text-amber-700',
                                    'kritis' => 'bg-red-50 text-red-700',
                                    default => 'bg-gray-50 text-gray-600',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-mono text-xs text-gray-500">
                                    {{ $log->tanggal_kegiatan->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-gray-800">{{ $log->activityType->nama_kegiatan }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $log->status_kegiatan)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ $log->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-400">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
