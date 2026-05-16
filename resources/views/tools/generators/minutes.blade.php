@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug);
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,0.95fr),minmax(320px,1fr)]">
    <div class="generator-section order-2 xl:order-1">
        <h2 class="text-lg font-semibold text-slate-900">Isi Data Berita Acara</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Data hanya dipakai untuk membangun preview dan file unduhan sesi aktif. Tidak ada isi berita acara yang disimpan permanen oleh BantuKerja.online.</p>

        <form id="minutes-generator-form" method="post" action="{{ route('tools.minutes.preview') }}" class="mt-5 grid gap-4 sm:grid-cols-2">
            @csrf

            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Judul berita acara</label>
                <input type="text" name="title" value="{{ old('title') }}" class="tool-input" placeholder="Berita Acara Serah Terima Dokumen">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nomor dokumen</label>
                <input type="text" name="document_number" value="{{ old('document_number') }}" class="tool-input" placeholder="BAST/05/2026">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal acara</label>
                <input type="date" name="event_date" value="{{ old('event_date') }}" class="tool-input">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Lokasi</label>
                <input type="text" name="location" value="{{ old('location') }}" class="tool-input" placeholder="Jakarta Selatan">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Pembuka berita acara</label>
                <textarea name="opening" rows="3" class="tool-textarea" placeholder="Contoh: Pada hari ini telah dilakukan serah terima dokumen antara para pihak berikut.">{{ old('opening') }}</textarea>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Pihak pertama</label>
                <input type="text" name="first_party_name" value="{{ old('first_party_name') }}" class="tool-input" placeholder="Nama penyerah">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Jabatan pihak pertama</label>
                <input type="text" name="first_party_role" value="{{ old('first_party_role') }}" class="tool-input" placeholder="Manager Operasional">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Pihak kedua</label>
                <input type="text" name="second_party_name" value="{{ old('second_party_name') }}" class="tool-input" placeholder="Nama penerima">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Jabatan pihak kedua</label>
                <input type="text" name="second_party_role" value="{{ old('second_party_role') }}" class="tool-input" placeholder="Supervisor Administrasi">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Isi berita acara</label>
                <textarea name="content" rows="7" class="tool-textarea" placeholder="Tulis isi pokok berita acara. Pisahkan per poin atau paragraf agar lebih mudah dibaca.">{{ old('content') }}</textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Penutup</label>
                <textarea name="closing" rows="3" class="tool-textarea" placeholder="Contoh: Demikian berita acara ini dibuat dan ditandatangani untuk dipergunakan sebagaimana mestinya.">{{ old('closing') }}</textarea>
            </div>

            <div class="generator-actions sm:col-span-2">
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Preview</button>
                <button formaction="{{ route('tools.minutes.download') }}" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                <button formaction="{{ route('tools.minutes.print') }}" formtarget="_blank" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset</button>
            </div>
        </form>
    </div>

    <div class="generator-section order-1 self-start bg-slate-50/60 xl:order-2 xl:sticky xl:top-24">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Preview Berita Acara</h2>
                <p class="mt-1 text-sm text-slate-500">Pilih template dan cek struktur dokumen sebelum diunduh.</p>
            </div>
            @if ($generatorPreview)
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $generatorPreview['template']->name }}</span>
            @endif
        </div>

        <div class="mt-5 rounded-[1.75rem] border border-slate-200 bg-white p-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Pilih Template Desain Berita Acara</h3>
                    <p class="mt-1 text-xs leading-6 text-slate-500">Gunakan template formal untuk dokumen standar atau professional untuk tampilan yang lebih rapih dan kuat.</p>
                </div>
                <a href="#minutes-generator-form" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-xs font-semibold text-slate-700 xl:hidden">Isi data</a>
            </div>

            <div class="mt-4 grid gap-3">
                @foreach ($generatorTemplates as $templateOption)
                    <label class="cursor-pointer">
                        <input type="radio" name="template_slug" value="{{ $templateOption->slug }}" form="minutes-generator-form" class="peer sr-only" @checked($selectedTemplateSlug === $templateOption->slug)>
                        <span class="block rounded-3xl border border-slate-200 bg-white p-4 transition hover:border-blue-200 peer-checked:border-blue-300 peer-checked:bg-blue-50/70 peer-checked:ring-2 peer-checked:ring-blue-100">
                            <span class="flex items-start justify-between gap-3">
                                <span>
                                    <span class="block text-sm font-semibold text-slate-900">{{ $templateOption->name }}</span>
                                    <span class="mt-2 block text-xs leading-6 text-slate-500">{{ $templateOption->description }}</span>
                                </span>
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">Gratis</span>
                            </span>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mt-5 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-3 shadow-sm">
            @if ($generatorPreview)
                {!! $generatorPreview['html'] !!}
            @else
                <div class="flex min-h-[420px] items-center justify-center rounded-[1.25rem] border border-dashed border-slate-200 bg-slate-50 px-6 text-center text-sm leading-7 text-slate-500">
                    Isi detail berita acara lalu klik Preview untuk melihat hasil dokumen sebelum diunduh.
                </div>
            @endif
        </div>
    </div>
</div>
