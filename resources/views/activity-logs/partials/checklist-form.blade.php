@php $no = 1; @endphp

@foreach ($templates as $sectionLabel => $items)
    @php $sectionName = $items->first()->section_name; @endphp

    <div class="mb-5">
        <h3 class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-3 flex items-center gap-2">
            {{ $sectionLabel }}. {{ $sectionName }}
            <span class="flex-1 h-px bg-gray-100"></span>
        </h3>

        <div class="space-y-3">
            @foreach ($items as $template)
                <div class="bg-white border border-gray-200 rounded-xl p-4 focus-within:border-emerald-300 transition">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $no++ }}. {{ $template->pertanyaan }}
                        @if ($template->is_required)
                            <span class="text-red-400 text-xs">*</span>
                        @endif
                    </label>

                    @if ($template->tipe_input === 'radio')
                        <div class="flex gap-2 flex-wrap">
                            @foreach ($template->opsi_jawaban as $opsi)
                                <label
                                    class="flex items-center gap-2 px-3 py-1.5 border border-gray-200 rounded-lg text-sm cursor-pointer hover:border-emerald-300 transition has-[:checked]:border-emerald-400 has-[:checked]:bg-emerald-50">
                                    <input type="radio" name="answers[{{ $template->id }}]" value="{{ $opsi }}"
                                        {{ $template->is_required ? 'required' : '' }} class="accent-emerald-500">
                                    {{ $opsi }}
                                </label>
                            @endforeach
                        </div>
                    @elseif($template->tipe_input === 'select')
                        <select name="answers[{{ $template->id }}]"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400 transition">
                            <option value="">— Pilih —</option>
                            @foreach ($template->opsi_jawaban as $opsi)
                                <option value="{{ $opsi }}">{{ $opsi }}</option>
                            @endforeach
                        </select>
                    @elseif($template->tipe_input === 'number')
                        <div class="flex">
                            <input type="number" name="answers[{{ $template->id }}]" min="0" placeholder="0"
                                class="flex-1 border border-gray-200 rounded-l-lg px-3 py-2 text-sm font-mono outline-none focus:border-emerald-400 transition"
                                {{ $template->is_required ? 'required' : '' }}>
                            @if ($template->satuan)
                                <span
                                    class="bg-gray-50 border border-l-0 border-gray-200 rounded-r-lg px-3 py-2 text-sm text-gray-400 font-mono">
                                    {{ $template->satuan }}
                                </span>
                            @endif
                        </div>
                    @elseif($template->tipe_input === 'textarea')
                        <div id="catatan-wrapper-{{ $template->id }}">
                            <textarea name="answers[{{ $template->id }}][]" placeholder="Tulis catatan..."
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400 transition resize-none"
                                rows="3"></textarea>
                        </div>
                        <button type="button" onclick="tambahCatatan({{ $template->id }})"
                            class="mt-2 flex items-center gap-1.5 text-xs border border-dashed border-gray-300 text-gray-400 hover:border-emerald-400 hover:text-emerald-600 px-3 py-1.5 rounded-lg transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            Tambah Catatan Lainnya
                        </button>
                    @else
                        <input type="text" name="answers[{{ $template->id }}]" placeholder="Isi jawaban..."
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400 transition"
                            {{ $template->is_required ? 'required' : '' }}>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endforeach

<script>
    function tambahCatatan(templateId) {
        const wrapper = document.getElementById('catatan-wrapper-' + templateId);
        const ta = document.createElement('textarea');
        ta.name = `answers[${templateId}][]`;
        ta.placeholder = 'Catatan tambahan...';
        ta.rows = 2;
        ta.className =
            'w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-emerald-400 transition resize-none mt-2';
        wrapper.appendChild(ta);
        ta.focus();
    }
</script>
