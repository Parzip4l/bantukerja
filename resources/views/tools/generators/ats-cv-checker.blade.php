@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug ?? 'ats-cv-checker-standard');
    $careerPresets = \App\Support\CareerKeywordDictionary::positionPresets();
    $scoreRange = $generatorPreview['payload']['score'] ?? null;
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,0.96fr),minmax(320px,1fr)]">
    <div class="order-2 rounded-3xl border border-slate-200 p-5 xl:order-1">
        <h2 class="text-lg font-semibold text-slate-900">Cek Skor CV ATS Friendly</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Upload PDF CV berbasis teks atau paste isi CV Anda untuk mengecek struktur dasar, keyword, skill, dan elemen ATS-friendly secara sederhana. Tool ini bukan ATS resmi perusahaan.</p>

        <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-4" data-career-preset-group="ats-cv-checker">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <select data-career-preset-select="ats-cv-checker" class="h-11 min-w-0 flex-1 rounded-2xl border border-slate-200 bg-white px-4 text-sm">
                    <option value="">Pilih preset posisi cepat</option>
                    @foreach ($careerPresets as $key => $preset)
                        <option value="{{ $key }}">{{ $preset['label'] }}</option>
                    @endforeach
                </select>
                <button type="button" data-career-preset-fill="ats-cv-checker" class="inline-flex h-11 items-center rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700">Isi preset</button>
            </div>
        </div>

        <form id="ats-cv-checker-form" method="post" action="{{ route('tools.ats-cv-checker.preview') }}" enctype="multipart/form-data" class="mt-5 grid gap-5 sm:grid-cols-2" data-analytics-generate-form data-analytics-event="cv_score_check" data-tool-name="ats_cv_checker">
            @csrf
            <input type="hidden" name="template_slug" value="{{ $selectedTemplateSlug }}">
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Posisi target</label><input type="text" name="target_position" value="{{ old('target_position') }}" data-career-preset-field="label" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Frontend Developer"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Level pengalaman</label><select name="experience_level" class="h-12 w-full rounded-2xl border border-slate-200 px-4">@foreach (['fresh-graduate' => 'Fresh Graduate', '0-1-tahun' => '0-1 tahun', '1-3-tahun' => '1-3 tahun', '3-5-tahun' => '3-5 tahun', '5-plus-tahun' => 'Lebih dari 5 tahun'] as $value => $label)<option value="{{ $value }}" @selected(old('experience_level') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Upload PDF CV</label>
                <input type="file" name="cv_pdf" accept="application/pdf,.pdf" class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700">
                <p class="mt-2 text-xs leading-6 text-slate-500">Gunakan PDF berbasis teks, bukan hasil scan gambar. File hanya dipakai untuk membaca isi CV di request ini dan tidak disimpan permanen.</p>
            </div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Isi CV</label><textarea name="cv_text" rows="12" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Upload PDF CV atau paste isi CV Anda di sini...">{{ old('cv_text') }}</textarea><p class="mt-2 text-xs leading-6 text-slate-500">Jika PDF tidak terbaca penuh, Anda tetap bisa paste isi CV secara manual. Tool ini tidak menyimpan isi CV ke database.</p></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Skill utama</label><input type="text" name="main_skills" value="{{ old('main_skills') }}" data-career-preset-field="skills" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="React, JavaScript, CSS, Git"></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Industri target</label><input type="text" name="target_industry" value="{{ old('target_industry') }}" data-career-preset-field="industry" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Teknologi, startup, manufaktur"></div>

            <div class="sm:col-span-2 flex flex-wrap gap-3">
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Cek skor CV</button>
                <button formaction="{{ route('tools.ats-cv-checker.pdf') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="ats_cv_checker" data-export-type="pdf" data-score-range="{{ $scoreRange }}" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                <button formaction="{{ route('tools.ats-cv-checker.word') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="ats_cv_checker" data-export-type="word" data-score-range="{{ $scoreRange }}" class="h-12 rounded-2xl border border-blue-200 bg-white px-5 text-sm font-semibold text-blue-700">Download Word</button>
                <button formaction="{{ route('tools.ats-cv-checker.print') }}" formtarget="_blank" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="ats_cv_checker" data-export-type="print" data-score-range="{{ $scoreRange }}" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                <button formaction="{{ route('tools.ats-cv-checker.download') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="ats_cv_checker" data-export-type="txt" data-score-range="{{ $scoreRange }}" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Download TXT</button>
                <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset form</button>
            </div>
        </form>

        @if ($generatorPreview && $generatorPreview['copy_text'])
            <pre id="ats-cv-checker-copy-content" class="sr-only">{{ $generatorPreview['copy_text'] }}</pre>
            <button type="button" data-copy-target="#ats-cv-checker-copy-content" data-analytics-copy data-analytics-event="career_tool_copy" data-tool-name="ats_cv_checker" data-score-range="{{ $scoreRange }}" class="mt-4 inline-flex h-11 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy hasil</button>
        @endif
        <div class="mt-5 rounded-3xl border border-blue-100 bg-blue-50 p-4 text-sm leading-7 text-blue-900">
            Kalau skor CV sudah cukup baik, lanjutkan ke <a href="{{ route('tools.show', 'simulasi-pertanyaan-interview') }}" class="font-semibold underline decoration-blue-300 underline-offset-4">Simulasi Interview</a> dan <a href="{{ route('tools.show', 'linkedin-headline-about-generator') }}" class="font-semibold underline decoration-blue-300 underline-offset-4">LinkedIn Generator</a> untuk melengkapi persiapan karier Anda.
        </div>
    </div>
    <div class="order-1 self-start rounded-3xl border border-slate-200 bg-slate-50/60 p-5 xl:order-2 xl:sticky xl:top-24">
        <h2 class="text-lg font-semibold text-slate-900">Preview Skor CV</h2>
        <div class="mt-5 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-3 shadow-sm">
            @if ($generatorPreview)
                {!! $generatorPreview['html'] !!}
            @else
                <div class="flex min-h-[420px] items-center justify-center rounded-[1.25rem] border border-dashed border-slate-200 bg-slate-50 px-6 text-center text-sm leading-7 text-slate-500">Tempel isi CV untuk melihat skor ATS sederhana, breakdown, checklist, dan rekomendasi keyword posisi target.</div>
            @endif
        </div>
    </div>
</div>

<script type="application/json" id="career-tool-presets">@json($careerPresets, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
