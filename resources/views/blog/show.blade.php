@extends('layouts.app')

@php
    use App\Support\MediaUrl;

    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $post->title,
        'description' => $post->meta_description,
        'datePublished' => optional($post->published_at)->toIso8601String(),
        'dateModified' => optional($post->updated_at)->toIso8601String(),
        'mainEntityOfPage' => route('blog.show', $post->slug),
        'image' => MediaUrl::resolve($post->featured_image),
        'author' => ['@type' => 'Organization', 'name' => 'BantuKerja.online'],
        'publisher' => ['@type' => 'Organization', 'name' => 'BantuKerja.online'],
    ];

    $faqSchema = $post->faqs->isNotEmpty() ? [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $post->faqs->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq->question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq->answer,
            ],
        ])->all(),
    ] : null;
@endphp

@section('content')
    <section class="container-shell py-12">
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Blog', 'url' => route('blog.index')],
            ['label' => $post->title, 'url' => route('blog.show', $post->slug)],
        ]" />

        <div class="grid gap-8 lg:grid-cols-[1.15fr,0.85fr]">
            <article>
                <div class="card-panel p-7">
                    <p class="eyebrow">{{ $post->category?->name }}</p>
                    <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ $post->title }}</h1>
                    <p class="mt-4 text-base leading-8 text-slate-600">{{ $post->excerpt }}</p>
                </div>

                @if (MediaUrl::resolve($post->featured_image))
                    <div class="mt-6 overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
                        <img
                            src="{{ MediaUrl::resolve($post->featured_image) }}"
                            alt="{{ $post->title }}"
                            class="h-auto w-full object-cover"
                            loading="lazy"
                        >
                    </div>
                @endif

                <div class="mt-6">
                    <x-ad-slot slot-key="article_top" label="Article Top" />
                </div>

                <div class="prose-content mt-6 card-panel p-7">
                    {!! $postContent !!}
                </div>

                <div class="mt-6">
                    <x-ad-slot slot-key="article_middle" label="Article Middle" />
                </div>

                @if ($post->faqs->isNotEmpty())
                    <section class="mt-6 card-panel p-7">
                        <h2 class="text-2xl font-semibold text-slate-900">FAQ</h2>
                        <div class="mt-5 space-y-4">
                            @foreach ($post->faqs as $faq)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5">
                                    <h3 class="text-base font-semibold text-slate-900">{{ $faq->question }}</h3>
                                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $faq->answer }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section class="mt-6 card-panel p-7">
                    <h2 class="text-2xl font-semibold text-slate-900">Artikel terkait</h2>
                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        @foreach ($relatedPosts as $relatedPost)
                            @if (filled($relatedPost->slug))
                                <a href="{{ route('blog.show', $relatedPost->slug) }}" class="rounded-2xl border border-slate-200 p-5 text-sm leading-7 text-slate-700 hover:border-blue-200">{{ $relatedPost->title }}</a>
                            @endif
                        @endforeach
                    </div>
                </section>
            </article>

            <aside class="space-y-6">
                @if (count($toc))
                    <div class="card-panel p-6">
                        <h2 class="text-lg font-semibold text-slate-900">Daftar isi</h2>
                        <div class="mt-4 space-y-3 text-sm">
                            @foreach ($toc as $item)
                                <a href="#{{ $item['id'] }}" class="block {{ $item['level'] === 'h3' ? 'pl-4' : '' }} text-slate-600 hover:text-blue-700">{{ $item['text'] }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="card-panel p-6">
                    <h2 class="text-lg font-semibold text-slate-900">Tools terkait</h2>
                    <div class="mt-4 space-y-4">
                        @foreach ($relatedTools as $tool)
                            @if (filled($tool->slug))
                                <a href="{{ route('tools.show', $tool->slug) }}" class="block text-sm leading-6 text-slate-700 hover:text-blue-700">{{ $tool->title }}</a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="card-panel p-6">
                    <h2 class="text-lg font-semibold text-slate-900">Template terkait</h2>
                    <div class="mt-4 space-y-4">
                        @foreach ($relatedTemplates as $template)
                            @if (filled($template->slug))
                                <a href="{{ route('templates.show', $template->slug) }}" class="block text-sm leading-6 text-slate-700 hover:text-blue-700">{{ $template->title }}</a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <x-ad-slot slot-key="sidebar" label="Sidebar" />
            </aside>
        </div>

        <div class="mt-8">
            <x-ad-slot slot-key="article_bottom" label="Article Bottom" />
        </div>

        @push('structured-data')
            <script type="application/ld+json">
                {!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
            </script>
            @if ($faqSchema)
                <script type="application/ld+json">
                    {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
                </script>
            @endif
        @endpush
    </section>
@endsection
