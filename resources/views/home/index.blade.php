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
@endphp

@section('content')
    <section class="container-shell py-10 sm:py-14 lg:py-18">
        <div class="grid gap-8 lg:grid-cols-[1.1fr,0.9fr] lg:items-center">
            <div>
                <p class="eyebrow">Bantu Kerja Online</p>
                <h1 class="mt-4 max-w-3xl text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
                    Tools dan template gratis untuk kerja, bisnis, dan administrasi harian.
                </h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600 sm:mt-5 sm:text-lg sm:leading-8">
                    Bantukerja.online membantu pengguna menyelesaikan pekerjaan administratif lebih cepat lewat kalkulator praktis, generator dokumen, template siap pakai, dan artikel yang mudah dipahami.
                </p>

                <form action="{{ route('tools.index') }}" method="get" class="mt-6 grid gap-2.5 rounded-[1.75rem] border border-slate-200 bg-white p-2.5 shadow-sm sm:mt-8 sm:flex sm:flex-row sm:gap-3 sm:rounded-3xl sm:p-4">
                    <input
                        type="search"
                        name="search"
                        placeholder="Cari tools seperti THR, invoice, surat izin..."
                        class="tool-input min-w-0 flex-1 rounded-[1.1rem] sm:rounded-2xl"
                    >
                    <button class="inline-flex h-12 items-center justify-center rounded-[1.1rem] bg-slate-900 px-5 text-sm font-semibold text-white hover:bg-blue-700 sm:w-auto sm:rounded-2xl sm:px-6">
                        Cari tool
                    </button>
                </form>

                <div class="-mx-4 mt-6 flex gap-2.5 overflow-x-auto px-4 pb-1 text-sm sm:mx-0 sm:mt-8 sm:flex-wrap sm:overflow-visible sm:px-0 sm:gap-3">
                    <a href="{{ route('tools.show', 'kalkulator-thr') }}" class="shrink-0 rounded-full border border-blue-200 bg-blue-50 px-3.5 py-2 text-blue-700 sm:px-4">Kalkulator THR</a>
                    <a href="{{ route('tools.show', 'generator-invoice') }}" class="shrink-0 rounded-full border border-orange-200 bg-orange-50 px-3.5 py-2 text-orange-700 sm:px-4">Generator Invoice</a>
                    <a href="{{ route('templates.show', 'surat-resign') }}" class="shrink-0 rounded-full border border-slate-200 bg-white px-3.5 py-2 text-slate-700 sm:px-4">Template Surat Resign</a>
                </div>
            </div>
        </div>
    </section>

    <section class="container-shell pb-8">
        <x-ad-slot slot-key="home_top" label="Home Top" />
    </section>

    <section class="container-shell py-10">
        <div class="card-panel p-6 sm:p-7">
            <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-end sm:justify-between sm:gap-4">
                <div>
                    <p class="eyebrow">Trust & usability</p>
                    <h2 class="section-heading mt-3">Kenapa menggunakan Bantu Kerja?</h2>
                    <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                        Bantu Kerja dirancang agar siapa pun bisa membuat dokumen kerja, bisnis, dan administrasi harian secara cepat tanpa proses yang rumit.
                    </p>
                </div>
            </div>

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
        <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-end sm:justify-between sm:gap-4">
            <div>
                <p class="eyebrow">Pilihan populer</p>
                <h2 class="section-heading mt-3">Tools paling siap dipakai</h2>
            </div>
            <a href="{{ route('tools.index') }}" class="text-sm font-medium text-blue-700">Lihat semua tools</a>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($popularTools as $tool)
                @if (filled($tool->slug))
                    <a href="{{ route('tools.show', $tool->slug) }}" class="card-panel p-6 hover:-translate-y-0.5 hover:border-blue-200">
                        <p class="text-sm font-medium text-blue-700">{{ $tool->category?->name }}</p>
                        <h3 class="mt-3 text-xl font-semibold text-slate-900">{{ $tool->title }}</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $tool->short_description }}</p>
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
            </div>
            <a href="{{ route('templates.index') }}" class="text-sm font-medium text-blue-700">Lihat semua template</a>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($popularTemplates as $template)
                @if (filled($template->slug))
                    <a href="{{ route('templates.show', $template->slug) }}" class="card-panel p-6 hover:-translate-y-0.5 hover:border-blue-200">
                        <p class="text-sm font-medium text-orange-600">{{ $template->category?->name }}</p>
                        <h3 class="mt-3 text-xl font-semibold text-slate-900">{{ $template->title }}</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $template->short_description }}</p>
                    </a>
                @endif
            @endforeach
        </div>
    </section>

    <section class="container-shell py-10">
        <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-end sm:justify-between sm:gap-4">
            <div>
                <p class="eyebrow">Artikel terbaru</p>
                <h2 class="section-heading mt-3">Edukasi yang membantu traffic organik</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm font-medium text-blue-700">Lihat semua artikel</a>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($latestPosts as $post)
                @if (filled($post->slug))
                    <a href="{{ route('blog.show', $post->slug) }}" class="card-panel p-6 hover:-translate-y-0.5 hover:border-blue-200">
                        <p class="text-sm font-medium text-slate-500">{{ $post->category?->name }}</p>
                        <h3 class="mt-3 text-xl font-semibold text-slate-900">{{ $post->title }}</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $post->excerpt }}</p>
                    </a>
                @endif
            @endforeach
        </div>
    </section>

    @push('structured-data')
        <script type="application/ld+json">
            {!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush
@endsection
