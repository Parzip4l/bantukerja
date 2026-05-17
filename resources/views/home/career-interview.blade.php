@extends('layouts.app')

@section('content')
    <section class="container-shell py-10 sm:py-12">
        <div class="rounded-[2rem] border border-slate-200 bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.08),_transparent_28%),linear-gradient(180deg,_#ffffff,_#f8fafc)] p-6 shadow-sm sm:p-8 lg:p-10">
            <span class="inline-flex rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-blue-700">
                Cluster Karier
            </span>
            <h1 class="mt-4 max-w-4xl text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl lg:text-[3.2rem] lg:leading-[1.05]">
                Karier & Interview
            </h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600 sm:text-lg sm:leading-8">
                Kumpulan tools gratis untuk menyiapkan CV ATS, surat lamaran, latihan interview, profil LinkedIn, sampai analisis kecocokan lowongan. Cocok untuk fresh graduate, pelamar aktif, maupun profesional yang ingin persiapan karier terasa lebih rapi dan terarah.
            </p>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('tools.show', 'generator-cv-ats') }}" class="inline-flex h-12 items-center justify-center rounded-2xl bg-slate-900 px-6 text-sm font-semibold text-white hover:bg-blue-700">
                    Mulai dari CV ATS
                </a>
                <a href="{{ route('tools.show', 'simulasi-pertanyaan-interview') }}" class="inline-flex h-12 items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 text-sm font-semibold text-slate-700 hover:border-blue-300 hover:text-blue-700">
                    Latihan Interview
                </a>
            </div>
        </div>
    </section>

    <section class="container-shell py-10">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="eyebrow">Tools utama</p>
                <h2 class="section-heading mt-3">Satu jalur persiapan karier yang lebih rapi</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                    Gunakan urutan ini untuk menyiapkan dokumen, menjawab interview, dan merapikan profil profesional Anda tanpa pindah-pindah terlalu jauh.
                </p>
            </div>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($careerTools as $tool)
                <a
                    href="{{ route('tools.show', $tool->slug) }}"
                    class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:border-blue-200 hover:bg-slate-50"
                    data-analytics-related
                    data-related-name="{{ $tool->slug }}"
                    data-related-section="career_cluster"
                >
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $tool->category?->name }}</p>
                    <h3 class="mt-3 text-lg font-semibold text-slate-900">{{ $tool->title }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($tool->short_description, 110) }}</p>
                    <span class="mt-4 inline-flex text-sm font-semibold text-blue-700">Buka tool</span>
                </a>
            @endforeach
        </div>
    </section>

    @if ($careerTemplates->isNotEmpty())
        <section class="container-shell py-10">
            <div class="card-panel p-6 sm:p-7">
                <p class="eyebrow">Template pendukung</p>
                <h2 class="section-heading mt-3">Dokumen siap pakai untuk lamaran kerja</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @foreach ($careerTemplates as $template)
                        <a href="{{ route('templates.show', $template->slug) }}" class="rounded-3xl border border-slate-200 bg-slate-50 p-5 hover:border-blue-200 hover:bg-white">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $template->title }}</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($template->short_description, 120) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($careerArticles->isNotEmpty())
        <section class="container-shell py-10">
            <div class="card-panel p-6 sm:p-7">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="eyebrow">Panduan</p>
                        <h2 class="section-heading mt-3">Baca tips tambahan seputar CV dan interview</h2>
                    </div>
                    <a href="{{ route('blog.index') }}" class="text-sm font-medium text-blue-700">Lihat semua artikel</a>
                </div>
                <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($careerArticles as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="rounded-3xl border border-slate-200 bg-white p-5 hover:border-blue-200 hover:bg-slate-50">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $post->category?->name ?? 'Artikel' }}</p>
                            <h3 class="mt-3 text-lg font-semibold text-slate-900">{{ $post->title }}</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($post->excerpt, 120) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="container-shell py-10">
        <div class="card-panel p-6 sm:p-7">
            <p class="eyebrow">Cara pakai</p>
            <h2 class="section-heading mt-3">Alur yang disarankan</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['title' => '1. Rapikan CV', 'description' => 'Mulai dari Generator CV ATS atau ATS CV Checker agar isi CV lebih siap dipakai.'],
                    ['title' => '2. Siapkan lamaran', 'description' => 'Gunakan Generator Surat Lamaran untuk membuat draft pengantar yang lebih profesional.'],
                    ['title' => '3. Latihan interview', 'description' => 'Pakai Simulasi Pertanyaan Interview dan STAR Generator agar jawaban lebih runtut.'],
                    ['title' => '4. Perkuat profil online', 'description' => 'Lengkapi personal branding lewat LinkedIn Headline & About Generator.'],
                ] as $step)
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <h3 class="text-lg font-semibold text-slate-900">{{ $step['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $step['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
