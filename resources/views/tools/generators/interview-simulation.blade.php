@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug ?? 'interview-simulation-standard');
    $careerPresets = \App\Support\CareerKeywordDictionary::positionPresets();
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,0.96fr),minmax(320px,1fr)]">
    <div class="order-2 rounded-3xl border border-slate-200 p-5 xl:order-1">
        <h2 class="text-lg font-semibold text-slate-900">Siapkan Simulasi Interview</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Pilih posisi, level pengalaman, dan jenis interview untuk mendapatkan daftar pertanyaan yang lebih relevan tanpa AI API.</p>

        <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-4" data-career-preset-group="interview-simulation">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <select data-career-preset-select="interview-simulation" class="h-11 min-w-0 flex-1 rounded-2xl border border-slate-200 bg-white px-4 text-sm">
                    <option value="">Pilih preset posisi cepat</option>
                    @foreach ($careerPresets as $key => $preset)
                        <option value="{{ $key }}">{{ $preset['label'] }}</option>
                    @endforeach
                </select>
                <button type="button" data-career-preset-fill="interview-simulation" class="inline-flex h-11 items-center rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700">Isi preset</button>
            </div>
        </div>

        <form id="interview-simulation-form" method="post" action="{{ route('tools.interview-simulation.preview') }}" class="mt-5 grid gap-5 sm:grid-cols-2" data-analytics-generate-form data-analytics-event="career_tool_generate" data-tool-name="interview_simulation">
            @csrf
            <input type="hidden" name="template_slug" value="{{ $selectedTemplateSlug }}">

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Posisi yang dilamar</label>
                <input type="text" name="position_applied" value="{{ old('position_applied') }}" data-career-preset-field="label" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Customer Service Staff">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Kategori posisi</label>
                <select name="position_category" data-career-preset-field="category" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    @foreach (['admin' => 'Admin', 'customer-service' => 'Customer Service', 'sales' => 'Sales', 'finance' => 'Finance', 'hr' => 'HR', 'it-support' => 'IT Support', 'digital-marketing' => 'Digital Marketing', 'backend-developer' => 'Backend Developer', 'frontend-developer' => 'Frontend Developer', 'project-manager' => 'Project Manager', 'operations' => 'Operations', 'fresh-graduate' => 'Fresh Graduate', 'lainnya' => 'Lainnya'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('position_category') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Level pengalaman</label>
                <select name="experience_level" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    @foreach (['fresh-graduate' => 'Fresh Graduate', '0-1-tahun' => '0-1 tahun', '1-3-tahun' => '1-3 tahun', '3-5-tahun' => '3-5 tahun', '5-plus-tahun' => 'Lebih dari 5 tahun'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('experience_level') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Jenis interview</label>
                <select name="interview_type" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    @foreach (['hr-interview' => 'HR Interview', 'user-interview' => 'User Interview', 'technical-interview' => 'Technical Interview', 'final-interview' => 'Final Interview', 'campuran' => 'Campuran'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('interview_type', 'campuran') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Jumlah pertanyaan</label>
                <select name="question_count" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    @foreach (['5', '10', '15', '20'] as $count)
                        <option value="{{ $count }}" @selected(old('question_count', '10') === $count)>{{ $count }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Sertakan tips menjawab</label>
                <select name="include_tips" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    <option value="ya" @selected(old('include_tips', 'ya') === 'ya')>Ya</option>
                    <option value="tidak" @selected(old('include_tips') === 'tidak')>Tidak</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex flex-wrap gap-3">
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Generate pertanyaan</button>
                <button formaction="{{ route('tools.interview-simulation.pdf') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="interview_simulation" data-export-type="pdf" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                <button formaction="{{ route('tools.interview-simulation.print') }}" formtarget="_blank" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="interview_simulation" data-export-type="print" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                <button formaction="{{ route('tools.interview-simulation.download') }}" data-analytics-export data-analytics-event="career_tool_export" data-tool-name="interview_simulation" data-export-type="txt" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Download TXT</button>
                <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset form</button>
            </div>
        </form>

        @if ($generatorPreview && $generatorPreview['copy_text'])
            <pre id="interview-simulation-copy-content" class="sr-only">{{ $generatorPreview['copy_text'] }}</pre>
            <button type="button" data-copy-target="#interview-simulation-copy-content" data-analytics-copy data-analytics-event="career_tool_copy" data-tool-name="interview_simulation" class="mt-4 inline-flex h-11 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy semua pertanyaan</button>
        @endif

        <div class="mt-5 rounded-3xl border border-amber-100 bg-amber-50 p-4 text-sm leading-7 text-amber-900">
            Input Anda hanya dipakai untuk menghasilkan hasil pada sesi ini. Jangan masukkan data yang terlalu sensitif jika tidak diperlukan.
        </div>
        <div class="mt-5 rounded-3xl border border-blue-100 bg-blue-50 p-4 text-sm leading-7 text-blue-900">
            Lanjutkan latihan Anda dengan <a href="{{ route('tools.show', 'interview-answer-star') }}" class="font-semibold underline decoration-blue-300 underline-offset-4">Generator Jawaban Interview STAR</a> atau cek kesiapan lamaran di <a href="{{ route('tools.show', 'ats-cv-checker') }}" class="font-semibold underline decoration-blue-300 underline-offset-4">ATS CV Checker</a>.
        </div>
    </div>

    <div class="order-1 self-start rounded-3xl border border-slate-200 bg-slate-50/60 p-5 xl:order-2 xl:sticky xl:top-24">
        <h2 class="text-lg font-semibold text-slate-900">Preview Simulasi Interview</h2>
        <p class="mt-1 text-sm text-slate-500">Daftar pertanyaan akan muncul di sini setelah Anda generate.</p>

        <div class="mt-5 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-3 shadow-sm">
            @if ($generatorPreview)
                {!! $generatorPreview['html'] !!}
            @else
                <div class="flex min-h-[420px] items-center justify-center rounded-[1.25rem] border border-dashed border-slate-200 bg-slate-50 px-6 text-center text-sm leading-7 text-slate-500">
                    Pilih posisi dan jenis interview, lalu klik Generate pertanyaan untuk melihat simulasi interview yang bisa langsung dipelajari.
                </div>
            @endif
        </div>
    </div>
</div>

<script type="application/json" id="career-tool-presets">@json($careerPresets, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
