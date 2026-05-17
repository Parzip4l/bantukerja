<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="google-adsense-account" content="ca-pub-8003898120453466">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    @production
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-YXVJ08TTEP"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', 'G-YXVJ08TTEP');
        </script>
    @endproduction
    <x-seo :seo="$seo ?? []" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('structured-data')
</head>
<body>
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.10),_transparent_32%),linear-gradient(to_bottom,_#f8fafc,_#ffffff)]">
        <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
            <div class="container-shell flex items-center justify-between gap-4 py-3 sm:py-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img
                        src="{{ asset('images/bantukerja-logo.png') }}"
                        alt="BantuKerja.online"
                        class="h-10 w-auto rounded-2xl object-contain sm:h-12"
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

                <button
                    type="button"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm md:hidden"
                    aria-label="Buka menu"
                    aria-expanded="false"
                    aria-controls="mobile-menu"
                    data-mobile-menu-toggle
                >
                    <span class="sr-only">Toggle menu</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M3 5h14M3 10h14M3 15h14" stroke-linecap="round" />
                    </svg>
                </button>
            </div>

            <div id="mobile-menu" class="hidden border-t border-slate-200/70 bg-white/95 md:hidden" data-mobile-menu-panel>
                <div class="container-shell py-3">
                    <nav class="grid gap-2 text-sm font-medium text-slate-700">
                        <a href="{{ route('tools.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 hover:border-blue-200 hover:text-blue-700">Tools</a>
                        <a href="{{ route('templates.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 hover:border-blue-200 hover:text-blue-700">Template</a>
                        <a href="{{ route('blog.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 hover:border-blue-200 hover:text-blue-700">Blog</a>
                        <a href="{{ route('pages.about') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 hover:border-blue-200 hover:text-blue-700">About</a>
                        <a href="{{ route('pages.contact') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 hover:border-blue-200 hover:text-blue-700">Contact</a>
                    </nav>
                </div>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="mt-20">
            <div class="container-shell py-12 sm:py-16">
                <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.08),_transparent_26%),radial-gradient(circle_at_bottom_right,_rgba(249,115,22,0.08),_transparent_22%),linear-gradient(180deg,_#ffffff,_#f8fafc)] p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)] sm:p-8 lg:p-10">
                    <div class="grid gap-10 lg:grid-cols-[minmax(0,1.25fr),minmax(0,0.8fr),minmax(0,0.8fr)] lg:gap-10">
                        <div>
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-4">
                                <img
                                    src="{{ asset('images/bantukerja-logo.png') }}"
                                    alt="BantuKerja.online"
                                    class="h-12 w-auto rounded-2xl border border-slate-200 bg-white p-1.5 object-contain shadow-sm sm:h-14"
                                    loading="lazy"
                                >
                            </a>
                            <p class="mt-5 max-w-xl text-sm leading-7 text-slate-600 sm:mt-6 sm:leading-8">
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

                    <div class="mt-8 border-t border-slate-200 pt-5 text-sm text-slate-500">
                        <p>&copy; {{ now()->year }} BantuKerja.online. Semua hak cipta dilindungi.</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <x-assistant-widget />

    @if (! empty($adsenseGlobalScript))
        {!! $adsenseGlobalScript !!}
    @endif
</body>
</html>
