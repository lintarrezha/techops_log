@extends('layouts.app')
@section('title', 'Template Checklist — ' . $activityType->nama_kegiatan)

@section('content')
    <div class="space-y-4">

        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('admin.activity-types.index') }}"
                        class="text-xs text-gray-400 hover:text-gray-600">Admin</a>
                    <span class="text-gray-300 text-xs">/</span>
                    <span class="text-xs text-gray-600">{{ $activityType->nama_kegiatan }}</span>
                </div>
                <h2 class="text-sm font-semibold text-gray-800">
                    {{ $activityType->icon }} Template Checklist — {{ $activityType->nama_kegiatan }}
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">Kelola pertanyaan checklist untuk jenis kegiatan ini</p>
            </div>
            <a href="{{ route('admin.checklist-templates.create', $activityType) }}"
                class="flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold px-3 py-2 rounded-lg transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Tambah Pertanyaan
            </a>
        </div>

        @if ($templates->isEmpty())
            <div class="bg-white border border-gray-200 rounded-xl py-16 text-center text-sm text-gray-400">
                Belum ada pertanyaan. Klik "Tambah Pertanyaan" untuk mulai.
            </div>
        @else
            @foreach ($templates as $sectionLabel => $items)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">
                            {{ $sectionLabel }}. {{ $items->first()->section_name }}
                        </span>
                        <span class="text-xs text-gray-400">({{ $items->count() }} pertanyaan)</span>
                    </div>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-50">
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-400">No</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-400">Pertanyaan</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-400">Tipe Input</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-400">Satuan</th>
                                <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-400">Wajib</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-400">Urutan</th>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($items as $template)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 font-mono text-xs text-gray-400">{{ $template->urutan }}</td>
                                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $template->pertanyaan }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded text-xs font-mono bg-gray-100 text-gray-600">
                                            {{ $template->tipe_input }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-400 text-xs font-mono">{{ $template->satuan ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($template->is_required)
                                            <span class="text-emerald-500 text-xs font-bold">✓</span>
                                        @else
                                            <span class="text-gray-300 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-1">
                                            <form method="POST"
                                                action="{{ route('admin.checklist-templates.move-up', [$activityType, $template]) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="text-gray-300 hover:text-gray-600 px-1.5 py-1 border border-gray-100 hover:border-gray-300 rounded transition text-xs">↑</button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('admin.checklist-templates.move-down', [$activityType, $template]) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="text-gray-300 hover:text-gray-600 px-1.5 py-1 border border-gray-100 hover:border-gray-300 rounded transition text-xs">↓</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.checklist-templates.edit', [$activityType, $template]) }}"
                                                class="text-xs border border-gray-200 text-gray-500 hover:bg-gray-50 px-2.5 py-1.5 rounded-lg transition">
                                                Edit
                                            </a>
                                            <form method="POST"
                                                action="{{ route('admin.checklist-templates.destroy', [$activityType, $template]) }}"
                                                onsubmit="return confirm('Yakin hapus pertanyaan ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-xs border border-red-200 text-red-500 hover:bg-red-50 px-2.5 py-1.5 rounded-lg transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endif

    </div>
@endsection
