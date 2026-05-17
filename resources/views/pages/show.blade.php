@extends('layouts.app')

@section('content')
    <section class="container-shell py-12">
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $page->title, 'url' => url()->current()],
        ]" />

        @if ($page->slug === 'contact')
            <div class="grid gap-8 lg:grid-cols-[1.05fr,0.95fr]">
                <div>
                    <article class="card-panel p-7">
                        <p class="eyebrow">Kontak</p>
                        <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Hubungi Bantu Kerja</h1>
                        <p class="mt-4 max-w-3xl text-base leading-8 text-slate-600">
                            Kami terbuka untuk masukan, kerja sama, pelaporan masalah, koreksi konten, atau permintaan fitur baru untuk tools dan template Bantu Kerja.
                        </p>
                    </article>

                    <section class="mt-8 card-panel p-7">
                        <h2 class="text-2xl font-semibold text-slate-900">Kontak utama</h2>
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Email utama</p>
                                <a href="mailto:hello@bantukerja.online" class="mt-3 block text-lg font-semibold text-slate-900 hover:text-blue-700">hello@bantukerja.online</a>
                            </div>
                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Email support</p>
                                <a href="mailto:support@bantukerja.online" class="mt-3 block text-lg font-semibold text-slate-900 hover:text-blue-700">support@bantukerja.online</a>
                            </div>
                        </div>

                        <div class="mt-6 rounded-3xl border border-slate-200 p-6">
                            <h3 class="text-lg font-semibold text-slate-900">Keperluan yang bisa dihubungi</h3>
                            <ul class="mt-4 space-y-3 text-sm leading-7 text-slate-600">
                                <li>Kerja sama konten atau sponsorship</li>
                                <li>Pelaporan bug pada tools</li>
                                <li>Koreksi artikel atau template</li>
                                <li>Permintaan fitur baru</li>
                                <li>Konsultasi kebutuhan aplikasi bisnis, HR, invoice, atau administrasi digital</li>
                            </ul>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-slate-200 p-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Estimasi waktu respons</p>
                                <p class="mt-3 text-base font-semibold text-slate-900">1-3 hari kerja</p>
                            </div>
                            <div class="rounded-3xl border border-amber-100 bg-amber-50 p-5">
                                <p class="text-sm leading-7 text-amber-900">Bantu Kerja tidak meminta password, data rekening, atau informasi sensitif lain melalui halaman kontak ini.</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-3xl border border-blue-100 bg-blue-50 p-5 text-sm leading-7 text-blue-900">
                            Punya ide tools baru? Ceritakan kebutuhan Anda, kami akan mempertimbangkannya untuk pengembangan berikutnya.
                        </div>
                    </section>
                </div>

                <div>
                    @if (! empty($showContactForm))
                        <section id="contact-form" class="card-panel p-7 lg:sticky lg:top-24">
                            <h2 class="text-2xl font-semibold text-slate-900">Kirim Pesan</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                Gunakan form ini untuk masukan, pelaporan bug, koreksi konten, atau kebutuhan kerja sama. Semakin jelas pesan Anda, semakin cepat tim kami memahaminya.
                            </p>

                            @if (session('contact_success'))
                                <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                                    {{ session('contact_success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                                    <ul class="list-disc pl-5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('contact.store') }}" method="post" class="mt-6 grid gap-4 sm:grid-cols-2">
                                @csrf
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Nama</label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Nama lengkap Anda">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="nama@email.com">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Subjek</label>
                                    <input type="text" name="subject" value="{{ old('subject') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4" placeholder="Contoh: Pelaporan bug generator invoice">
                                </div>
                                <div class="hidden">
                                    <label>Website</label>
                                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="mb-2 block text-sm font-medium text-slate-700">Pesan</label>
                                    <textarea name="message" rows="7" class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Tulis kebutuhan, kendala, atau ide Anda secara singkat namun jelas.">{{ old('message') }}</textarea>
                                </div>
                                <div class="sm:col-span-2">
                                    <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Kirim Pesan</button>
                                </div>
                            </form>
                        </section>
                    @endif
                </div>
            </div>
        @else
            <article class="card-panel p-7">
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ $page->title }}</h1>
                <div class="prose-content mt-6">
                    {!! $page->content !!}
                </div>
            </article>
        @endif
    </section>
@endsection
