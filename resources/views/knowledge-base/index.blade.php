@extends('layouts.app')
@section('title', 'Knowledge Base')

@section('content')
    <div class="space-y-4">

        {{-- Search & Filter --}}
        <form method="GET" class="flex gap-3 flex-wrap items-center bg-white border border-gray-200 rounded-xl px-4 py-3">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="🔍 Cari masalah, solusi, kata kunci..."
                class="flex-1 min-w-48 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400 transition">

            <select name="kategori"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400">
                <option value="">Semua Kategori</option>
                @foreach ($kategoris as $k)
                    <option value="{{ $k }}" {{ request('kategori') === $k ? 'selected' : '' }}>
                        {{ $k }}</option>
                @endforeach
            </select>

            <button type="submit"
                class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                Cari
            </button>

            @if (request()->hasAny(['search', 'kategori']))
                <a href="{{ route('kb.index') }}" class="text-sm text-gray-400 hover:text-gray-600">Reset</a>
            @endif
        </form>

        {{-- List --}}
        <div class="space-y-3">
            @forelse($issues as $issue)
                <div class="bg-white border border-gray-200 rounded-xl p-5 hover:border-gray-300 transition">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="text-sm font-semibold text-gray-800">{{ $issue->judul_masalah }}</h3>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if ($issue->kategori)
                                <span
                                    class="text-xs bg-blue-50 text-blue-600 px-2.5 py-0.5 rounded-full">{{ $issue->kategori }}</span>
                            @endif
                            <span
                                class="text-xs px-2.5 py-0.5 rounded-full {{ $issue->status === 'resolved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                {{ $issue->status === 'resolved' ? '✓ Resolved' : 'Open' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex gap-4 text-xs text-gray-400 mb-3">
                        <span>📅 {{ $issue->created_at->translatedFormat('d F Y') }}</span>
                        @if ($issue->activityLog)
                            <span>🖥️ {{ $issue->activityLog->activityType->nama_kegiatan }}</span>
                            <span>👤 {{ $issue->activityLog->user->name }}</span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-500 mb-3">{{ $issue->deskripsi_masalah }}</p>

                    @if ($issue->solusi)
                        <div
                            class="border-l-2 border-emerald-300 pl-3 py-2 bg-emerald-50/50 rounded-r-lg text-sm text-gray-600">
                            <span class="text-xs font-semibold text-emerald-600 block mb-0.5">Solusi:</span>
                            {{ $issue->solusi }}
                        </div>
                    @endif

                    @if ($issue->status === 'open')
                        <div class="mt-3 flex justify-end">
                            <form method="POST" action="{{ route('kb.resolve', $issue) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="text-xs border border-emerald-300 text-emerald-600 hover:bg-emerald-50 px-3 py-1.5 rounded-lg transition">
                                    Tandai Resolved
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white border border-gray-200 rounded-xl py-16 text-center text-sm text-gray-400">
                    Belum ada issue tercatat di Knowledge Base.
                    <p class="mt-1 text-xs">Tambahkan issue dari halaman <a href="{{ route('activity.index') }}"
                            class="text-emerald-600 hover:underline">Detail Kegiatan</a>.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($issues->hasPages())
            <div class="flex justify-center">{{ $issues->withQueryString()->links() }}</div>
        @endif

    </div>
@endsection
