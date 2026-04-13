@extends('layouts.app')
@section('title', 'Edit Pertanyaan')

@section('content')
    <div class="max-w-lg">
        <div class="flex items-center gap-2 mb-6 text-xs text-gray-400">
            <a href="{{ route('admin.activity-types.index') }}" class="hover:text-gray-600">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.checklist-templates.index', $activityType) }}"
                class="hover:text-gray-600">{{ $activityType->nama_kegiatan }}</a>
            <span>/</span>
            <span class="text-gray-600">Edit Pertanyaan</span>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-5">Edit Pertanyaan</h2>

            <form method="POST"
                action="{{ route('admin.checklist-templates.update', [$activityType, $checklistTemplate]) }}"
                class="space-y-4" x-data="{ tipe: '{{ old('tipe_input', $checklistTemplate->tipe_input) }}' }">
                @csrf @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Label Seksi
                            *</label>
                        <input type="text" name="section_label"
                            value="{{ old('section_label', $checklistTemplate->section_label) }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 uppercase transition"
                            maxlength="5">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Nama Seksi
                            *</label>
                        <input type="text" name="section_name"
                            value="{{ old('section_name', $checklistTemplate->section_name) }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Pertanyaan
                        *</label>
                    <input type="text" name="pertanyaan" value="{{ old('pertanyaan', $checklistTemplate->pertanyaan) }}"
                        required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Tipe Input
                            *</label>
                        <select name="tipe_input" x-model="tipe"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition">
                            <option value="text" {{ $checklistTemplate->tipe_input === 'text' ? 'selected' : '' }}>
                                Text</option>
                            <option value="number" {{ $checklistTemplate->tipe_input === 'number' ? 'selected' : '' }}>
                                Number</option>
                            <option value="radio" {{ $checklistTemplate->tipe_input === 'radio' ? 'selected' : '' }}>
                                Radio</option>
                            <option value="select" {{ $checklistTemplate->tipe_input === 'select' ? 'selected' : '' }}>
                                Select</option>
                            <option value="textarea" {{ $checklistTemplate->tipe_input === 'textarea' ? 'selected' : '' }}>
                                Textarea</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Satuan</label>
                        <input type="text" name="satuan" value="{{ old('satuan', $checklistTemplate->satuan) }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition"
                            placeholder="%, GB, Mbps">
                    </div>
                </div>

                <div x-show="tipe === 'radio' || tipe === 'select'" x-cloak>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Opsi Jawaban
                        *</label>
                    <textarea name="opsi_jawaban" rows="4"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition resize-none"
                        placeholder="Satu opsi per baris">{{ old('opsi_jawaban', $opsiText) }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Tulis satu opsi per baris</p>
                </div>

                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_required" value="1"
                            {{ old('is_required', $checklistTemplate->is_required) ? 'checked' : '' }}
                            class="w-4 h-4 accent-emerald-500">
                        <span class="text-sm text-gray-700">Pertanyaan wajib diisi</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('admin.checklist-templates.index', $activityType) }}"
                        class="text-sm text-gray-500 border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="text-sm bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-5 py-2 rounded-lg transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
