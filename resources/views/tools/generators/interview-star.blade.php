@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug ?? 'interview-star-standard');
    $careerPresets = \App\Support\CareerKeywordDictionary::positionPresets();
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,0.96fr),minmax(320px,1fr)]">
    <div class="order-2 rounded-3xl border border-slate-200 p-5 xl:order-1">
        <h2 class="text-lg font-semibold text-slate-900">Susun Jawaban Interview Metode STAR</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Gabungkan pengalaman, tindakan, dan hasil kerja Anda menjadi jawaban yang lebih runtut dan natural tanpa AI API.</p>

        <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-4" data-career-preset-group="interview-star">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <select data-career-preset-select="interview-star" class="h-11 min-w-0 flex-1 rounded-2xl border border-slate-200 bg-white px-4 text-sm">
                    <option value="">Pilih preset posisi cepat</option>
                    @foreach ($careerPresets as $key => $preset)
                        <option value="{{ $key }}">{{ $preset['label'] }}</option>
                    @endforeach
                </select>
                <button type="button" data-career-preset-fill="interview-star" class="inline-flex h-11 items-center rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700">Isi preset</button>
            </div>
        </div>

        <form id="interview-star-form" method="post" action="{{ route('tools.interview-star.preview') }}" class="mt-5 grid gap-5 sm:grid-cols-2" data-analytics-generate-form data-analytics-event="career_tool_generate" data-tool-name="interview_star">
            @csrf
            <input type="hidden" name="template_slug" value="{{ $selectedTemplateSlug }}">
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Posisi yang dilamar</label><input type="text" name="position_applied" value="{{ old('position_applied') }}" data-career-preset-field="label" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Admin HR"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Pertanyaan interview</label><input type="text" name="interview_question" value="{{ old('interview_question') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Ceritakan saat Anda menyelesaikan masalah di tempat kerja"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Level pengalaman</label><select name="experience_level" class="h-12 w-full rounded-2xl border border-slate-200 px-4"><option value="fresh-graduate" @selected(old('experience_level') === 'fresh-graduate')>Fresh Graduate</option><option value="berpengalaman" @selected(old('experience_level', 'berpengalaman') === 'berpengalaman')>Berpengalaman</option></select></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Jenis pengalaman</label><select name="experience_type" class="h-12 w-full rounded-2xl border border-slate-200 px-4">@foreach (['pengalaman-kerja' => 'Pengalaman kerja', 'magang' => 'Magang', 'organisasi' => 'Organisasi', 'project-kuliah' => 'Project kuliah', 'freelance' => 'Freelance', 'volunteer' => 'Volunteer'] as $value => $label)<option value="{{ $value }}" @selected(old('experience_type') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Situation</label><textarea name="situation" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: Tim saya menghadapi keterlambatan penyusunan laporan bulanan karena data dari beberapa divisi belum rapi.">{{ old('situation') }}</textarea></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Task</label><textarea name="task" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: Saya diminta membantu merapikan data dan memastikan laporan bisa selesai sebelum deadline.">{{ old('task') }}</textarea></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Action</label><textarea name="action" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: Saya membuat checklist data, follow up ke tiap PIC, lalu menyusun format rekap yang lebih mudah dicek ulang.">{{ old('action') }}</textarea></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Result</label><textarea name="result" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: Laporan selesai tepat waktu dan proses rekap berikutnya menjadi lebih terstruktur.">{{ old('result') }}</textarea></div>
            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Skill yang ingin ditonjolkan</label><input type="text" name="highlight_skills" value="{{ old('highlight_skills') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Problem solving, komunikasi, koordinasi, Excel"></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Gaya jawaban</label><select name="answer_style" class="h-12 w-full rounded-2xl border border-slate-200 px-4">@foreach (['formal' => 'Formal', 'natural-profesional' => 'Natural profesional', 'singkat' => 'Singkat', 'detail' => 'Detail'] as $value => $label)<option value="{{ $value }}" @selected(old('answer_style', 'natural-profesional') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div><label class="mb-2 block text-sm font-medium text-slate-700">Format output</label><select name="output_format" class="h-12 w-full rounded-2xl border border-slate-200 px-4">@foreach (['struktur-star' => 'Struktur STAR', 'paragraf-interview' => 'Paragraf interview', 'keduanya' => 'Keduanya'] as $value => $label)<option value="{{ $value }}" @selected(old('output_format', 'keduanya') === $value)>{{ $label }}</option>@endforeach</select></div>

            <div class="sm:col-span-2 flex flex-wrap gap-3">
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Generate jawaban STAR</button>
                <button formaction="{{ route('tools.interview-star.pdf') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="interview_star" data-export-type="pdf" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                <button formaction="{{ route('tools.interview-star.print') }}" formtarget="_blank" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="interview_star" data-export-type="print" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                <button formaction="{{ route('tools.interview-star.download') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="interview_star" data-export-type="txt" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Download TXT</button>
                <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset form</button>
            </div>
        </form>

        @if ($generatorPreview && $generatorPreview['copy_text'])
            <pre id="interview-star-copy-content" class="sr-only">{{ $generatorPreview['copy_text'] }}</pre>
            <button type="button" data-copy-target="#interview-star-copy-content" data-analytics-copy data-analytics-event="career_tool_copy" data-tool-name="interview_star" class="mt-4 inline-flex h-11 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy hasil</button>
        @endif
        <div class="mt-5 rounded-3xl border border-blue-100 bg-blue-50 p-4 text-sm leading-7 text-blue-900">
            Butuh latihan pertanyaan dulu? Coba <a href="{{ route('tools.show', 'simulasi-pertanyaan-interview') }}" class="font-semibold underline decoration-blue-300 underline-offset-4">Simulasi Pertanyaan Interview</a> atau rapikan profil profesional Anda lewat <a href="{{ route('tools.show', 'linkedin-headline-about-generator') }}" class="font-semibold underline decoration-blue-300 underline-offset-4">LinkedIn Headline & About Generator</a>.
        </div>
    </div>
    <div class="order-1 self-start rounded-3xl border border-slate-200 bg-slate-50/60 p-5 xl:order-2 xl:sticky xl:top-24">
        <h2 class="text-lg font-semibold text-slate-900">Preview Jawaban STAR</h2>
        <div class="mt-5 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-3 shadow-sm">
            @if ($generatorPreview)
                {!! $generatorPreview['html'] !!}
            @else
                <div class="flex min-h-[420px] items-center justify-center rounded-[1.25rem] border border-dashed border-slate-200 bg-slate-50 px-6 text-center text-sm leading-7 text-slate-500">Isi pengalaman Anda dengan pola STAR, lalu generate untuk melihat versi struktur dan paragraf interview yang lebih rapi.</div>
            @endif
        </div>
    </div>
</div>

<script type="application/json" id="career-tool-presets">@json($careerPresets, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
