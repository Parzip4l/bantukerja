<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tool;

class SitemapService
{
    protected function pageUrl(Page $page): string
    {
        if (! filled($page->slug)) {
            return route('home');
        }

        return match ($page->slug) {
            'about' => route('pages.about'),
            'contact' => route('pages.contact'),
            'privacy-policy' => route('pages.privacy-policy'),
            'terms' => route('pages.terms'),
            'disclaimer' => route('pages.disclaimer'),
            default => route('pages.show', $page->slug),
        };
    }

    public function build(): string
    {
        $urls = collect([
            [
                'loc' => route('home'),
                'lastmod' => now()->toDateString(),
                'priority' => '1.0',
            ],
            [
                'loc' => route('tools.index'),
                'lastmod' => now()->toDateString(),
                'priority' => '0.9',
            ],
            [
                'loc' => route('templates.index'),
                'lastmod' => now()->toDateString(),
                'priority' => '0.9',
            ],
            [
                'loc' => route('blog.index'),
                'lastmod' => now()->toDateString(),
                'priority' => '0.9',
            ],
        ])
            ->merge(Tool::published()->get()->map(fn (Tool $tool) => [
                'loc' => route('tools.show', $tool->slug),
                'lastmod' => optional($tool->updated_at)->toDateString(),
                'priority' => '0.8',
            ]))
            ->merge(DocumentTemplate::published()->get()->map(fn (DocumentTemplate $template) => [
                'loc' => route('templates.show', $template->slug),
                'lastmod' => optional($template->updated_at)->toDateString(),
                'priority' => '0.8',
            ]))
            ->merge(Post::published()->get()->map(fn (Post $post) => [
                'loc' => route('blog.show', $post->slug),
                'lastmod' => optional($post->updated_at)->toDateString(),
                'priority' => '0.8',
            ]))
            ->merge(Page::published()->get()->map(fn (Page $page) => [
                'loc' => $this->pageUrl($page),
                'lastmod' => optional($page->updated_at)->toDateString(),
                'priority' => '0.6',
            ]));

        return trim(view('partials.sitemap', ['urls' => $urls])->render());
    }
}
