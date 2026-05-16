@extends('layouts.app')

@section('content')
    <section class="container-shell py-12">
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Template', 'url' => route('templates.index')],
            ['label' => $template->title, 'url' => route('templates.show', $template->slug)],
        ]" />

        <div class="grid gap-8 lg:grid-cols-[1.2fr,0.8fr]">
            <div>
                <div class="card-panel p-7">
                    <p class="eyebrow">{{ $template->category?->name }}</p>
                    <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ $template->title }}</h1>
                    <p class="mt-4 text-base leading-8 text-slate-600">{{ $template->short_description }}</p>
                </div>

                <div class="mt-6">
                    <x-ad-slot slot-key="template_top" label="Template Top" />
                </div>

                <div class="mt-6 card-panel p-7">
                    <div class="flex flex-wrap gap-3">
                        <button type="button" data-copy-target="#template-content" class="h-11 rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white">Copy template</button>
                        <a href="{{ route('templates.download-word', $template->slug) }}" class="inline-flex h-11 items-center rounded-2xl bg-blue-700 px-5 text-sm font-semibold text-white">Download Word</a>
                        <a href="{{ route('templates.download', $template->slug) }}" class="inline-flex h-11 items-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700">Download TXT</a>
                    </div>
                    <div id="template-content" class="prose-content mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        {!! $template->content !!}
                    </div>
                </div>

                <div class="mt-6">
                    <x-ad-slot slot-key="template_middle" label="Template Middle" />
                </div>

                @if ($template->faqs->count())
                    <section class="mt-6 card-panel p-7">
                        <h2 class="text-2xl font-semibold text-slate-900">FAQ</h2>
                        <div class="mt-5 space-y-4">
                            @foreach ($template->faqs as $faq)
                                <div class="rounded-2xl border border-slate-200 p-5">
                                    <h3 class="text-base font-semibold text-slate-900">{{ $faq->question }}</h3>
                                    <p class="mt-2 text-sm leading-7 text-slate-600">{{ $faq->answer }}</p>
                                </div>
                            @endforeach
                        </div>
                        <x-faq-schema :faqs="$template->faqs" />
                    </section>
                @endif
            </div>

            <aside class="space-y-6">
                <div class="card-panel p-6">
                    <h2 class="text-lg font-semibold text-slate-900">Artikel terkait</h2>
                    <div class="mt-4 space-y-4">
                        @foreach ($relatedPosts as $post)
                            <a href="{{ route('blog.show', $post->slug) }}" class="block text-sm leading-6 text-slate-700 hover:text-blue-700">{{ $post->title }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="card-panel p-6">
                    <h2 class="text-lg font-semibold text-slate-900">Tools terkait</h2>
                    <div class="mt-4 space-y-4">
                        @foreach ($relatedTools as $tool)
                            <a href="{{ route('tools.show', $tool->slug) }}" class="block text-sm leading-6 text-slate-700 hover:text-blue-700">{{ $tool->title }}</a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>

        <div class="mt-8">
            <x-ad-slot slot-key="template_bottom" label="Template Bottom" />
        </div>
    </section>
@endsection
