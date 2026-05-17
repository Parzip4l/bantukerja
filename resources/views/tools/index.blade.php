@extends('layouts.app')

@section('content')
    <section class="container-shell py-12">
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Tools', 'url' => route('tools.index')],
        ]" />

        <div class="card-panel p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h1 class="section-heading">Semua Tools Gratis</h1>
                    <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">
                        Gunakan tools publik yang ringan, cepat, dan praktis untuk menghitung, membuat dokumen, atau menyederhanakan pekerjaan administratif harian.
                    </p>
                </div>
                <div class="grid max-w-sm grid-cols-2 gap-3 sm:max-w-md">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <p class="text-2xl font-semibold text-slate-900">{{ number_format($tools->total()) }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ request()->filled('search') || request()->filled('category') ? 'Hasil ditemukan' : 'Total tools aktif' }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <p class="text-base font-semibold text-slate-900">Tanpa login</p>
                        <p class="mt-1 text-sm text-slate-500">Banyak tools bisa langsung digunakan.</p>
                    </div>
                </div>
            </div>

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
                @if (filled($tool->slug))
                    <a href="{{ route('tools.show', $tool->slug) }}" class="card-panel flex h-full flex-col p-5 hover:border-blue-200">
                        <div class="flex items-start justify-between gap-3">
                            <p class="text-sm font-medium text-blue-700">{{ $tool->category?->name }}</p>
                            <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[11px] font-semibold text-emerald-700">Gratis</span>
                        </div>
                        <h2 class="mt-3 text-lg font-semibold text-slate-900">{{ $tool->title }}</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($tool->short_description, 110) }}</p>
                        <span class="mt-4 inline-flex text-sm font-semibold text-blue-700">Gunakan tools</span>
                    </a>
                @endif
            @empty
                <div class="card-panel p-8 text-sm text-slate-600 md:col-span-2 xl:col-span-3">Belum ada tool yang cocok dengan filter Anda.</div>
            @endforelse
        </div>

        <div class="mt-8">{{ $tools->links() }}</div>
    </section>
@endsection
