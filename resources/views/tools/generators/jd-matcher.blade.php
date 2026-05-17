@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug ?? 'jd-matcher-standard');
    $careerPresets = \App\Support\CareerKeywordDictionary::positionPresets();
    $scoreRange = $generatorPreview['payload']['score'] ?? null;
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,0.96fr),minmax(320px,1fr)]">
    <div class="order-2 rounded-3xl border border-slate-200 p-5 xl:order-1">
        <h2 class="text-lg font-semibold text-slate-900">Bandingkan CV Singkat dengan Lowongan</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Masukkan isi lowongan dan profil singkat Anda. Sistem akan menghitung kecocokan berdasarkan keyword, skill, pengalaman, dan kelengkapan profil secara rule-based.</p>

        <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-4" data-career-preset-group="jd-matcher">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <select data-career-preset-select="jd-matcher" class="h-11 min-w-0 flex-1 rounded-2xl border border-slate-200 bg-white px-4 text-sm">
                    <option value="">Pilih preset posisi cepat</option>
                    @foreach ($careerPresets as $key => $preset)
                        <option value="{{ $key }}">{{ $preset['label'] }}</option>
                    @endforeach
                </select>
                <button type="button" data-career-preset-fill="jd-matcher" class="inline-flex h-11 items-center rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700">Isi preset</button>
            </div>
        </div>

        <form id="jd-matcher-form" method="post" action="{{ route('tools.jd-matcher.preview') }}" class="mt-5 grid gap-5 sm:grid-cols-2" data-analytics-generate-form data-analytics-event="jd_match_check" data-tool-name="job_description_matcher">
            @csrf
            <input type="hidden" name="template_slug" value="{{ $selectedTemplateSlug }}">
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Posisi target</label><input type="text" name="target_position" value="{{ old('target_position') }}" data-career-preset-field="label" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Finance Staff"></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Isi lowongan kerja / job description</label><textarea name="job_description" rows="6" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Tempel isi lowongan kerja di sini...">{{ old('job_description') }}</textarea></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Ringkasan profil atau isi CV singkat</label><textarea name="profile_summary" rows="5" data-career-preset-field="matcher_profile" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: Lulusan S1 Akuntansi dengan pengalaman magang finance dan terbiasa mengolah data di Excel.">{{ old('profile_summary') }}</textarea></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Skill yang dimiliki</label><input type="text" name="owned_skills" value="{{ old('owned_skills') }}" data-career-preset-field="skills" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Excel, rekonsiliasi, invoice, laporan keuangan"></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Pengalaman kerja singkat</label><textarea name="experience_summary" rows="4" data-career-preset-field="experience" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: Mengelola rekap invoice vendor, membantu rekonsiliasi data pembayaran, dan menyusun laporan mingguan.">{{ old('experience_summary') }}</textarea></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Pendidikan terakhir</label><input type="text" name="education_level" value="{{ old('education_level') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="S1 Akuntansi"></div>

            <div class="sm:col-span-2 flex flex-wrap gap-3">
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Cek kecocokan</button>
                <button formaction="{{ route('tools.jd-matcher.pdf') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="job_description_matcher" data-export-type="pdf" data-score-range="{{ $scoreRange }}" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                <button formaction="{{ route('tools.jd-matcher.word') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="job_description_matcher" data-export-type="word" data-score-range="{{ $scoreRange }}" class="h-12 rounded-2xl border border-blue-200 bg-white px-5 text-sm font-semibold text-blue-700">Download Word</button>
                <button formaction="{{ route('tools.jd-matcher.print') }}" formtarget="_blank" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="job_description_matcher" data-export-type="print" data-score-range="{{ $scoreRange }}" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                <button formaction="{{ route('tools.jd-matcher.download') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="job_description_matcher" data-export-type="txt" data-score-range="{{ $scoreRange }}" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Download TXT</button>
                <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset form</button>
            </div>
        </form>

        @if ($generatorPreview && $generatorPreview['copy_text'])
            <pre id="jd-matcher-copy-content" class="sr-only">{{ $generatorPreview['copy_text'] }}</pre>
            <button type="button" data-copy-target="#jd-matcher-copy-content" data-analytics-copy data-analytics-event="career_tool_copy" data-tool-name="job_description_matcher" data-score-range="{{ $scoreRange }}" class="mt-4 inline-flex h-11 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy hasil analisis</button>
        @endif
        <div class="mt-5 rounded-3xl border border-blue-100 bg-blue-50 p-4 text-sm leading-7 text-blue-900">
            Setelah melihat match score, cek struktur CV Anda di <a href="{{ route('tools.show', 'ats-cv-checker') }}" class="font-semibold underline decoration-blue-300 underline-offset-4">ATS CV Checker</a> dan rapikan surat pengantar lewat <a href="{{ route('tools.show', 'generator-surat-lamaran-kerja') }}" class="font-semibold underline decoration-blue-300 underline-offset-4">Generator Surat Lamaran</a>.
        </div>
    </div>
    <div class="order-1 self-start rounded-3xl border border-slate-200 bg-slate-50/60 p-5 xl:order-2 xl:sticky xl:top-24">
        <h2 class="text-lg font-semibold text-slate-900">Preview Analisis</h2>
        <div class="mt-5 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-3 shadow-sm">
            @if ($generatorPreview)
                {!! $generatorPreview['html'] !!}
            @else
                <div class="flex min-h-[420px] items-center justify-center rounded-[1.25rem] border border-dashed border-slate-200 bg-slate-50 px-6 text-center text-sm leading-7 text-slate-500">Masukkan lowongan dan profil singkat untuk melihat match score, keyword cocok, skill gap, dan saran optimasi CV.</div>
            @endif
        </div>
    </div>
</div>

<script type="application/json" id="career-tool-presets">@json($careerPresets, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
