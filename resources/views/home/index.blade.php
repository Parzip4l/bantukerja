@extends('layouts.app')

@php
    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'BantuKerja.online',
        'url' => route('home'),
        'description' => 'Tools dan template gratis untuk kerja, bisnis, dan administrasi harian.',
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => route('tools.index').'?search={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];

    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'BantuKerja.online',
        'url' => route('home'),
        'contactPoint' => [[
            '@type' => 'ContactPoint',
            'contactType' => 'customer support',
            'email' => 'hello@bantukerja.online',
            'availableLanguage' => ['id'],
        ]],
    ];

    $quickKeywords = [
        ['label' => 'Invoice', 'url' => route('tools.show', 'generator-invoice')],
        ['label' => 'CV ATS', 'url' => route('tools.show', 'generator-cv-ats')],
        ['label' => 'Kwitansi', 'url' => route('tools.show', 'generator-kwitansi')],
        ['label' => 'THR', 'url' => route('tools.show', 'kalkulator-thr')],
        ['label' => 'Slip Gaji', 'url' => route('tools.index', ['search' => 'slip gaji'])],
        ['label' => 'Surat Lamaran', 'url' => route('tools.show', 'generator-surat-lamaran-kerja')],
        ['label' => 'Template', 'url' => route('templates.index')],
    ];
@endphp

