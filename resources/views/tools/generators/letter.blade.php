@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug);
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,0.95fr),minmax(320px,1fr)]">
    <div class="generator-section order-2 xl:order-1">
            <h2 class="text-lg font-semibold text-slate-900">Isi Data Surat</h2>
            <p class="mt-2 text-sm leading-7 text-slate-600">Data Anda hanya dipakai untuk membuat preview, PDF, print view, dan copy text. BantuKerja.online tidak menyimpannya secara permanen.</p>

            <form id="letter-generator-form" method="post" action="{{ route('tools.leave-letter.preview') }}" class="mt-5 grid gap-4 sm:grid-cols-2">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Jabatan</label>
                    <input type="text" name="position" value="{{ old('position') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Perusahaan</label>
                    <input type="text" name="company" value="{{ old('company') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal surat</label>
                    <input type="date" name="date" value="{{ old('date', old('leave_date')) }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Kota</label>
                    <input type="text" name="city" value="{{ old('city') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Penerima surat</label>
                    <input type="text" name="recipient" value="{{ old('recipient') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="HRD / Atasan / Pimpinan">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Alasan izin</label>
                    <textarea name="reason" rows="5" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('reason') }}</textarea>
                </div>

                <div class="generator-actions sm:col-span-2">
                    <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Preview</button>
                    <button formaction="{{ route('tools.leave-letter.pdf') }}" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                    <button formaction="{{ route('tools.leave-letter.print') }}" formtarget="_blank" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                    <button formaction="{{ route('tools.leave-letter.download') }}" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Download TXT</button>
                    <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset</button>
                </div>
            </form>

            @if ($generatorPreview && $generatorPreview['copy_text'])
                <pre id="letter-copy-content" class="sr-only">{{ $generatorPreview['copy_text'] }}</pre>
                <button type="button" data-copy-target="#letter-copy-content" class="mt-4 inline-flex h-11 w-full items-center justify-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700 sm:w-auto">Copy text surat</button>
            @endif
        </div>

        <div id="letter-preview-panel" class="generator-section order-1 self-start bg-slate-50/60 xl:order-2 xl:sticky xl:top-24">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Preview Surat</h2>
                    <p class="mt-1 text-sm text-slate-500">Pilih template lalu cek hasil surat di panel yang sama.</p>
                </div>
                @if ($generatorPreview)
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $generatorPreview['template']->name }}</span>
                @endif
            </div>

            <div class="mt-5 rounded-[1.75rem] border border-slate-200 bg-white p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Pilih Template Desain Surat</h3>
                        <p class="mt-1 text-xs leading-6 text-slate-500">Template dipilih di sini supaya preview selalu dekat dan mudah dibandingkan.</p>
                    </div>
                    <a href="#letter-generator-form" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-xs font-semibold text-slate-700 xl:hidden">Isi data</a>
                </div>

                <div class="mt-4 grid gap-3">
                    @foreach ($generatorTemplates as $templateOption)
                        <label class="cursor-pointer">
                            <input type="radio" name="template_slug" value="{{ $templateOption->slug }}" form="letter-generator-form" class="peer sr-only" @checked($selectedTemplateSlug === $templateOption->slug)>
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
                        Pilih template surat, isi data, lalu klik Preview untuk melihat hasil dokumen.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
