@extends('layouts.app')
@section('title', 'Detail Kegiatan #' . $activityLog->id)

@section('content')
    <div class="max-w-2xl space-y-4">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('activity.index') }}" class="text-sm text-gray-400 hover:text-gray-600">← Kembali</a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-base font-bold text-gray-800">
                        {{ $activityLog->activityType->icon ?? '' }} {{ $activityLog->activityType->nama_kegiatan }}
                    </h2>
                    <p class="text-xs text-gray-400 mt-1">
                        #{{ $activityLog->id }} &bull;
                        {{ $activityLog->tanggal_kegiatan->translatedFormat('l, d F Y — H:i') }} &bull;
                        {{ $activityLog->user->name }}
                    </p>
                </div>
                @php
                    $badgeColor = match ($activityLog->status_kegiatan) {
                        'normal' => 'bg-emerald-50 text-emerald-700',
                        'ada_kendala' => 'bg-amber-50 text-amber-700',
                        'kritis' => 'bg-red-50 text-red-700',
                        default => 'bg-gray-50 text-gray-600',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeColor }} flex-shrink-0">
                    {{ ucfirst(str_replace('_', ' ', $activityLog->status_kegiatan)) }}
                </span>
            </div>

            {{-- Jawaban checklist --}}
            @if ($activityLog->answers->count())
                @php $grouped = $activityLog->answers->groupBy(fn($a) => $a->template->section_label ?? '-'); @endphp
                @foreach ($grouped as $label => $answers)
                    <div class="mb-4">
                        <h3
                            class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-3 flex items-center gap-2">
                            {{ $label }}. {{ $answers->first()->template->section_name }}
                            <span class="flex-1 h-px bg-gray-100"></span>
                        </h3>
                        <div class="space-y-2">
                            @foreach ($answers as $answer)
                                <div class="flex gap-3 text-sm">
                                    <span class="text-gray-400 min-w-0 flex-1">{{ $answer->template->pertanyaan }}</span>
                                    <span class="font-medium text-gray-800 text-right">
                                        {{ $answer->jawaban ?? '—' }}
                                        @if ($answer->template->satuan)
                                            {{ $answer->template->satuan }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Catatan --}}
            @if ($activityLog->catatan)
                <div class="border-t border-gray-100 pt-4 mt-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Catatan</p>
                    <p class="text-sm text-gray-700">{{ $activityLog->catatan }}</p>
                </div>
            @endif
        </div>

        {{-- Issues / Knowledge Base --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800">Issue / Knowledge Base</h3>
                <button onclick="document.getElementById('form-issue').classList.toggle('hidden')"
                    class="text-xs bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-lg transition">
                    + Tambah Issue
                </button>
            </div>

            {{-- Form tambah issue --}}
            <div id="form-issue" class="hidden p-5 border-b border-gray-100 bg-gray-50">
                <form method="POST" action="{{ route('kb.store') }}" class="space-y-3">
                    @csrf
                    <input type="hidden" name="activity_log_id" value="{{ $activityLog->id }}">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Judul Masalah *</label>
                        <input type="text" name="judul_masalah" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400"
                            placeholder="Ringkasan masalah...">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Kategori</label>
                            <input type="text" name="kategori"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400"
                                placeholder="Performance, Koneksi, dll">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
                            <select name="status"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400">
                                <option value="open">Open</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Deskripsi Masalah *</label>
                        <textarea name="deskripsi_masalah" required rows="2"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400 resize-none"
                            placeholder="Jelaskan detail masalah..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Solusi</label>
                        <textarea name="solusi" rows="2"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400 resize-none"
                            placeholder="Solusi yang diterapkan (jika sudah ada)..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('form-issue').classList.add('hidden')"
                            class="text-sm text-gray-500 border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-100 transition">Batal</button>
                        <button type="submit"
                            class="text-sm bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-4 py-2 rounded-lg transition">Simpan
                            Issue</button>
                    </div>
                </form>
            </div>

            {{-- Daftar issues --}}
            <div class="divide-y divide-gray-50">
                @forelse($activityLog->issues as $issue)
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <h4 class="text-sm font-semibold text-gray-800">{{ $issue->judul_masalah }}</h4>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if ($issue->kategori)
                                    <span
                                        class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">{{ $issue->kategori }}</span>
                                @endif
                                @if ($issue->status === 'open')
                                    <form method="POST" action="{{ route('kb.resolve', $issue) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="text-xs border border-emerald-300 text-emerald-600 hover:bg-emerald-50 px-2 py-0.5 rounded-full transition">
                                            Tandai Resolved
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">✓
                                        Resolved</span>
                                @endif
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mb-2">{{ $issue->deskripsi_masalah }}</p>
                        @if ($issue->solusi)
                            <div
                                class="border-l-2 border-emerald-300 pl-3 text-sm text-gray-600 bg-emerald-50/50 py-2 rounded-r-lg">
                                <span class="text-xs font-semibold text-emerald-600">Solusi: </span>{{ $issue->solusi }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada issue tercatat.</div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
