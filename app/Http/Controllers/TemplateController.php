<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DocumentTemplate;
use App\Models\Post;
use App\Models\Tool;
use App\Services\SeoService;
use App\Services\TemplateRenderService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateController extends Controller
{
    public function index(Request $request, SeoService $seoService): View
    {
        $templates = DocumentTemplate::query()
            ->with('category')
            ->published()
            ->when($request->string('search')->toString(), function ($query, string $search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder->where('title', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%");
                });
            })
            ->when($request->string('category')->toString(), function ($query, string $category): void {
                $query->whereHas('category', fn ($builder) => $builder->where('slug', $category));
            })
            ->latestPublished()
            ->paginate(9)
            ->withQueryString();

        return view('templates.index', [
            'seo' => $seoService->defaults([
                'title' => 'Template Dokumen Gratis',
                'description' => 'Template surat, CV, invoice, MoU, dan dokumen administrasi lain yang bisa langsung disalin dan disesuaikan.',
            ]),
            'templates' => $templates,
            'categories' => Category::active()->where('type', 'template')->orderBy('name')->get(),
        ]);
    }

    public function show(string $slug, SeoService $seoService): View
    {
        $template = DocumentTemplate::query()
            ->with(['category', 'faqs'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
        $relatedPosts = $template->relatedPosts()->limit(4)->get();
        $relatedTools = $template->relatedTools()->limit(4)->get();

        if ($relatedPosts->isEmpty()) {
            $relatedPosts = Post::published()
                ->latestPublished()
                ->limit(4)
                ->get();
        }

        if ($relatedTools->isEmpty()) {
            $relatedTools = Tool::published()
                ->featured()
                ->latestPublished()
                ->limit(4)
                ->get();
        }

        return view('templates.show', [
            'template' => $template,
            'seo' => $seoService->forModel($template),
            'relatedPosts' => $relatedPosts,
            'relatedTools' => $relatedTools,
        ]);
    }

    public function download(string $slug, TemplateRenderService $templateRenderService)
    {
        $template = DocumentTemplate::published()->where('slug', $slug)->firstOrFail();

        return $templateRenderService->downloadText($template->slug.'.txt', strip_tags($template->content));
    }

    public function downloadWord(string $slug, TemplateRenderService $templateRenderService)
    {
        $template = DocumentTemplate::published()->where('slug', $slug)->firstOrFail();

        return $templateRenderService->downloadWord(
            $template->slug.'.doc',
            $template->title,
            $template->content,
        );
    }
}
