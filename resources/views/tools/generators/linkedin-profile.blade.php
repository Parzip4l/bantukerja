@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug ?? 'linkedin-profile-standard');
    $careerPresets = \App\Support\CareerKeywordDictionary::positionPresets();
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,0.96fr),minmax(320px,1fr)]">
    <div class="order-2 rounded-3xl border border-slate-200 p-5 xl:order-1">
        <h2 class="text-lg font-semibold text-slate-900">Buat Headline & About LinkedIn</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Tool ini menyusun beberapa opsi headline dan About LinkedIn menggunakan template pintar berbasis posisi, level, dan skill utama tanpa AI API.</p>

        <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-4" data-career-preset-group="linkedin-profile">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <select data-career-preset-select="linkedin-profile" class="h-11 min-w-0 flex-1 rounded-2xl border border-slate-200 bg-white px-4 text-sm">
                    <option value="">Pilih preset posisi cepat</option>
                    @foreach ($careerPresets as $key => $preset)
                        <option value="{{ $key }}">{{ $preset['label'] }}</option>
                    @endforeach
                </select>
                <button type="button" data-career-preset-fill="linkedin-profile" class="inline-flex h-11 items-center rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700">Isi preset</button>
            </div>
        </div>

        <form id="linkedin-profile-form" method="post" action="{{ route('tools.linkedin-profile.preview') }}" class="mt-5 grid gap-5 sm:grid-cols-2" data-analytics-generate-form data-analytics-event="career_tool_generate" data-tool-name="linkedin_profile">
            @csrf
            <input type="hidden" name="template_slug" value="{{ $selectedTemplateSlug }}">
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Profesi / posisi target</label><input type="text" name="target_position" value="{{ old('target_position') }}" data-career-preset-field="label" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Digital Marketing Specialist"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Level</label><select name="career_level" class="h-12 w-full rounded-2xl border border-slate-200 px-4">@foreach (['fresh-graduate' => 'Fresh Graduate', 'junior' => 'Junior', 'mid-level' => 'Mid-level', 'senior' => 'Senior', 'manager' => 'Manager'] as $value => $label)<option value="{{ $value }}" @selected(old('career_level') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Bidang industri</label><input type="text" name="industry" value="{{ old('industry') }}" data-career-preset-field="industry" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Teknologi, FMCG, edukasi"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Skill utama 1</label><input type="text" name="primary_skill_1" value="{{ old('primary_skill_1') }}" data-career-preset-field="skills.0" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="SEO"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Skill utama 2</label><input type="text" name="primary_skill_2" value="{{ old('primary_skill_2') }}" data-career-preset-field="skills.1" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Content Strategy"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Skill utama 3</label><input type="text" name="primary_skill_3" value="{{ old('primary_skill_3') }}" data-career-preset-field="skills.2" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Analytics"></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Pengalaman utama / aktivitas utama</label><textarea name="main_experience" rows="4" data-career-preset-field="experience" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: Mengelola campaign digital, membuat laporan performa mingguan, dan berkolaborasi dengan tim desain serta sales.">{{ old('main_experience') }}</textarea></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Target karier</label><input type="text" name="career_target" value="{{ old('career_target') }}" data-career-preset-field="career_target" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Mengembangkan karier di digital marketing performance"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Gaya bahasa</label><select name="language_style" class="h-12 w-full rounded-2xl border border-slate-200 px-4">@foreach (['profesional' => 'Profesional', 'friendly' => 'Friendly', 'singkat' => 'Singkat', 'fresh-graduate' => 'Fresh Graduate', 'senior' => 'Senior'] as $value => $label)<option value="{{ $value }}" @selected(old('language_style', 'profesional') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Bahasa</label><select name="language" class="h-12 w-full rounded-2xl border border-slate-200 px-4"><option value="id" @selected(old('language', 'id') === 'id')>Indonesia</option><option value="en" @selected(old('language') === 'en')>English basic</option></select></div>

            <div class="sm:col-span-2 flex flex-wrap gap-3">
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Generate headline & about</button>
                <button formaction="{{ route('tools.linkedin-profile.pdf') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="linkedin_profile" data-export-type="pdf" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                <button formaction="{{ route('tools.linkedin-profile.print') }}" formtarget="_blank" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="linkedin_profile" data-export-type="print" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                <button formaction="{{ route('tools.linkedin-profile.download') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="linkedin_profile" data-export-type="txt" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Download TXT</button>
                <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset form</button>
            </div>
        </form>

        @if ($generatorPreview && $generatorPreview['copy_text'])
            <pre id="linkedin-profile-copy-content" class="sr-only">{{ $generatorPreview['copy_text'] }}</pre>
            <pre id="linkedin-headlines-copy-content" class="sr-only">{{ implode("\n", $generatorPreview['payload']['headlines'] ?? []) }}</pre>
            <pre id="linkedin-about-copy-content" class="sr-only">{{ ($generatorPreview['payload']['about_short'] ?? '')."\n\n".($generatorPreview['payload']['about_professional'] ?? '') }}</pre>
            <div class="mt-4 flex flex-wrap gap-3">
                <button type="button" data-copy-target="#linkedin-headlines-copy-content" data-analytics-copy data-analytics-event="career_tool_copy" data-tool-name="linkedin_profile" class="inline-flex h-11 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy headline</button>
                <button type="button" data-copy-target="#linkedin-about-copy-content" data-analytics-copy data-analytics-event="career_tool_copy" data-tool-name="linkedin_profile" class="inline-flex h-11 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy about</button>
                <button type="button" data-copy-target="#linkedin-profile-copy-content" data-analytics-copy data-analytics-event="career_tool_copy" data-tool-name="linkedin_profile" class="inline-flex h-11 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy semua</button>
            </div>
        @endif
        <div class="mt-5 rounded-3xl border border-blue-100 bg-blue-50 p-4 text-sm leading-7 text-blue-900">
            Setelah profil LinkedIn rapi, lanjutkan ke <a href="{{ route('tools.show', 'generator-cv-ats') }}" class="font-semibold underline decoration-blue-300 underline-offset-4">Generator CV ATS</a> atau <a href="{{ route('tools.show', 'generator-surat-lamaran-kerja') }}" class="font-semibold underline decoration-blue-300 underline-offset-4">Generator Surat Lamaran</a>.
        </div>
    </div>
    <div class="order-1 self-start rounded-3xl border border-slate-200 bg-slate-50/60 p-5 xl:order-2 xl:sticky xl:top-24">
        <h2 class="text-lg font-semibold text-slate-900">Preview LinkedIn</h2>
        <div class="mt-5 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-3 shadow-sm">
            @if ($generatorPreview)
                {!! $generatorPreview['html'] !!}
            @else
                <div class="flex min-h-[420px] items-center justify-center rounded-[1.25rem] border border-dashed border-slate-200 bg-slate-50 px-6 text-center text-sm leading-7 text-slate-500">Isi posisi target dan skill utama untuk melihat 3 opsi headline serta 2 opsi About LinkedIn.</div>
            @endif
        </div>
    </div>
</div>

<script type="application/json" id="career-tool-presets">@json($careerPresets, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
