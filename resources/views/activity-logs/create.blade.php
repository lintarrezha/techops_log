@extends('layouts.app')
@section('title', 'Input Kegiatan')

@section('content')
    <div class="max-w-2xl" x-data="{ step: 1, selectedType: null, selectedName: '' }">

        {{-- Step Indicator --}}
        <div class="flex items-center gap-2 mb-8">
            <div class="flex items-center gap-2">
                <div :class="step >= 1 ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-500'"
                    class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition">1</div>
                <span class="text-xs font-medium" :class="step >= 1 ? 'text-emerald-600' : 'text-gray-400'">Pilih
                    Kegiatan</span>
            </div>
            <div class="flex-1 h-px bg-gray-200 mx-2"></div>
            <div class="flex items-center gap-2">
                <div :class="step >= 2 ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-500'"
                    class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition">2</div>
                <span class="text-xs font-medium" :class="step >= 2 ? 'text-emerald-600' : 'text-gray-400'">Isi
                    Checklist</span>
            </div>
            <div class="flex-1 h-px bg-gray-200 mx-2"></div>
            <div class="flex items-center gap-2">
                <div :class="step >= 3 ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-500'"
                    class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition">3</div>
                <span class="text-xs font-medium" :class="step >= 3 ? 'text-emerald-600' : 'text-gray-400'">Selesai</span>
            </div>
        </div>

        {{-- STEP 1: Pilih Jenis Kegiatan --}}
        <div x-show="step === 1">
            <h2 class="text-lg font-bold text-gray-800 mb-1">Pilih Jenis Kegiatan</h2>
            <p class="text-sm text-gray-400 mb-6">Pilih kegiatan yang akan dilakukan</p>

            <div class="grid grid-cols-2 gap-3">
                @foreach ($activityTypes as $type)
                    <div @click="selectedType = {{ $type->id }}; selectedName = '{{ $type->nama_kegiatan }}'"
                        :class="selectedType === {{ $type->id }} ?
                            'border-emerald-400 bg-emerald-50 ring-1 ring-emerald-400' :
                            'border-gray-200 bg-white hover:border-gray-300'"
                        class="border-2 rounded-xl p-5 cursor-pointer transition">
                        <div class="text-3xl mb-3">{{ $type->icon }}</div>
                        <h3 class="text-sm font-semibold text-gray-800 mb-1">{{ $type->nama_kegiatan }}</h3>
                        <p class="text-xs text-gray-400">{{ $type->deskripsi }}</p>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end mt-6">
                <button
                    @click="if(selectedType) { step = 2; loadChecklist(selectedType) } else { alert('Pilih jenis kegiatan terlebih dahulu') }"
                    class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                    Lanjut →
                </button>
            </div>
        </div>

        {{-- STEP 2: Form Checklist --}}
        <div x-show="step === 2" x-cloak>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-800" x-text="'Checklist — ' + selectedName"></h2>
                    <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('l, d F Y') }} &bull;
                        {{ auth()->user()->name }}</p>
                </div>
                <button @click="step = 1"
                    class="text-xs text-gray-400 hover:text-gray-600 border border-gray-200 px-3 py-1.5 rounded-lg transition">
                    ← Ganti Kegiatan
                </button>
            </div>

            <form method="POST" action="{{ route('activity.store') }}" id="checklist-form">
                @csrf
                <input type="hidden" name="activity_type_id" x-bind:value="selectedType">
                <input type="hidden" name="status_kegiatan" id="status_kegiatan" value="normal">

                {{-- Area ini diisi via AJAX --}}
                <div id="checklist-container">
                    <div class="text-center py-12 text-sm text-gray-400">
                        <svg class="w-8 h-8 mx-auto mb-3 text-gray-300 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                        </svg>
                        Memuat checklist...
                    </div>
                </div>

                {{-- Seksi Custom --}}
                <div id="custom-sections-container" class="space-y-4 mt-4"></div>

                {{-- Tombol Tambah Seksi --}}
                <div class="flex items-center gap-3 my-4">
                    <button type="button" onclick="showAddSectionModal()"
                        class="flex items-center gap-2 border-2 border-dashed border-emerald-300 text-emerald-600 hover:bg-emerald-50 text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Tambah Seksi Baru
                    </button>
                    <span class="text-xs text-gray-400">Tambahkan seksi dan pertanyaan custom sesuai kebutuhan</span>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-between items-center pt-5 border-t border-gray-100 mt-4">
                    <button type="button" @click="step = 1"
                        class="text-sm text-gray-500 hover:text-gray-700 border border-gray-200 px-4 py-2 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition">
                        Submit Laporan →
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- Modal Tambah Seksi --}}
    <div id="modal-overlay" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center"
        onclick="closeModal(event)">
        <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4 shadow-xl">
            <h3 class="text-base font-bold text-gray-800 mb-1">Tambah Seksi Baru</h3>
            <p class="text-xs text-gray-400 mb-5">Beri nama seksi lalu pilih cara pengisian</p>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Nama Seksi
                    *</label>
                <input type="text" id="modal-section-name" maxlength="60"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-emerald-400 transition"
                    placeholder="Contoh: Kondisi Ruang Server">
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Cara Pengisian
                    Awal</label>
                <div class="space-y-2">
                    <label
                        class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-emerald-300 transition">
                        <input type="radio" name="modal-opt" value="pertanyaan" checked class="mt-0.5 accent-emerald-500">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Mulai dengan pertanyaan</p>
                            <p class="text-xs text-gray-400">Tambahkan pertanyaan terstruktur di seksi ini</p>
                        </div>
                    </label>
                    <label
                        class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-emerald-300 transition">
                        <input type="radio" name="modal-opt" value="catatan" class="mt-0.5 accent-emerald-500">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Langsung isi catatan</p>
                            <p class="text-xs text-gray-400">Tanpa pertanyaan — tulis catatan bebas langsung</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex gap-2 justify-end">
                <button onclick="document.getElementById('modal-overlay').classList.add('hidden')"
                    class="text-sm text-gray-500 border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button onclick="createCustomSection()"
                    class="text-sm bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-4 py-2 rounded-lg transition">Buat
                    Seksi →</button>
            </div>
        </div>
    </div>

    <script>
        const checklistRoute = "{{ route('activity.checklist', ['activityType' => '__ID__']) }}";

        function loadChecklist(typeId) {
            const url = checklistRoute.replace('__ID__', typeId);
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.text())
                .then(html => {
                    document.getElementById('checklist-container').innerHTML = html;
                });
        }

        function showAddSectionModal() {
            document.getElementById('modal-section-name').value = '';
            document.getElementById('modal-overlay').classList.remove('hidden');
            setTimeout(() => document.getElementById('modal-section-name').focus(), 100);
        }

        function closeModal(e) {
            if (e.target.id === 'modal-overlay') document.getElementById('modal-overlay').classList.add('hidden');
        }

        let sectionCount = 0;
        const sectionLetters = ['E', 'F', 'G', 'H', 'I', 'J'];

        function createCustomSection() {
            const name = document.getElementById('modal-section-name').value.trim();
            if (!name) {
                document.getElementById('modal-section-name').focus();
                return;
            }

            const opt = document.querySelector('input[name="modal-opt"]:checked').value;
            const letter = sectionLetters[sectionCount] ?? 'X';
            const id = 'cs-' + sectionCount;
            sectionCount++;

            const html = `
    <div id="${id}" class="border border-gray-200 rounded-xl overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-100 px-4 py-3 flex items-center gap-2">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider flex-1">${letter}. ${name}</span>
            <button type="button" onclick="document.getElementById('${id}').remove()"
                    class="text-gray-300 hover:text-red-400 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-4 space-y-3" id="${id}-body"></div>
        <div class="px-4 pb-4 flex gap-2">
            <button type="button" onclick="addQuestion('${id}-body')"
                    class="flex items-center gap-1.5 text-xs border border-dashed border-blue-300 text-blue-500 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                Tambah Pertanyaan
            </button>
            <button type="button" onclick="addDirectNote('${id}-body')"
                    class="flex items-center gap-1.5 text-xs border border-dashed border-emerald-300 text-emerald-600 hover:bg-emerald-50 px-3 py-1.5 rounded-lg transition">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                Tambah Catatan Langsung
            </button>
        </div>
    </div>`;

            document.getElementById('custom-sections-container').insertAdjacentHTML('beforeend', html);
            document.getElementById('modal-overlay').classList.add('hidden');

            if (opt === 'pertanyaan') addQuestion(id + '-body');
            else addDirectNote(id + '-body');
        }

        let qCount = 0;

        function addQuestion(bodyId) {
            qCount++;
            const id = 'q-' + qCount;
            document.getElementById(bodyId).insertAdjacentHTML('beforeend', `
    <div id="${id}" class="bg-gray-50 border border-gray-200 rounded-lg p-3">
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xs font-medium text-gray-400 flex-1">Pertanyaan</span>
            <button type="button" onclick="document.getElementById('${id}').remove()" class="text-gray-300 hover:text-red-400 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <input type="text" name="custom[${id}][label]" placeholder="Tulis pertanyaan Anda... (contoh: Suhu ruang server?)"
               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mb-2 outline-none focus:border-emerald-400 transition bg-white">
        <textarea name="custom[${id}][jawaban]" placeholder="Jawaban / catatan..."
                  class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none outline-none focus:border-emerald-400 transition bg-white" rows="2"></textarea>
    </div>`);
        }

        let nCount = 0;

        function addDirectNote(bodyId) {
            nCount++;
            const id = 'n-' + nCount;
            document.getElementById(bodyId).insertAdjacentHTML('beforeend', `
    <div id="${id}" class="border-l-2 border-emerald-300 bg-emerald-50/50 rounded-r-lg p-3">
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xs font-medium text-emerald-600 flex-1">📝 Catatan Langsung</span>
            <button type="button" onclick="document.getElementById('${id}').remove()" class="text-gray-300 hover:text-red-400 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <textarea name="custom[${id}][jawaban]" placeholder="Tulis catatan atau observasi langsung di sini..."
                  class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none outline-none focus:border-emerald-400 transition bg-white" rows="2"></textarea>
    </div>`);
        }
    </script>
@endsection
