@extends('layouts.app')

@section('content')
    <section class="container-shell py-12">
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Tools', 'url' => route('tools.index')],
        ]" />

        <div class="card-panel p-6">
            <h1 class="section-heading">Semua Tools Gratis</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">
                Gunakan tools publik yang ringan, cepat, dan praktis untuk menghitung, membuat dokumen, atau menyederhanakan pekerjaan administratif harian.
            </p>

            <form method="get" class="mt-6 grid gap-3 md:grid-cols-[1fr,220px,auto]">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari tools..." class="h-12 rounded-2xl border border-slate-200 px-4 text-sm">
                <select name="category" class="h-12 rounded-2xl border border-slate-200 px-4 text-sm">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <button class="h-12 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Filter</button>
            </form>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($tools as $tool)
                <a href="{{ route('tools.show', $tool->slug) }}" class="card-panel p-6 hover:border-blue-200">
                    <p class="text-sm font-medium text-blue-700">{{ $tool->category?->name }}</p>
                    <h2 class="mt-3 text-xl font-semibold text-slate-900">{{ $tool->title }}</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $tool->short_description }}</p>
                </a>
            @empty
                <div class="card-panel p-8 text-sm text-slate-600 md:col-span-2 xl:col-span-3">Belum ada tool yang cocok dengan filter Anda.</div>
            @endforelse
        </div>

        <div class="mt-8">{{ $tools->links() }}</div>
    </section>
@endsection
