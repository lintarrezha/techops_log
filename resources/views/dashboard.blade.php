@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Stats --}}
        <div class="grid grid-cols-4 gap-4">
            @php
                $statCards = [
                    [
                        'label' => 'Kegiatan Hari Ini',
                        'value' => $stats['kegiatan_hari_ini'],
                        'color' => 'emerald',
                        'sub' => 'aktivitas tercatat',
                    ],
                    [
                        'label' => 'Alert Aktif',
                        'value' => $stats['alert_aktif'],
                        'color' => 'amber',
                        'sub' => 'perlu perhatian',
                    ],
                    [
                        'label' => 'Kegiatan Bulan Ini',
                        'value' => $stats['kegiatan_bulan_ini'],
                        'color' => 'blue',
                        'sub' => 'total ' . now()->format('F'),
                    ],
                    [
                        'label' => 'Issue Resolved',
                        'value' => $stats['issue_resolved'],
                        'color' => 'emerald',
                        'sub' => 'bulan ini',
                    ],
                ];
                $colorMap = [
                    'emerald' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                    'amber' => 'text-amber-600 bg-amber-50 border-amber-100',
                    'blue' => 'text-blue-600 bg-blue-50 border-blue-100',
                ];
            @endphp

            @foreach ($statCards as $card)
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ $card['label'] }}</p>
                    <p class="text-3xl font-mono font-medium {{ explode(' ', $colorMap[$card['color']])[0] }} mb-1">
                        {{ $card['value'] }}</p>
                    <p class="text-xs text-gray-400">{{ $card['sub'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white border border-gray-200 rounded-xl">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-800">Aktivitas Terbaru Hari Ini</h2>
                <a href="{{ route('activity.index') }}" class="text-xs text-emerald-600 hover:underline">Lihat semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($todayLogs as $log)
                    @php
                        $statusColor = match ($log->status_kegiatan) {
                            'normal' => 'bg-emerald-400',
                            'ada_kendala' => 'bg-amber-400',
                            'kritis' => 'bg-red-400',
                            default => 'bg-gray-400',
                        };
                        $badgeColor = match ($log->status_kegiatan) {
                            'normal' => 'bg-emerald-50 text-emerald-700',
                            'ada_kendala' => 'bg-amber-50 text-amber-700',
                            'kritis' => 'bg-red-50 text-red-700',
                            default => 'bg-gray-50 text-gray-600',
                        };
                    @endphp
                    <div class="flex items-center gap-3 px-5 py-3">
                        <div class="w-2 h-2 rounded-full {{ $statusColor }} flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">{{ $log->activityType->nama_kegiatan }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $log->catatan ?? 'Tidak ada catatan' }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $badgeColor }} capitalize flex-shrink-0">
                            {{ str_replace('_', ' ', $log->status_kegiatan) }}
                        </span>
                        <span
                            class="text-xs text-gray-400 font-mono flex-shrink-0">{{ $log->tanggal_kegiatan->format('H:i') }}</span>
                        <a href="{{ route('activity.show', $log) }}"
                            class="text-xs text-gray-400 hover:text-emerald-600 flex-shrink-0">Detail →</a>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-sm text-gray-400">
                        Belum ada kegiatan hari ini.
                        <a href="{{ route('activity.create') }}" class="text-emerald-600 hover:underline ml-1">Input
                            sekarang →</a>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
