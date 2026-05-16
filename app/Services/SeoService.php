<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tool;
use App\Support\MediaUrl;
use DOMDocument;
use DOMXPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SeoService
{
    public function defaults(array $overrides = []): array
    {
        $base = [
            'title' => 'BantuKerja.online',
            'description' => 'Tools dan template gratis untuk kerja, bisnis, dan administrasi harian.',
            'canonical' => url()->current(),
            'og_type' => 'website',
            'og_image' => asset('images/og-default.svg'),
            'robots' => 'index,follow,max-image-preview:large',
        ];

        return array_merge($base, $overrides);
    }

    public function forModel(Model $model, array $overrides = []): array
    {
        $title = $model->meta_title ?: $model->title;
        $description = $model->meta_description
            ?: Str::limit(strip_tags($model->short_description ?? $model->excerpt ?? $model->content ?? ''), 160);
        $pageCanonical = match ($model->slug) {
            'about' => route('pages.about'),
            'contact' => route('pages.contact'),
            'privacy-policy' => route('pages.privacy-policy'),
            'terms' => route('pages.terms'),
            'disclaimer' => route('pages.disclaimer'),
            default => filled($model->slug) ? route('pages.show', $model->slug) : url()->current(),
        };
        $canonical = match (true) {
            $model instanceof Tool => filled($model->slug) ? route('tools.show', $model->slug) : url()->current(),
            $model instanceof DocumentTemplate => filled($model->slug) ? route('templates.show', $model->slug) : url()->current(),
            $model instanceof Post => filled($model->slug) ? route('blog.show', $model->slug) : url()->current(),
            $model instanceof Page => $pageCanonical,
            default => url()->current(),
        };

        return $this->defaults(array_merge([
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'og_type' => $model instanceof Post ? 'article' : 'website',
            'og_image' => MediaUrl::resolve($model->og_image) ?? asset('images/og-default.svg'),
        ], $overrides));
    }

    public function breadcrumbs(array $items): array
    {
        return collect($items)->values()->map(function (array $item, int $index): array {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['label'],
                'item' => $item['url'],
            ];
        })->all();
    }

    public function prepareContentWithTableOfContents(string $html): array
    {
        if (blank($html)) {
            return ['html' => $html, 'items' => []];
        }

        $document = new DOMDocument;
        @$document->loadHTML('<?xml encoding="utf-8" ?><div>'.$html.'</div>');
        $xpath = new DOMXPath($document);
        $headings = $xpath->query('//h2|//h3');

        $items = [];

        foreach ($headings as $heading) {
            $text = trim($heading->textContent);

            if ($text === '') {
                continue;
            }

            $id = $heading->attributes?->getNamedItem('id')?->nodeValue ?: Str::slug($text);
            $heading->setAttribute('id', $id);

            $items[] = [
                'id' => $id,
                'text' => $text,
                'level' => $heading->nodeName,
            ];
        }

        $container = $document->getElementsByTagName('div')->item(0);
        $content = '';

        if ($container) {
            foreach ($container->childNodes as $childNode) {
                $content .= $document->saveHTML($childNode);
            }
        }

        return [
            'html' => $content ?: $html,
            'items' => $items,
        ];
    }
}
