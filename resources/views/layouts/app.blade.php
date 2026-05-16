<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <x-seo :seo="$seo ?? []" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('structured-data')
</head>
<body>
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.10),_transparent_32%),linear-gradient(to_bottom,_#f8fafc,_#ffffff)]">
        <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
            <div class="container-shell flex items-center justify-between gap-4 py-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img
                        src="{{ asset('images/bantukerja-logo.png') }}"
                        alt="BantuKerja.online"
                        class="h-12 w-auto rounded-2xl object-contain"
                        loading="eager"
                    >
                </a>

                <nav class="hidden items-center gap-6 text-sm font-medium text-slate-600 md:flex">
                    <a href="{{ route('tools.index') }}" class="hover:text-blue-700">Tools</a>
                    <a href="{{ route('templates.index') }}" class="hover:text-blue-700">Template</a>
                    <a href="{{ route('blog.index') }}" class="hover:text-blue-700">Blog</a>
                    <a href="{{ route('pages.about') }}" class="hover:text-blue-700">About</a>
                    <a href="{{ route('pages.contact') }}" class="hover:text-blue-700">Contact</a>
                </nav>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="mt-20">
            <div class="container-shell py-14 sm:py-16">
                <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.08),_transparent_26%),radial-gradient(circle_at_bottom_right,_rgba(249,115,22,0.08),_transparent_22%),linear-gradient(180deg,_#ffffff,_#f8fafc)] p-8 shadow-[0_24px_80px_rgba(15,23,42,0.08)] lg:p-10">
                    <div class="grid gap-12 lg:grid-cols-[minmax(0,1.25fr),minmax(0,0.8fr),minmax(0,0.8fr)] lg:gap-10">
                        <div>
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-4">
                                <img
                                    src="{{ asset('images/bantukerja-logo.png') }}"
                                    alt="BantuKerja.online"
                                    class="h-14 w-auto rounded-2xl border border-slate-200 bg-white p-1.5 object-contain shadow-sm"
                                    loading="lazy"
                                >
                            </a>
                            <p class="mt-6 max-w-xl text-sm leading-8 text-slate-600">
                                Platform utilitas publik untuk membantu kerja, bisnis, dan administrasi harian terasa lebih cepat, lebih rapi, dan lebih mudah dikelola.
                            </p>
                        </div>

                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Explore</h3>
                            <div class="mt-5 space-y-3 text-sm">
                                <a href="{{ route('tools.index') }}" class="block text-slate-700 hover:text-blue-700">Tools Gratis</a>
                                <a href="{{ route('templates.index') }}" class="block text-slate-700 hover:text-blue-700">Template Dokumen</a>
                                <a href="{{ route('blog.index') }}" class="block text-slate-700 hover:text-blue-700">Artikel Blog</a>
                                <a href="{{ route('pages.about') }}" class="block text-slate-700 hover:text-blue-700">Tentang Kami</a>
                                <a href="{{ route('pages.contact') }}" class="block text-slate-700 hover:text-blue-700">Kontak</a>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Legal</h3>
                            <div class="mt-5 space-y-3 text-sm">
                                <a href="{{ route('pages.privacy-policy') }}" class="block text-slate-700 hover:text-blue-700">Privacy Policy</a>
                                <a href="{{ route('pages.terms') }}" class="block text-slate-700 hover:text-blue-700">Terms</a>
                                <a href="{{ route('pages.disclaimer') }}" class="block text-slate-700 hover:text-blue-700">Disclaimer</a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex flex-col gap-4 border-t border-slate-200 pt-4 mt-5 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                        <p class="mt-4">&copy; {{ now()->year }} BantuKerja.online. Semua hak cipta dilindungi.</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @if (! empty($adsenseGlobalScript))
        {!! $adsenseGlobalScript !!}
    @endif
</body>
</html>
