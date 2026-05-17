@php
    $chips = ['CV ATS', 'Invoice', 'Kwitansi', 'THR', 'Slip Gaji', 'Surat Lamaran', 'Laporan Kerja', 'Template', 'Contact'];
@endphp

<div
    class="pointer-events-none fixed bottom-4 right-4 z-50 sm:bottom-6 sm:right-6"
    data-assistant-widget
    data-assistant-endpoint="{{ route('api.assistant.search') }}"
>
    <button
        type="button"
        class="pointer-events-auto inline-flex h-12 items-center gap-2 rounded-full border border-slate-200 bg-slate-900 px-5 text-sm font-semibold text-white shadow-[0_16px_32px_rgba(15,23,42,0.18)] hover:bg-blue-700"
        data-assistant-toggle
        aria-expanded="false"
        aria-controls="assistant-panel"
    >
        Butuh bantuan?
    </button>

    <div
        id="assistant-panel"
        class="pointer-events-auto mt-3 hidden w-[min(92vw,380px)] max-h-[calc(100vh-2rem)] flex flex-col overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-[0_28px_80px_rgba(15,23,42,0.18)] sm:max-h-[min(80vh,760px)]"
        data-assistant-panel
    >
        <div class="shrink-0 flex items-start justify-between gap-4 border-b border-slate-200 bg-slate-50 px-5 py-4">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Asisten Bantu Kerja</h2>
                <p class="mt-1 text-xs leading-6 text-slate-500">Bantu cari tools, template, artikel, dan halaman penting.</p>
            </div>
            <button type="button" class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-600" data-assistant-close>Tutup</button>
        </div>

        <div class="min-h-0 flex-1 overflow-y-auto px-4 py-4" data-assistant-messages>
            <div class="rounded-3xl bg-slate-50 px-4 py-3 text-sm leading-7 text-slate-700">
                <p class="font-semibold text-slate-900">Halo, saya Asisten Bantu Kerja.</p>
                <p class="mt-1">Saya bisa membantu kamu menemukan tools, template, artikel, dan halaman penting di BantuKerja.online.</p>
                <div class="mt-3 text-xs leading-6 text-slate-500">
                    Contoh: tools buat CV ATS, generator invoice, template kwitansi, cara hitung THR, laporan kerja harian, contact Bantu Kerja
                </div>
            </div>
        </div>

        <div class="shrink-0 border-t border-slate-200 bg-white px-4 py-4">
            <div class="mb-3 flex flex-wrap gap-2" data-assistant-chips>
                @foreach ($chips as $chip)
                    <button type="button" class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-700 hover:border-blue-300 hover:text-blue-700" data-assistant-chip="{{ $chip }}">{{ $chip }}</button>
                @endforeach
            </div>

            <form class="grid gap-3" data-assistant-form>
                <label for="assistant-input" class="sr-only">Tulis pertanyaan</label>
                <input id="assistant-input" type="text" maxlength="300" class="h-12 rounded-2xl border border-slate-200 px-4 text-sm text-slate-900 outline-none focus:border-blue-300" placeholder="Cari tools, template, artikel, atau contact..." data-assistant-input>
                <button type="submit" class="inline-flex h-11 items-center justify-center rounded-2xl bg-slate-900 px-4 text-sm font-semibold text-white hover:bg-blue-700" data-assistant-submit>
                    Kirim
                </button>
            </form>

            <p class="mt-3 text-[11px] leading-5 text-slate-500">
                Asisten ini hanya membantu menemukan konten di BantuKerja.online. Jangan masukkan data sensitif.
            </p>
        </div>
    </div>
</div>
