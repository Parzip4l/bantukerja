@extends('layouts.app')

@section('content')
    <section class="container-shell py-12">
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $page->title, 'url' => url()->current()],
        ]" />

        <article class="card-panel p-7">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ $page->title }}</h1>
            <div class="prose-content mt-6">
                {!! $page->content !!}
            </div>
        </article>

        @if (! empty($showContactForm))
            <section id="contact-form" class="mt-8 card-panel p-7">
                <h2 class="text-2xl font-semibold text-slate-900">Kirim pesan</h2>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                    Gunakan form ini untuk pertanyaan umum, masukan konten, atau usulan kerja sama. Form dibatasi untuk mencegah spam.
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
                        <input type="text" name="name" value="{{ old('name') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Subjek</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="h-12 w-full rounded-2xl border border-slate-200 px-4">
                    </div>
                    <div class="hidden">
                        <label>Website</label>
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Pesan</label>
                        <textarea name="message" rows="6" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('message') }}</textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Kirim pesan</button>
                    </div>
                </form>
            </section>
        @endif
    </section>
@endsection
