@extends('layouts.app')
@section('title', 'Edit Jenis Kegiatan')

@section('content')
    <div class="max-w-lg">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.activity-types.index') }}" class="text-sm text-gray-400 hover:text-gray-600">←
                Kembali</a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-800 mb-5">Edit Jenis Kegiatan</h2>

            <form method="POST" action="{{ route('admin.activity-types.update', $activityType) }}" class="space-y-4">
                @csrf @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                        Nama Kegiatan <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="nama_kegiatan"
                        value="{{ old('nama_kegiatan', $activityType->nama_kegiatan) }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition
                              @error('nama_kegiatan') border-red-300 @enderror">
                    @error('nama_kegiatan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label
                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <input type="text" name="deskripsi" value="{{ old('deskripsi', $activityType->deskripsi) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Icon
                        (Emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon', $activityType->icon) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('admin.activity-types.index') }}"
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
