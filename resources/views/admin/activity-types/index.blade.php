@extends('layouts.app')
@section('title', 'Admin — Jenis Kegiatan')

@section('content')
    <div class="space-y-4">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-gray-800">Manajemen Jenis Kegiatan</h2>
                <p class="text-xs text-gray-400 mt-0.5">Kelola jenis kegiatan yang tersedia di sistem</p>
            </div>
            <a href="{{ route('admin.activity-types.create') }}"
                class="flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold px-3 py-2 rounded-lg transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Tambah Jenis Kegiatan
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Icon
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama
                            Kegiatan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Deskripsi</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Total
                            Log</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($activityTypes as $type)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-xl">{{ $type->icon ?? '📋' }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $type->nama_kegiatan }}</td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $type->deskripsi ?? '—' }}</td>
                            <td class="px-4 py-3 text-center font-mono text-gray-600">{{ $type->activity_logs_count }}</td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $type->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-400' }}">
                                    {{ $type->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    {{-- Template Checklist --}}
                                    <a href="{{ route('admin.checklist-templates.index', $type) }}"
                                        class="text-xs border border-blue-200 text-blue-600 hover:bg-blue-50 px-2.5 py-1.5 rounded-lg transition">
                                        Checklist
                                    </a>
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.activity-types.edit', $type) }}"
                                        class="text-xs border border-gray-200 text-gray-500 hover:bg-gray-50 px-2.5 py-1.5 rounded-lg transition">
                                        Edit
                                    </a>
                                    {{-- Toggle Aktif --}}
                                    <form method="POST" action="{{ route('admin.activity-types.toggle', $type) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="text-xs border px-2.5 py-1.5 rounded-lg transition
                                        {{ $type->is_active
                                            ? 'border-amber-200 text-amber-600 hover:bg-amber-50'
                                            : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}">
                                            {{ $type->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    {{-- Hapus --}}
                                    @if ($type->activity_logs_count === 0)
                                        <form method="POST" action="{{ route('admin.activity-types.destroy', $type) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus jenis kegiatan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-xs border border-red-200 text-red-500 hover:bg-red-50 px-2.5 py-1.5 rounded-lg transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-400">Belum ada jenis
                                kegiatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
