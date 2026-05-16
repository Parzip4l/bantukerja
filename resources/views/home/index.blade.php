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
    <section class="container-shell py-14 sm:py-18">
        <div class="grid gap-10 lg:grid-cols-[1.1fr,0.9fr] lg:items-center">
            <div>
                <p class="eyebrow">Bantu Kerja Online</p>
                <h1 class="mt-4 max-w-3xl text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">
                    Tools dan template gratis untuk kerja, bisnis, dan administrasi harian.
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-600">
                    Bantukerja.online membantu pengguna menyelesaikan pekerjaan administratif lebih cepat lewat kalkulator praktis, generator dokumen, template siap pakai, dan artikel yang mudah dipahami.
                </p>

                <form action="{{ route('tools.index') }}" method="get" class="mt-8 flex flex-col gap-3 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row">
                    <input
                        type="search"
                        name="search"
                        placeholder="Cari tools seperti THR, invoice, surat izin..."
                        class="h-12 flex-1 rounded-2xl border border-slate-200 px-4 text-sm text-slate-700 outline-none ring-0 placeholder:text-slate-400 focus:border-blue-400"
                    >
                    <button class="h-12 rounded-2xl bg-slate-900 px-6 text-sm font-semibold text-white hover:bg-blue-700">
                        Cari tool
                    </button>
                </form>

                <div class="mt-8 flex flex-wrap gap-3 text-sm">
                    <a href="{{ route('tools.show', 'kalkulator-thr') }}" class="rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-blue-700">Kalkulator THR</a>
                    <a href="{{ route('tools.show', 'generator-invoice') }}" class="rounded-full border border-orange-200 bg-orange-50 px-4 py-2 text-orange-700">Generator Invoice</a>
                    <a href="{{ route('templates.show', 'surat-resign') }}" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-slate-700">Template Surat Resign</a>
                </div>
            </div>

            
        </div>
    </section>

    <section class="container-shell pb-8">
        <x-ad-slot slot-key="home_top" label="Home Top" />
    </section>

    <section class="container-shell py-10">
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="card-panel p-6">
                <p class="eyebrow">Kategori tools</p>
                <h2 class="section-heading mt-3">Alat bantu yang sering dicari</h2>
                <div class="mt-6 flex flex-wrap gap-3">
                    @foreach ($toolCategories as $category)
                        <a href="{{ route('tools.index', ['category' => $category->slug]) }}" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 hover:border-blue-300 hover:text-blue-700">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="card-panel p-6">
                <p class="eyebrow">Kategori template</p>
                <h2 class="section-heading mt-3">Dokumen yang siap dipakai</h2>
                <div class="mt-6 flex flex-wrap gap-3">
                    @foreach ($templateCategories as $category)
                        <a href="{{ route('templates.index', ['category' => $category->slug]) }}" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 hover:border-blue-300 hover:text-blue-700">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="container-shell py-10">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="eyebrow">Pilihan populer</p>
                <h2 class="section-heading mt-3">Tools paling siap dipakai</h2>
            </div>
            <a href="{{ route('tools.index') }}" class="text-sm font-medium text-blue-700">Lihat semua tools</a>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($popularTools as $tool)
                <a href="{{ route('tools.show', $tool->slug) }}" class="card-panel p-6 hover:-translate-y-0.5 hover:border-blue-200">
                    <p class="text-sm font-medium text-blue-700">{{ $tool->category?->name }}</p>
                    <h3 class="mt-3 text-xl font-semibold text-slate-900">{{ $tool->title }}</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $tool->short_description }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="container-shell py-6">
        <x-ad-slot slot-key="home_middle" label="Home Middle" />
    </section>

    <section class="container-shell py-10">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="eyebrow">Template unggulan</p>
                <h2 class="section-heading mt-3">Dokumen gratis yang sering dibutuhkan</h2>
            </div>
            <a href="{{ route('templates.index') }}" class="text-sm font-medium text-blue-700">Lihat semua template</a>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($popularTemplates as $template)
                <a href="{{ route('templates.show', $template->slug) }}" class="card-panel p-6 hover:-translate-y-0.5 hover:border-blue-200">
                    <p class="text-sm font-medium text-orange-600">{{ $template->category?->name }}</p>
                    <h3 class="mt-3 text-xl font-semibold text-slate-900">{{ $template->title }}</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $template->short_description }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="container-shell py-10">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="eyebrow">Artikel terbaru</p>
                <h2 class="section-heading mt-3">Edukasi yang membantu traffic organik</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm font-medium text-blue-700">Lihat semua artikel</a>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($latestPosts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="card-panel p-6 hover:-translate-y-0.5 hover:border-blue-200">
                    <p class="text-sm font-medium text-slate-500">{{ $post->category?->name }}</p>
                    <h3 class="mt-3 text-xl font-semibold text-slate-900">{{ $post->title }}</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $post->excerpt }}</p>
                </a>
            @endforeach
        </div>
    </section>

    @push('structured-data')
        <script type="application/ld+json">
            {!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush
@endsection