@section('content')
    <section class="container-shell py-8 sm:py-10 lg:py-14">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.05fr),minmax(320px,0.95fr)] lg:items-start">
            <div class="lg:pt-3">
                <span class="inline-flex rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold tracking-[0.18em] text-blue-700 uppercase">
                    Tools & Template Gratis
                </span>
                <h1 class="mt-4 max-w-4xl text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl lg:text-[3.35rem] lg:leading-[1.02]">
                    Buat Dokumen Kerja dan Administrasi Lebih Cepat
                </h1>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600 sm:text-lg sm:leading-8">
                    Gunakan tools dan template gratis untuk membuat invoice, kwitansi, CV ATS, surat kerja, slip gaji, kalkulator THR, dan kebutuhan administrasi harian lainnya. Cocok untuk pekerja, freelancer, UMKM, HR, dan admin kantor yang ingin serba lebih cepat dari browser.
                </p>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <a href="{{ route('tools.index') }}" class="inline-flex h-12 items-center justify-center rounded-2xl bg-slate-900 px-6 text-sm font-semibold text-white hover:bg-blue-700">
                        Mulai Gunakan Tools Gratis
                    </a>
                    <a href="{{ route('templates.index') }}" class="inline-flex h-12 items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 text-sm font-semibold text-slate-700 hover:border-blue-300 hover:text-blue-700">
                        Lihat Template Dokumen
                    </a>
                </div>

                <p class="mt-4 text-sm leading-7 text-slate-500">
                    Tanpa login untuk tools publik • Preview langsung • Bisa copy, print, dan download
                </p>

                <form action="{{ route('tools.index') }}" method="get" class="mt-6 rounded-[1.7rem] border border-slate-200 bg-white p-4 shadow-sm" data-home-search>
                    <label for="home-search-input" class="mb-3 block text-sm font-medium text-slate-700">Cari tools, template, atau artikel</label>
                    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr),180px]">
                        <input
                            id="home-search-input"
                            type="search"
                            name="search"
                            placeholder="Cari tools atau template: invoice, CV ATS, kwitansi, THR, slip gaji..."
                            class="tool-input min-w-0 rounded-2xl"
                            autocomplete="off"
                            data-home-search-input
                        >
                        <button class="inline-flex h-12 items-center justify-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white hover:bg-blue-700">
                            Cari Sekarang
                        </button>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($quickKeywords as $keyword)
                            <a href="{{ $keyword['url'] }}" class="rounded-full border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm text-slate-700 hover:border-blue-300 hover:text-blue-700">
                                {{ $keyword['label'] }}
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-4 hidden rounded-3xl border border-slate-200 bg-slate-50 p-3" data-home-search-results></div>
                </form>

                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    @foreach ($stats as $stat)
                        <div class="rounded-3xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                            <p class="text-2xl font-semibold text-slate-900 sm:text-[1.75rem]">{{ $stat['value'] }}</p>
                            <p class="mt-1 text-sm leading-6 text-slate-500">{{ $stat['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card-panel p-5 sm:p-6">
                <div class="flex flex-col gap-3 border-b border-slate-100 pb-5 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="eyebrow">Mulai cepat</p>
                        <h2 class="mt-3 text-2xl font-semibold text-slate-900">Tools yang paling sering dipakai</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-600">
                            Pilihan ini paling sering jadi titik awal user baru saat butuh dokumen kerja, administrasi, atau transaksi sederhana.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm leading-6 text-blue-900">
                        Input digunakan untuk preview dokumen dan tidak disimpan permanen pada tools publik.
                    </div>
                </div>

                <div class="mt-5 space-y-3">
                    @foreach ($heroHighlights as $highlight)
                        <a href="{{ route('tools.show', $highlight->slug) }}" class="flex items-start gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-4 hover:border-blue-200 hover:bg-white">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white text-sm font-semibold text-slate-900 shadow-sm">
                                {{ $loop->iteration }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $highlight->category?->name }}</p>
                                    <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700">
                                        Gratis
                                    </span>
                                </div>
                                <h3 class="mt-2 text-lg font-semibold text-slate-900">{{ $highlight->title }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($highlight->short_description, 92) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-3xl border border-slate-200 bg-white p-4">
                        <p class="text-sm font-semibold text-slate-900">Tanpa login</p>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Banyak tools publik bisa langsung dipakai dari browser.</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-4">
                        <p class="text-sm font-semibold text-slate-900">Siap copy atau download</p>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Cocok untuk kebutuhan cepat sebelum dicetak atau diedit lagi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container-shell pb-8">
        <x-ad-slot slot-key="home_top" label="Home Top" />
    </section>

    <section class="container-shell py-10">
        <div class="card-panel p-6 sm:p-7">
            <p class="eyebrow">Trust & usability</p>
            <h2 class="section-heading mt-3">Kenapa menggunakan Bantu Kerja?</h2>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                Bantu Kerja dirancang agar siapa pun bisa membuat dokumen kerja, bisnis, dan administrasi harian secara cepat tanpa proses yang rumit.
            </p>

            <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ([
                    ['title' => 'Gratis digunakan', 'description' => 'Gunakan berbagai tools dasar untuk membuat dokumen dan kalkulasi kerja tanpa biaya.'],
                    ['title' => 'Tanpa login untuk tools publik', 'description' => 'Banyak tools bisa langsung digunakan dari browser tanpa membuat akun terlebih dahulu.'],
                    ['title' => 'Preview langsung', 'description' => 'Lihat hasil dokumen secara langsung sebelum disalin, dicetak, atau diunduh.'],
                    ['title' => 'Bisa copy, print, dan download', 'description' => 'Hasil dokumen dapat digunakan kembali sesuai kebutuhan, baik untuk kerja, bisnis, maupun administrasi harian.'],
                    ['title' => 'Fokus kebutuhan Indonesia', 'description' => 'Template dan tools dibuat dengan konteks penggunaan masyarakat Indonesia, seperti invoice, kwitansi, CV, surat kerja, dan kebutuhan HR sederhana.'],
                    ['title' => 'Data tidak disimpan permanen', 'description' => 'Input pada tools publik tidak disimpan permanen kecuali ada fitur khusus yang meminta persetujuan pengguna.'],
                ] as $point)
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <h3 class="text-lg font-semibold text-slate-900">{{ $point['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $point['description'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex flex-col items-start gap-4 rounded-3xl border border-blue-100 bg-blue-50 p-5 sm:flex-row sm:items-center sm:justify-between">
                <p class="max-w-2xl text-sm leading-7 text-blue-900">
                    Mulai gunakan tools gratis Bantu Kerja untuk menyelesaikan kebutuhan administrasi harian dengan lebih cepat.
                </p>
                <a href="{{ route('tools.index') }}" class="inline-flex h-11 items-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white hover:bg-blue-700">
                    Lihat Semua Tools
                </a>
            </div>
        </div>
    </section>

    <section class="container-shell py-10">
        <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-end sm:justify-between sm:gap-4">
            <div>
                <p class="eyebrow">Persona</p>
                <h2 class="section-heading mt-3">Pilih tools sesuai kebutuhan Anda</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                    Temukan tools yang paling relevan berdasarkan pekerjaan, bisnis, atau administrasi yang sedang Anda kerjakan.
                </p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2">
            @foreach ($personaSections as $section)
                <div class="card-panel p-6">
                    <h3 class="text-xl font-semibold text-slate-900">{{ $section['title'] }}</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $section['description'] }}</p>
                    <div class="mt-5 flex flex-wrap gap-2.5">
                        @foreach ($section['items'] as $item)
                            <a href="{{ $item['url'] }}" class="rounded-full border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm text-slate-700 hover:border-blue-300 hover:text-blue-700">
                                {{ $item['title'] }}
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ $section['cta_url'] }}" class="mt-6 inline-flex text-sm font-semibold text-blue-700">
                        {{ $section['cta_label'] }}
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section class="container-shell py-10">
        <div class="grid gap-4 sm:gap-6 lg:grid-cols-2">
            <div class="card-panel p-6">
                <p class="eyebrow">Kategori tools</p>
                <h2 class="section-heading mt-3">Alat bantu yang sering dicari</h2>
                <div class="mt-5 flex flex-wrap gap-2.5 sm:mt-6 sm:gap-3">
                    @foreach ($toolCategories as $category)
                        <a href="{{ route('tools.index', ['category' => $category->slug]) }}" class="rounded-full border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm text-slate-700 hover:border-blue-300 hover:text-blue-700 sm:px-4">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="card-panel p-6">
                <p class="eyebrow">Kategori template</p>
                <h2 class="section-heading mt-3">Dokumen yang siap dipakai</h2>
                <div class="mt-5 flex flex-wrap gap-2.5 sm:mt-6 sm:gap-3">
                    @foreach ($templateCategories as $category)
                        <a href="{{ route('templates.index', ['category' => $category->slug]) }}" class="rounded-full border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm text-slate-700 hover:border-blue-300 hover:text-blue-700 sm:px-4">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="container-shell py-10">
        <div class="card-panel p-6 sm:p-7">
            <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-end sm:justify-between sm:gap-4">
                <div>
                    <p class="eyebrow">Cara pakai</p>
                    <h2 class="section-heading mt-3">Cara menggunakan Bantu Kerja</h2>
                    <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                        Cukup pilih tools, isi data yang dibutuhkan, lalu gunakan hasilnya sesuai kebutuhan.
                    </p>
                </div>
            </div>

            <div class="mt-8 grid gap-4 lg:grid-cols-4">
                @foreach ([
                    ['step' => '1', 'title' => 'Pilih tools atau template', 'description' => 'Cari tools yang sesuai, seperti invoice, CV, kwitansi, THR, slip gaji, atau dokumen kerja lainnya.'],
                    ['step' => '2', 'title' => 'Isi form sederhana', 'description' => 'Masukkan data yang diperlukan. Untuk tools publik, data hanya digunakan untuk membuat preview dokumen.'],
                    ['step' => '3', 'title' => 'Preview hasil', 'description' => 'Lihat hasil dokumen secara langsung sebelum digunakan.'],
                    ['step' => '4', 'title' => 'Copy, print, atau download', 'description' => 'Gunakan hasilnya untuk kebutuhan kerja, bisnis, atau administrasi harian.'],
                ] as $step)
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">{{ $step['step'] }}</span>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ $step['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $step['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="container-shell py-10">
        <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-end sm:justify-between sm:gap-4">
            <div>
                <p class="eyebrow">Pilihan populer</p>
                <h2 class="section-heading mt-3">Tools populer yang paling sering dipakai</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                    Mulai dari invoice dan kwitansi sampai CV ATS, THR, SOP, dan job description untuk kebutuhan kerja harian.
                </p>
            </div>
            <a href="{{ route('tools.index') }}" class="text-sm font-medium text-blue-700">Lihat semua tools</a>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($popularTools->take(6) as $tool)
                @if (filled($tool->slug))
                    <a href="{{ route('tools.show', $tool->slug) }}" class="card-panel flex h-full flex-col p-5 hover:-translate-y-0.5 hover:border-blue-200">
                        <div class="flex items-start justify-between gap-4">
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $tool->category?->name }}</span>
                            <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[11px] font-semibold text-emerald-700">Gratis</span>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ $tool->title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($tool->short_description, 105) }}</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-semibold text-slate-600">Tanpa login</span>
                            <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-semibold text-slate-600">Preview</span>
                            <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-semibold text-slate-600">Bisa download</span>
                        </div>
                        <span class="mt-5 inline-flex text-sm font-semibold text-blue-700">Gunakan Tools</span>
                    </a>
                @endif
            @endforeach
        </div>
    </section>

    <section class="container-shell py-6">
        <x-ad-slot slot-key="home_middle" label="Home Middle" />
    </section>

    <section class="container-shell py-10">
        <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-end sm:justify-between sm:gap-4">
            <div>
                <p class="eyebrow">Template unggulan</p>
                <h2 class="section-heading mt-3">Dokumen gratis yang sering dibutuhkan</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                    Gunakan template dokumen siap edit untuk kebutuhan surat kerja, karier, dan administrasi bisnis ringan.
                </p>
            </div>
            <a href="{{ route('templates.index') }}" class="text-sm font-medium text-blue-700">Lihat semua template</a>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($popularTemplates as $template)
                @if (filled($template->slug))
                    <a href="{{ route('templates.show', $template->slug) }}" class="card-panel p-5 hover:-translate-y-0.5 hover:border-blue-200">
                        <p class="text-sm font-medium text-orange-600">{{ $template->category?->name }}</p>
                        <h3 class="mt-3 text-lg font-semibold text-slate-900">{{ $template->title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($template->short_description, 100) }}</p>
                    </a>
                @endif
            @endforeach
        </div>
    </section>

    <section class="container-shell py-10">
        <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-end sm:justify-between sm:gap-4">
            <div>
                <p class="eyebrow">Artikel panduan</p>
                <h2 class="section-heading mt-3">Panduan praktis seputar kerja dan administrasi</h2>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                    Baca tips sederhana untuk membuat dokumen kerja, administrasi bisnis, HR, dan kebutuhan profesional lainnya.
                </p>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm font-medium text-blue-700">Lihat semua artikel</a>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($latestPosts as $post)
                @if (filled($post->slug))
                    <a href="{{ route('blog.show', $post->slug) }}" class="card-panel p-5 hover:-translate-y-0.5 hover:border-blue-200">
                        <p class="text-sm font-medium text-slate-500">{{ $post->category?->name }}</p>
                        <h3 class="mt-3 text-lg font-semibold text-slate-900">{{ $post->title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($post->excerpt, 110) }}</p>
                        <span class="mt-4 inline-flex text-sm font-semibold text-blue-700">Baca panduan</span>
                    </a>
                @endif
            @endforeach
        </div>
    </section>

    <section class="container-shell pb-12 pt-6">
        <div class="card-panel p-7">
            <p class="eyebrow">Konsultasi</p>
            <h2 class="section-heading mt-3">Butuh sistem digital untuk bisnis atau perusahaan?</h2>
            <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                Selain menyediakan tools gratis, Bantu Kerja juga dapat menjadi pintu awal untuk konsultasi kebutuhan aplikasi bisnis seperti sistem invoice, HRIS, ticketing, approval, dashboard, dan administrasi digital lainnya.
            </p>
            <a href="{{ route('pages.contact') }}" class="mt-6 inline-flex h-11 items-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white hover:bg-blue-700">
                Konsultasi Kebutuhan
            </a>
        </div>
    </section>

    <script type="application/json" id="home-search-index">@json($searchIndex, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>

    @push('structured-data')
        <script type="application/ld+json">
            {!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
        <script type="application/ld+json">
            {!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush
@endsection
