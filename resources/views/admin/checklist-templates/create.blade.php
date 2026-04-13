@extends('layouts.app')
@section('title', 'Tambah Pertanyaan')

@section('content')
    <div class="max-w-lg">
        <div class="flex items-center gap-2 mb-6 text-xs text-gray-400">
            <a href="{{ route('admin.activity-types.index') }}" class="hover:text-gray-600">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.checklist-templates.index', $activityType) }}"
                class="hover:text-gray-600">{{ $activityType->nama_kegiatan }}</a>
            <span>/</span>
            <span class="text-gray-600">Tambah Pertanyaan</span>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-5">Tambah Pertanyaan Baru</h2>

            <form method="POST" action="{{ route('admin.checklist-templates.store', $activityType) }}" class="space-y-4"
                x-data="{ tipe: 'text' }">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                            Label Seksi <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="section_label" value="{{ old('section_label') }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition uppercase"
                            placeholder="A" maxlength="5">
                        <p class="text-xs text-gray-400 mt-1">Huruf seksi (A, B, C, dst)</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                            Nama Seksi <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="section_name" value="{{ old('section_name') }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition"
                            placeholder="Status Umum Server">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                        Pertanyaan <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="pertanyaan" value="{{ old('pertanyaan') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition"
                        placeholder="Contoh: CPU Usage saat ini">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                            Tipe Input <span class="text-red-400">*</span>
                        </label>
                        <select name="tipe_input" x-model="tipe"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition">
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="radio">Radio (Pilihan)</option>
                            <option value="select">Select (Dropdown)</option>
                            <option value="textarea">Textarea</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Satuan</label>
                        <input type="text" name="satuan" value="{{ old('satuan') }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition"
                            placeholder="%, GB, Mbps">
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ada</p>
                    </div>
                </div>

                {{-- Opsi Jawaban (muncul hanya untuk radio/select) --}}
                <div x-show="tipe === 'radio' || tipe === 'select'" x-cloak>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                        Opsi Jawaban <span class="text-red-400">*</span>
                    </label>
                    <textarea name="opsi_jawaban" rows="4"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition resize-none"
                        placeholder="Normal&#10;Ada Kendala&#10;Kritis">{{ old('opsi_jawaban') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Tulis satu opsi per baris</p>
                </div>

                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_required" value="1"
                            {{ old('is_required', '1') ? 'checked' : '' }} class="w-4 h-4 accent-emerald-500">
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
                        Simpan Pertanyaan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
