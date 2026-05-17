@php
    $selectedTemplateSlug = old('template_slug', $generatorPreview['template_slug'] ?? $generatorTemplates->first()?->slug);
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,0.96fr),minmax(320px,1fr)]">
    <div class="order-2 rounded-3xl border border-slate-200 p-5 xl:order-1">
        <h2 class="text-lg font-semibold text-slate-900">Isi Data Surat Lamaran</h2>
        <p class="mt-2 text-sm leading-7 text-slate-600">Lengkapi data inti agar surat lamaran terasa natural, sopan, dan tetap mudah diedit sebelum dikirim ke recruiter.</p>

        <form id="application-letter-generator-form" method="post" action="{{ route('tools.application-letter.preview') }}" class="mt-5 grid gap-5 sm:grid-cols-2">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nama lengkap</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Nadia Putri Pratama">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="nadia@email.com">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nomor HP</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="0812xxxxxxx">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Kota domisili</label>
                <input type="text" name="city" value="{{ old('city') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Bandung">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Posisi yang dilamar</label>
                <input type="text" name="position_applied" value="{{ old('position_applied') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Digital Marketing Specialist">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nama perusahaan</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="PT Maju Berkembang">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nama HRD / rekruter</label>
                <input type="text" name="recruiter_name" value="{{ old('recruiter_name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Ibu Rina">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Sumber info lowongan</label>
                <input type="text" name="job_source" value="{{ old('job_source') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="LinkedIn, Jobstreet, website perusahaan">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Pendidikan terakhir</label>
                <input type="text" name="education_level" value="{{ old('education_level') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="S1 Manajemen">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Jurusan</label>
                <input type="text" name="major" value="{{ old('major') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Manajemen Pemasaran">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Level pengalaman</label>
                <select name="experience_level" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    <option value="fresh-graduate" @selected(old('experience_level') === 'fresh-graduate')>Fresh Graduate</option>
                    <option value="1-2-tahun" @selected(old('experience_level') === '1-2-tahun')>1-2 tahun</option>
                    <option value="3-5-tahun" @selected(old('experience_level') === '3-5-tahun')>3-5 tahun</option>
                    <option value="5-plus-tahun" @selected(old('experience_level') === '5-plus-tahun')>Lebih dari 5 tahun</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal surat</label>
                <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Jenis surat</label>
                <select name="letter_type" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    <option value="formal" @selected(old('letter_type') === 'formal')>Formal</option>
                    <option value="singkat-profesional" @selected(old('letter_type') === 'singkat-profesional')>Singkat Profesional</option>
                    <option value="fresh-graduate" @selected(old('letter_type') === 'fresh-graduate')>Fresh Graduate</option>
                    <option value="berpengalaman" @selected(old('letter_type') === 'berpengalaman')>Berpengalaman</option>
                    <option value="magang" @selected(old('letter_type') === 'magang')>Magang / Internship</option>
                    <option value="email-lamaran" @selected(old('letter_type') === 'email-lamaran')>Email Lamaran</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Bahasa output</label>
                <select name="language_style" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    <option value="indonesia-formal" @selected(old('language_style', 'indonesia-formal') === 'indonesia-formal')>Indonesia formal</option>
                    <option value="indonesia-santai-profesional" @selected(old('language_style') === 'indonesia-santai-profesional')>Indonesia santai profesional</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Ringkasan pengalaman / keahlian</label>
                <textarea name="experience_summary" rows="5" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Contoh: Saya memiliki pengalaman mengelola media sosial brand, membuat konten campaign, dan mengoptimalkan performa iklan digital untuk meningkatkan leads.">{{ old('experience_summary') }}</textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">Skill utama</label>
                <textarea name="main_skills" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="SEO, Google Ads, Canva, Komunikasi, Microsoft Excel">{{ old('main_skills') }}</textarea>
                <p class="mt-2 text-xs leading-6 text-slate-500">Pisahkan dengan koma agar sistem bisa merangkum skill penting dengan lebih rapi.</p>
            </div>

            <div class="sm:col-span-2 flex flex-wrap gap-3">
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Generate surat</button>
                <button formaction="{{ route('tools.application-letter.pdf') }}" class="h-12 rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download PDF</button>
                <button formaction="{{ route('tools.application-letter.print') }}" formtarget="_blank" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Print</button>
                <button formaction="{{ route('tools.application-letter.download') }}" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Download TXT</button>
                <button type="reset" class="h-12 rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Reset form</button>
            </div>
        </form>

        @if ($generatorPreview && $generatorPreview['copy_text'])
            <pre id="application-letter-copy-content" class="sr-only">{{ $generatorPreview['copy_text'] }}</pre>
            <button type="button" data-copy-target="#application-letter-copy-content" class="mt-4 inline-flex h-11 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Copy hasil</button>
        @endif
    </div>

    <div id="application-letter-preview-panel" class="order-1 self-start rounded-3xl border border-slate-200 bg-slate-50/60 p-5 xl:order-2 xl:sticky xl:top-24">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Preview Surat Lamaran</h2>
                <p class="mt-1 text-sm text-slate-500">Pilih gaya surat dan lihat hasilnya langsung di panel yang sama.</p>
            </div>
            @if ($generatorPreview)
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $generatorPreview['template']->name }}</span>
            @endif
        </div>

        <div class="mt-5 rounded-[1.75rem] border border-slate-200 bg-white p-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Pilih Template Surat</h3>
                    <p class="mt-1 text-xs leading-6 text-slate-500">Gunakan formal untuk kebutuhan umum, professional untuk versi ringkas, atau email untuk body email lamaran.</p>
                </div>
                <a href="#application-letter-generator-form" class="inline-flex h-10 items-center rounded-2xl border border-slate-200 px-4 text-xs font-semibold text-slate-700 xl:hidden">Isi data</a>
            </div>

            <div class="mt-4 grid gap-3">
                @foreach ($generatorTemplates as $templateOption)
                    <label class="cursor-pointer">
                        <input type="radio" name="template_slug" value="{{ $templateOption->slug }}" form="application-letter-generator-form" class="peer sr-only" @checked($selectedTemplateSlug === $templateOption->slug)>
                        <span class="block rounded-3xl border border-slate-200 bg-white p-4 transition hover:border-blue-200 peer-checked:border-blue-300 peer-checked:bg-blue-50/70 peer-checked:ring-2 peer-checked:ring-blue-100">
                            <span class="block text-sm font-semibold text-slate-900">{{ $templateOption->name }}</span>
                            <span class="mt-2 block text-xs leading-6 text-slate-500">{{ $templateOption->description }}</span>
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
                    Isi data lamaran, pilih gaya surat, lalu klik Generate surat untuk melihat hasil siap copy.
                </div>
            @endif
        </div>

        <div class="mt-5 rounded-3xl border border-amber-100 bg-amber-50 p-4 text-sm leading-7 text-amber-900">
            Lengkapi dokumen karier Anda dengan <a href="{{ route('tools.show', 'generator-cv-ats') }}" class="font-semibold underline decoration-amber-300 underline-offset-4">Generator CV ATS</a> agar lamaran terlihat lebih siap kirim.
        </div>
    </div>
</div>
