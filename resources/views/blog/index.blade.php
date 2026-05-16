@extends('layouts.app')

@php
    use App\Support\MediaUrl;
@endphp

@section('content')
    <section class="container-shell py-12">
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Blog', 'url' => route('blog.index')],
        ]" />

        <div class="card-panel p-6">
            <h1 class="section-heading">Artikel Edukatif SEO-Friendly</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">
                Artikel praktis untuk membantu pengguna memahami topik kerja, dokumen, penggajian, invoice, dan administrasi bisnis sehari-hari.
            </p>
            <form method="get" class="mt-6 grid gap-3 md:grid-cols-[1fr,220px,auto]">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari artikel..." class="h-12 rounded-2xl border border-slate-200 px-4 text-sm">
                <select name="category" class="h-12 rounded-2xl border border-slate-200 px-4 text-sm">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Filter</button>
            </form>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            @forelse ($posts as $post)
                @if (filled($post->slug))
                    <a href="{{ route('blog.show', $post->slug) }}" class="card-panel overflow-hidden hover:border-blue-200">
                        @if (MediaUrl::resolve($post->featured_image))
                            <img
                                src="{{ MediaUrl::resolve($post->featured_image) }}"
                                alt="{{ $post->title }}"
                                class="h-56 w-full object-cover"
                                loading="lazy"
                            >
                        @endif
                        <div class="p-6">
                            <p class="text-sm font-medium text-slate-500">{{ $post->category?->name }}</p>
                            <h2 class="mt-3 text-2xl font-semibold text-slate-900">{{ $post->title }}</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-600">{{ $post->excerpt }}</p>
                        </div>
                    </a>
                @endif
            @empty
                <div class="card-panel p-8 text-sm text-slate-600 lg:col-span-2">Belum ada artikel yang cocok dengan pencarian Anda.</div>
            @endforelse
        </div>

        <div class="mt-8">{{ $posts->links() }}</div>
    </section>
@endsection
