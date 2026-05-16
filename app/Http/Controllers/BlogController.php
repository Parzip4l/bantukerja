<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request, SeoService $seoService): View
    {
        $posts = Post::query()
            ->with('category')
            ->published()
            ->when($request->string('search')->toString(), function ($query, string $search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            })
            ->when($request->string('category')->toString(), function ($query, string $category): void {
                $query->whereHas('category', fn ($builder) => $builder->where('slug', $category));
            })
            ->latestPublished()
            ->paginate(9)
            ->withQueryString();

        return view('blog.index', [
            'seo' => $seoService->defaults([
                'title' => 'Blog BantuKerja.online',
                'description' => 'Artikel edukatif SEO-friendly seputar THR, gaji, invoice, template kerja, administrasi bisnis, dan dokumen harian.',
            ]),
            'posts' => $posts,
            'categories' => Category::active()->where('type', 'blog')->orderBy('name')->get(),
        ]);
    }

    public function show(string $slug, SeoService $seoService): View
    {
        $post = Post::query()
            ->with(['category', 'faqs'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
        $preparedContent = $seoService->prepareContentWithTableOfContents($post->content);

        return view('blog.show', [
            'post' => $post,
            'seo' => $seoService->forModel($post),
            'toc' => $preparedContent['items'],
            'postContent' => $preparedContent['html'],
            'relatedPosts' => $post->relatedPosts()->limit(4)->get(),
            'relatedTools' => $post->relatedTools()->limit(4)->get(),
            'relatedTemplates' => $post->relatedTemplates()->limit(4)->get(),
        ]);
    }
}
