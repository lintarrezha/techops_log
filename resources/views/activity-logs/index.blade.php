@extends('layouts.app')
@section('title', 'Riwayat Kegiatan')

@section('content')
    <div class="space-y-4">

        {{-- Filter --}}
        <form method="GET" class="flex gap-3 flex-wrap items-center bg-white border border-gray-200 rounded-xl px-4 py-3">
            <select name="type"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400">
                <option value="">Semua Kegiatan</option>
                @foreach ($activityTypes as $type)
                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                        {{ $type->nama_kegiatan }}
                    </option>
                @endforeach
            </select>

            <select name="bulan"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400">
                <option value="">Semua Bulan</option>
                @foreach (range(1, 12) as $b)
                    <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select name="status"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400">
                <option value="">Semua Status</option>
                <option value="normal" {{ request('status') === 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="ada_kendala" {{ request('status') === 'ada_kendala' ? 'selected' : '' }}>Ada Kendala</option>
                <option value="kritis" {{ request('status') === 'kritis' ? 'selected' : '' }}>Kritis</option>
            </select>

            <button type="submit"
                class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                Filter
            </button>

            @if (request()->hasAny(['type', 'bulan', 'status']))
                <a href="{{ route('activity.index') }}"
                    class="text-sm text-gray-400 hover:text-gray-600 px-3 py-2">Reset</a>
            @endif

            <div class="ml-auto flex gap-2">
                <a href="{{ route('report.pdf', request()->all()) }}"
                    class="flex items-center gap-1.5 border border-gray-200 text-gray-500 hover:bg-gray-50 text-xs font-medium px-3 py-2 rounded-lg transition">
                    ⬇ Export PDF
                </a>
            </div>
        </form>

        {{-- Table --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">ID
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Tanggal & Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Jenis Kegiatan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Teknisi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Aksi</th>
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
                                <td class="px-4 py-3 font-mono text-xs text-gray-400">#{{ $log->id }}</td>
                                <td class="px-4 py-3 font-mono text-xs text-gray-500">
                                    {{ $log->tanggal_kegiatan->format('d M Y') }}<br>
                                    <span class="text-gray-300">{{ $log->tanggal_kegiatan->format('H:i') }}</span>
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $log->activityType->icon ?? '' }} {{ $log->activityType->nama_kegiatan }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $log->status_kegiatan)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ $log->user->name }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('activity.show', $log) }}"
                                        class="text-xs border border-gray-200 hover:border-emerald-400 hover:text-emerald-600 px-3 py-1.5 rounded-lg transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-16 text-center text-sm text-gray-400">
                                    Belum ada data kegiatan.
                                    <a href="{{ route('activity.create') }}"
                                        class="text-emerald-600 hover:underline ml-1">Input sekarang →</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($logs->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} entri
                    </p>
                    {{ $logs->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
